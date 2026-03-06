<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="theme-color" content="#ffffff">

    <title>{{ config('app.name', 'Remenko') }} - {{ __('Rating') }}</title>

    @if($kitId = config('services.adobe_fonts_kit_id'))
    <link rel="preconnect" href="https://use.typekit.net">
    <link rel="stylesheet" href="https://use.typekit.net/{{ $kitId }}.css">
    @else
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet">
    @endif

    @vite(['resources/css/app.css', 'resources/js/rating-app.js'])
</head>
<body class="rating-app relative min-h-screen min-h-[100dvh] flex flex-col touch-manipulation select-none overflow-hidden bg-white">
    <div id="rating-app" class="flex-1 flex flex-col relative z-10">
        {{-- Language toggle - top right --}}
        <div class="absolute top-4 right-4 z-50 flex gap-2">
            <button type="button" data-lang="nl" class="lang-btn px-4 py-2 rounded-lg text-sm font-medium transition-colors min-h-[48px] touch-target" aria-label="Nederlands">NL</button>
            <button type="button" data-lang="en" class="lang-btn px-4 py-2 rounded-lg text-sm font-medium transition-colors min-h-[48px] touch-target" aria-label="English">EN</button>
        </div>

        {{-- Screen 1: Rating --}}
        <div id="screen-rating" class="screen rating-screen flex flex-col items-center justify-center p-8 gap-8">
            <h1 class="text-3xl md:text-4xl font-semibold text-gray-800 text-center" data-i18n="ratingTitle">Beoordeel hier jouw winkelervaring</h1>
            <div class="flex flex-wrap justify-center gap-4 md:gap-6 items-end">
                <button type="button" data-score="very_bad" class="rating-btn flex flex-col items-center gap-2 py-4 touch-target min-h-[160px] hover:opacity-90 active:scale-95 transition-transform">
                    <img src="{{ asset('images/rating-very-bad.png') }}" alt="" class="w-20 h-20 md:w-24 md:h-24 object-contain" aria-hidden="true">
                    <span class="text-sm md:text-base font-medium text-red-700" data-i18n="veryBad">Zeer slecht</span>
                </button>
                <button type="button" data-score="bad" class="rating-btn flex flex-col items-center gap-2 py-4 touch-target min-h-[160px] hover:opacity-90 active:scale-95 transition-transform">
                    <img src="{{ asset('images/rating-bad.png') }}" alt="" class="w-20 h-20 md:w-24 md:h-24 object-contain" aria-hidden="true">
                    <span class="text-sm md:text-base font-medium text-red-600" data-i18n="bad">Slecht</span>
                </button>
                <button type="button" data-score="neutral" class="rating-btn flex flex-col items-center gap-2 py-4 touch-target min-h-[160px] hover:opacity-90 active:scale-95 transition-transform">
                    <img src="{{ asset('images/rating-neutral.png') }}" alt="" class="w-20 h-20 md:w-24 md:h-24 object-contain" aria-hidden="true">
                    <span class="text-sm md:text-base font-medium text-orange-600" data-i18n="neutral">Neutraal</span>
                </button>
                <button type="button" data-score="good" class="rating-btn flex flex-col items-center gap-2 py-4 touch-target min-h-[160px] hover:opacity-90 active:scale-95 transition-transform">
                    <img src="{{ asset('images/rating-good.png') }}" alt="" class="w-20 h-20 md:w-24 md:h-24 object-contain" aria-hidden="true">
                    <span class="text-sm md:text-base font-medium text-green-600" data-i18n="good">Goed</span>
                </button>
                <button type="button" data-score="very_good" class="rating-btn flex flex-col items-center gap-2 py-4 touch-target min-h-[160px] hover:opacity-90 active:scale-95 transition-transform">
                    <img src="{{ asset('images/rating-very-good.png') }}" alt="" class="w-20 h-20 md:w-24 md:h-24 object-contain" aria-hidden="true">
                    <span class="text-sm md:text-base font-medium text-green-700" data-i18n="veryGood">Zeer goed</span>
                </button>
            </div>
        </div>

        {{-- Screen 2: Demographics (optional) --}}
        <div id="screen-demographics" class="screen screen-hidden demographics-screen flex flex-col items-center justify-center p-8 gap-6">
            <h2 class="text-2xl md:text-3xl font-semibold text-gray-800 text-center" data-i18n="demographicsTitle">Help ons verbeteren</h2>
            <p class="text-gray-600 text-center -mt-2" data-i18n="demographicsSubtitle">Optioneel – anoniem</p>
            <div class="w-full max-w-md space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2" data-i18n="ageRange">Leeftijd</label>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" data-age="<25" class="demo-btn age-btn px-4 py-2 rounded-lg border-2 border-gray-300 text-gray-700 font-medium transition-colors touch-target">&lt;25</button>
                        <button type="button" data-age="25-40" class="demo-btn age-btn px-4 py-2 rounded-lg border-2 border-gray-300 text-gray-700 font-medium transition-colors touch-target">25-40</button>
                        <button type="button" data-age="40-50" class="demo-btn age-btn px-4 py-2 rounded-lg border-2 border-gray-300 text-gray-700 font-medium transition-colors touch-target">40-50</button>
                        <button type="button" data-age="50-65" class="demo-btn age-btn px-4 py-2 rounded-lg border-2 border-gray-300 text-gray-700 font-medium transition-colors touch-target">50-65</button>
                        <button type="button" data-age="65+" class="demo-btn age-btn px-4 py-2 rounded-lg border-2 border-gray-300 text-gray-700 font-medium transition-colors touch-target">65+</button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2" data-i18n="gender">Geslacht</label>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" data-gender="male" class="demo-btn gender-btn px-4 py-2 rounded-lg border-2 border-gray-300 text-gray-700 font-medium transition-colors touch-target" data-i18n="male">Man</button>
                        <button type="button" data-gender="female" class="demo-btn gender-btn px-4 py-2 rounded-lg border-2 border-gray-300 text-gray-700 font-medium transition-colors touch-target" data-i18n="female">Vrouw</button>
                        <button type="button" data-gender="other" class="demo-btn gender-btn px-4 py-2 rounded-lg border-2 border-gray-300 text-gray-700 font-medium transition-colors touch-target" data-i18n="other">Anders</button>
                        <button type="button" data-gender="prefer_not_to_say" class="demo-btn gender-btn px-4 py-2 rounded-lg border-2 border-gray-300 text-gray-700 font-medium transition-colors touch-target" data-i18n="preferNotToSay">Zeg ik liever niet</button>
                    </div>
                </div>
            </div>
            <div class="flex gap-4 mt-2">
                <button type="button" id="demographics-skip" class="px-6 py-3 text-gray-700 font-medium rounded-xl border-2 border-gray-300 bg-gray-50 hover:bg-gray-100 transition-colors touch-target" data-i18n="skip">Overslaan</button>
                <button type="button" id="demographics-continue" class="px-8 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl transition-colors touch-target disabled:bg-gray-300 disabled:hover:bg-gray-300" data-i18n="continue">Doorgaan</button>
            </div>
        </div>

        {{-- Screen 3: Feedback --}}
        <div id="screen-feedback" class="screen screen-hidden feedback-screen flex flex-col items-center justify-center overflow-hidden p-4 gap-3 z-[60] isolate">
            <h2 class="text-xl md:text-2xl font-semibold text-gray-800 text-center shrink-0" data-i18n="feedbackTitle">Wil je de keuze kort toelichten?</h2>
            <div class="w-full max-w-xl shrink-0">
                <textarea id="feedback-input" rows="2" maxlength="500" placeholder="" dir="ltr" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" class="w-full p-3 text-base rounded-lg border-2 border-gray-300 bg-white focus:border-green-500 focus:ring-2 focus:ring-green-200/50 outline-none transition-all resize-none touch-target placeholder:text-gray-600 select-text touch-manipulation text-left" data-i18n-placeholder="feedbackPlaceholder"></textarea>
                <p class="text-xs text-gray-500 mt-1 text-right" id="char-count">0 / 500</p>
            </div>
            <div class="flex gap-3 shrink-0">
                <button type="button" id="feedback-skip" class="px-5 py-2.5 text-gray-700 font-medium rounded-lg border-2 border-gray-300 bg-gray-50 hover:bg-gray-100 transition-colors touch-target text-sm" data-i18n="skip">Overslaan</button>
                <button type="button" id="feedback-submit" class="flex items-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors touch-target min-h-[48px] disabled:bg-gray-300 disabled:hover:bg-gray-300 text-sm">
                    <span data-i18n="send">Versturen</span>
                    <span aria-hidden="true">→</span>
                </button>
            </div>
            {{-- Virtual keyboard - compact dark theme --}}
            <div id="virtual-keyboard" class="virtual-keyboard hidden w-full max-w-xl shrink-0 mt-1 p-2 touch-manipulation" aria-hidden="true">
                <div class="keyboard-rows flex flex-col gap-0.5"></div>
            </div>
        </div>

        {{-- Screen 4: Thanks --}}
        <div id="screen-thanks" class="screen screen-thanks screen-hidden thanks-screen bg-cover bg-center bg-no-repeat" style="background-image: url('{{ config('services.thanks_image_url') ?: asset('images/thanks-bg.png') }}');">
            <div class="absolute inset-0 flex items-center justify-center bg-black/30">
                <h2 class="text-5xl md:text-7xl font-bold text-white drop-shadow-lg" data-i18n="thanks">Bedankt!</h2>
            </div>
        </div>
    </div>

    {{-- Fixed logo bottom left (all screens) --}}
    <div class="fixed bottom-4 left-4 z-50 pointer-events-none">
        <img src="{{ asset('images/logo-remenko.png') }}" alt="Remenko" class="h-28 md:h-36 w-auto object-contain">
    </div>
    <style>
        #virtual-keyboard .key { background: #4a4a4a !important; background-color: #4a4a4a !important; color: #f1f5f9 !important; border-radius: 4px !important; }
        #virtual-keyboard .key:hover { background: #5a5a5a !important; background-color: #5a5a5a !important; }
        #virtual-keyboard .key:active { background: #6a6a6a !important; background-color: #6a6a6a !important; }
        #virtual-keyboard .key.key-special { background: #3a3a3a !important; background-color: #3a3a3a !important; }
        #virtual-keyboard .key.key-special:hover { background: #4a4a4a !important; background-color: #4a4a4a !important; }
        #virtual-keyboard .key.key-special:active { background: #5a5a5a !important; background-color: #5a5a5a !important; }
    </style>
</body>
</html>
