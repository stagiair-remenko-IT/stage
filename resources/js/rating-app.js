/**
 * Remenko Customer Rating Touchscreen App
 * Screens: Rating -> Feedback (5s timeout) -> Thanks (3s) -> Rating
 */

const translations = {
    nl: {
        ratingTitle: 'Waardeer onze diensten',
        veryBad: 'Zeer slecht',
        bad: 'Slecht',
        neutral: 'Neutraal',
        good: 'Goed',
        veryGood: 'Zeer goed',
        demographicsTitle: 'Help ons verbeteren',
        demographicsSubtitle: 'Optioneel – anoniem',
        ageRange: 'Leeftijd',
        gender: 'Geslacht',
        male: 'Man',
        female: 'Vrouw',
        other: 'Anders',
        preferNotToSay: 'Zeg ik liever niet',
        skip: 'Overslaan',
        continue: 'Doorgaan',
        feedbackTitle: 'Wil je de keuze kort toelichten?',
        feedbackPlaceholder: 'Typ hier je feedback...',
        send: 'Versturen',
        thanks: 'Bedankt!',
    },
    en: {
        ratingTitle: 'Value our services',
        veryBad: 'Very bad',
        bad: 'Bad',
        neutral: 'Neutral',
        good: 'Good',
        veryGood: 'Very good',
        demographicsTitle: 'Help us improve',
        demographicsSubtitle: 'Optional – anonymous',
        ageRange: 'Age',
        gender: 'Gender',
        male: 'Male',
        female: 'Female',
        other: 'Other',
        preferNotToSay: 'Prefer not to say',
        skip: 'Skip',
        continue: 'Continue',
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
    demographics: document.getElementById('screen-demographics'),
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
    document.getElementById('feedback-skip').addEventListener('click', () => {
        clearFeedbackTimeout();
        goToThanks();
    });

    document.querySelectorAll('.age-btn').forEach((btn) => {
        btn.addEventListener('click', () => {
            selectedAge = selectedAge === btn.dataset.age ? null : btn.dataset.age;
            document.querySelectorAll('.age-btn').forEach((b) => {
                b.classList.toggle('border-green-500', b.dataset.age === selectedAge);
                b.classList.toggle('bg-green-50', b.dataset.age === selectedAge);
                b.classList.toggle('border-gray-300', b.dataset.age !== selectedAge);
            });
        });
    });
    document.querySelectorAll('.gender-btn').forEach((btn) => {
        btn.addEventListener('click', () => {
            selectedGender = selectedGender === btn.dataset.gender ? null : btn.dataset.gender;
            document.querySelectorAll('.gender-btn').forEach((b) => {
                b.classList.toggle('border-green-500', b.dataset.gender === selectedGender);
                b.classList.toggle('bg-green-50', b.dataset.gender === selectedGender);
                b.classList.toggle('border-gray-300', b.dataset.gender !== selectedGender);
            });
        });
    });
    document.getElementById('demographics-skip').addEventListener('click', () => goToFeedback());
    document.getElementById('demographics-continue').addEventListener('click', handleDemographicsContinue);
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
    screens.rating.classList.add('screen-hidden');
    screens.demographics.classList.add('screen-hidden');
    screens.feedback.classList.add('screen-hidden');
    screens.thanks.classList.add('screen-hidden');

    if (name === 'rating') screens.rating.classList.remove('screen-hidden');
    else if (name === 'demographics') screens.demographics.classList.remove('screen-hidden');
    else if (name === 'feedback') screens.feedback.classList.remove('screen-hidden');
    else if (name === 'thanks') screens.thanks.classList.remove('screen-hidden');
}

function handleRatingClick(score) {
    clearFeedbackTimeout();
    submitRating(score).then((data) => {
        currentRatingId = data?.id ?? null;
        resetDemographicsSelection();
        showScreen('demographics');
    }).catch(() => {
        showScreen('thanks');
        setTimeout(() => showScreen('rating'), THANKS_DISPLAY_MS);
    });
}

let selectedAge = null;
let selectedGender = null;

function resetDemographicsSelection() {
    selectedAge = null;
    selectedGender = null;
    document.querySelectorAll('.age-btn').forEach((btn) => {
        btn.classList.remove('border-green-500', 'bg-green-50');
        btn.classList.add('border-gray-300');
    });
    document.querySelectorAll('.gender-btn').forEach((btn) => {
        btn.classList.remove('border-green-500', 'bg-green-50');
        btn.classList.add('border-gray-300');
    });
}

function goToFeedback() {
    feedbackInput.value = '';
    updateCharCount();
    startFeedbackTimeout();
    showScreen('feedback');
}

function handleDemographicsContinue() {
    if (!currentRatingId || (!selectedAge && !selectedGender)) {
        goToFeedback();
        return;
    }
    updateDemographics(selectedAge, selectedGender).finally(() => goToFeedback());
}

function updateDemographics(ageRange, gender) {
    return fetch(`/api/ratings/${currentRatingId}`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({
            age_range: ageRange || null,
            gender: gender || null,
        }),
    }).then((r) => {
        if (!r.ok) throw new Error('Update failed');
        return r.json();
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
