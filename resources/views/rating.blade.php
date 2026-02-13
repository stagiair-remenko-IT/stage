<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="theme-color" content="#f0f2f5">

    <title>{{ config('app.name', 'Remenko') }} - {{ __('Rating') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/rating-app.js'])
</head>
<body class="rating-app bg-gray-100 min-h-screen min-h-[100dvh] flex flex-col touch-manipulation select-none overflow-hidden">
    <div id="rating-app" class="flex-1 flex flex-col relative">
        {{-- Language toggle - top right --}}
        <div class="absolute top-4 right-4 z-50 flex gap-2">
            <button type="button" data-lang="nl" class="lang-btn px-4 py-2 rounded-lg text-sm font-medium transition-colors min-h-[48px] touch-target" aria-label="Nederlands">NL</button>
            <button type="button" data-lang="en" class="lang-btn px-4 py-2 rounded-lg text-sm font-medium transition-colors min-h-[48px] touch-target" aria-label="English">EN</button>
        </div>

        {{-- Screen 1: Rating --}}
        <div id="screen-rating" class="rating-screen flex-1 flex flex-col items-center justify-center p-8 gap-8">
            <h1 class="text-3xl md:text-4xl font-semibold text-gray-800 text-center" data-i18n="ratingTitle">Beoordeel onze service</h1>
            <div class="flex flex-wrap justify-center gap-12 md:gap-20 items-end">
                <button type="button" data-score="bad" class="rating-btn flex flex-col items-center gap-4 py-4 touch-target min-h-[180px] hover:opacity-90 active:scale-95 transition-transform">
                    <svg class="w-32 h-32 md:w-40 md:h-40" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <circle cx="50" cy="50" r="45" fill="#dc2626"/>
                        <circle cx="35" cy="42" r="5" fill="white"/>
                        <circle cx="65" cy="42" r="5" fill="white"/>
                        <path d="M35 62 Q50 72 65 62" stroke="white" stroke-width="4" stroke-linecap="round" fill="none"/>
                    </svg>
                    <span class="text-xl font-medium text-red-600" data-i18n="bad">Slecht</span>
                </button>
                <button type="button" data-score="neutral" class="rating-btn flex flex-col items-center gap-4 py-4 touch-target min-h-[180px] hover:opacity-90 active:scale-95 transition-transform">
                    <svg class="w-32 h-32 md:w-40 md:h-40" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <circle cx="50" cy="50" r="45" fill="#ea580c"/>
                        <circle cx="35" cy="42" r="4" fill="white"/>
                        <circle cx="65" cy="42" r="4" fill="white"/>
                        <line x1="30" y1="65" x2="70" y2="65" stroke="white" stroke-width="4" stroke-linecap="round"/>
                    </svg>
                    <span class="text-xl font-medium text-orange-600" data-i18n="neutral">Neutraal</span>
                </button>
                <button type="button" data-score="good" class="rating-btn flex flex-col items-center gap-4 py-4 touch-target min-h-[180px] hover:opacity-90 active:scale-95 transition-transform">
                    <svg class="w-32 h-32 md:w-40 md:h-40" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <circle cx="50" cy="50" r="45" fill="#16a34a"/>
                        <circle cx="35" cy="42" r="4" fill="white"/>
                        <circle cx="65" cy="42" r="4" fill="white"/>
                        <path d="M30 58 Q50 72 70 58" stroke="white" stroke-width="4" stroke-linecap="round" fill="none"/>
                    </svg>
                    <span class="text-xl font-medium text-green-600" data-i18n="good">Goed</span>
                </button>
            </div>
        </div>

        {{-- Screen 2: Feedback --}}
        <div id="screen-feedback" class="feedback-screen hidden flex-1 flex flex-col items-center justify-center p-8 gap-6">
            <h2 class="text-2xl md:text-3xl font-semibold text-gray-800 text-center" data-i18n="feedbackTitle">Wil je de keuze kort toelichten?</h2>
            <div class="w-full max-w-xl">
                <textarea id="feedback-input" rows="4" maxlength="500" placeholder="" class="w-full p-4 text-lg rounded-xl border-2 border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition-all resize-none touch-target" data-i18n-placeholder="feedbackPlaceholder"></textarea>
                <p class="text-sm text-gray-500 mt-2 text-right" id="char-count">0 / 500</p>
            </div>
            <button type="button" id="feedback-submit" class="flex items-center gap-2 px-8 py-4 bg-green-600 hover:bg-green-700 text-white text-lg font-semibold rounded-xl transition-colors touch-target min-h-[60px]">
                <span data-i18n="send">Versturen</span>
                <span aria-hidden="true">→</span>
            </button>
        </div>

        {{-- Screen 3: Thanks --}}
        <div id="screen-thanks" class="thanks-screen hidden absolute inset-0 bg-cover bg-center bg-no-repeat" style="background-image: url('{{ config('services.thanks_image_url') ?: asset('images/thanks-bg.png') }}');">
            <div class="absolute inset-0 flex items-center justify-center bg-black/30">
                <h2 class="text-5xl md:text-7xl font-bold text-white drop-shadow-lg" data-i18n="thanks">Bedankt!</h2>
            </div>
        </div>
    </div>
</body>
</html>
