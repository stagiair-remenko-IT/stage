/**
 * Remenko Customer Rating Touchscreen App
 * Screens: Rating -> Feedback (5s timeout) -> Thanks (3s) -> Rating
 */

const translations = {
    nl: {
        ratingTitle: 'Beoordeel onze service',
        bad: 'Slecht',
        neutral: 'Neutraal',
        good: 'Goed',
        feedbackTitle: 'Wil je de keuze kort toelichten?',
        feedbackPlaceholder: 'Typ hier je feedback...',
        send: 'Versturen',
        thanks: 'Bedankt!',
    },
    en: {
        ratingTitle: 'Rate our service',
        bad: 'Bad',
        neutral: 'Neutral',
        good: 'Good',
        feedbackTitle: 'Would you like to briefly explain your choice?',
        feedbackPlaceholder: 'Type your feedback here...',
        send: 'Send',
        thanks: 'Thank you!',
    },
};

const STORAGE_KEY = 'remenko_lang';
const FEEDBACK_TIMEOUT_MS = 5000;
const THANKS_DISPLAY_MS = 3000;

let currentLocale = 'nl';
let currentRatingId = null;
let feedbackTimeoutId = null;

const screens = {
    rating: document.getElementById('screen-rating'),
    feedback: document.getElementById('screen-feedback'),
    thanks: document.getElementById('screen-thanks'),
};

const feedbackInput = document.getElementById('feedback-input');
const feedbackSubmit = document.getElementById('feedback-submit');
const charCount = document.getElementById('char-count');

function init() {
    currentLocale = localStorage.getItem(STORAGE_KEY) || 'nl';
    updateLangButtons();
    applyTranslations();
    bindEvents();
}

function bindEvents() {
    document.querySelectorAll('.rating-btn').forEach((btn) => {
        btn.addEventListener('click', () => handleRatingClick(btn.dataset.score));
    });

    document.querySelectorAll('.lang-btn').forEach((btn) => {
        btn.addEventListener('click', () => {
            currentLocale = btn.dataset.lang;
            localStorage.setItem(STORAGE_KEY, currentLocale);
            updateLangButtons();
            applyTranslations();
        });
    });

    feedbackInput.addEventListener('input', () => {
        resetFeedbackTimeout();
        updateCharCount();
    });
    feedbackInput.addEventListener('keydown', resetFeedbackTimeout);
    feedbackInput.addEventListener('focus', resetFeedbackTimeout);
    feedbackInput.addEventListener('touchstart', resetFeedbackTimeout);

    feedbackSubmit.addEventListener('click', handleFeedbackSubmit);
}

function updateLangButtons() {
    document.querySelectorAll('.lang-btn').forEach((btn) => {
        const isActive = btn.dataset.lang === currentLocale;
        btn.classList.toggle('bg-gray-800', isActive);
        btn.classList.toggle('text-white', isActive);
        btn.classList.toggle('bg-gray-200', !isActive);
        btn.classList.toggle('text-gray-700', !isActive);
    });
}

function applyTranslations() {
    const t = translations[currentLocale];
    document.querySelectorAll('[data-i18n]').forEach((el) => {
        const key = el.dataset.i18n;
        if (t[key]) el.textContent = t[key];
    });
    document.querySelectorAll('[data-i18n-placeholder]').forEach((el) => {
        const key = el.dataset.i18nPlaceholder;
        if (t[key]) el.placeholder = t[key];
    });
    updateCharCount();
}

function updateCharCount() {
    const len = feedbackInput.value.length;
    charCount.textContent = `${len} / 500`;
}

function showScreen(name) {
    screens.rating.classList.add('hidden');
    screens.feedback.classList.add('hidden');
    screens.thanks.classList.add('hidden');

    if (name === 'rating') screens.rating.classList.remove('hidden');
    else if (name === 'feedback') screens.feedback.classList.remove('hidden');
    else if (name === 'thanks') screens.thanks.classList.remove('hidden');
}

function handleRatingClick(score) {
    clearFeedbackTimeout();
    submitRating(score).then((data) => {
        currentRatingId = data?.id ?? null;
        feedbackInput.value = '';
        updateCharCount();
        startFeedbackTimeout();
        showScreen('feedback');
    }).catch(() => {
        showScreen('thanks');
        setTimeout(() => showScreen('rating'), THANKS_DISPLAY_MS);
    });
}

function submitRating(score) {
    return fetch('/api/ratings', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({ score, location: 'showroom' }),
    })
        .then((r) => {
            if (!r.ok) throw new Error('Rating failed');
            return r.json();
        });
}

function startFeedbackTimeout() {
    clearFeedbackTimeout();
    feedbackTimeoutId = setTimeout(() => {
        feedbackTimeoutId = null;
        goToThanks();
    }, FEEDBACK_TIMEOUT_MS);
}

function resetFeedbackTimeout() {
    startFeedbackTimeout();
}

function clearFeedbackTimeout() {
    if (feedbackTimeoutId) {
        clearTimeout(feedbackTimeoutId);
        feedbackTimeoutId = null;
    }
}

function handleFeedbackSubmit() {
    const text = feedbackInput.value.trim();
    if (text) {
        clearFeedbackTimeout();
        submitFeedback(text).finally(() => goToThanks());
    } else {
        goToThanks();
    }
}

function submitFeedback(text) {
    return fetch('/api/feedback', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({ rating_id: currentRatingId, text }),
    }).then((r) => {
        if (!r.ok) throw new Error('Feedback failed');
        return r.json();
    });
}

function goToThanks() {
    showScreen('thanks');
    setTimeout(() => {
        currentRatingId = null;
        showScreen('rating');
    }, THANKS_DISPLAY_MS);
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}
