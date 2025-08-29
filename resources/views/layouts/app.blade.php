<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
    $fallbackTitle = config('app.name', 'Balai Diklat Industri Yogyakarta');
    // Jika Livewire render memberikan variable $title, pakai itu.
    $runtimeTitle = isset($title) && trim($title) !== '' ? $title : null;
    @endphp
    <title>
        @hasSection('title')
        @yield('title')
        @elseif($runtimeTitle)
        {{ $runtimeTitle }}
        @else
        {{ $fallbackTitle }}
        @endif
    </title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('icons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('icons/favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('icons/apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <script>
        (function() {
            try {
                // Server-side session (diset ThemeSwitcher@mount/setMode)
                var serverMode = "{{ session('theme-mode', 'default') }}"; // 'default' | 'prefersdark'
                // Client-side cache (tetap dipakai sebagai fallback)
                var clientMode = null;
                try {
                    clientMode = localStorage.getItem('theme-mode');
                } catch (_) {}
                var mode = clientMode || serverMode || 'default';

                var resolved = 'light';
                if (mode === 'prefersdark') {
                    resolved = 'dark';
                } else {
                    // default: ikut preferensi OS
                    resolved = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                }
                document.documentElement.setAttribute('data-theme', resolved);
            } catch (_) {}
        })();
    </script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
</head>

<body>
    <header>
        <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between py-4">
                <a href="{{ url('/') }}" class="flex items-center space-x-3" wire:navigate>
                    <!-- hidden on lower than md screen size -->
                    <img id="kemenperin-logo" class="h-16 hidden md:inline-block" src="{{ asset('images/kemenperin.svg') }}" alt="Kementerian Perindustrian RI - Balai Diklat Industri Yogyakarta">
                    <img id="bdi-yogyakarta-logo" class="h-16" src="{{ asset('images/bdi-yogyakarta-corpu.svg') }}" alt="Balai Diklat Industri Yogyakarta">
                </a>
                <div class="flex items-center space-x-4">
                    <a href="https://bdiyogyakarta.kemenperin.go.id/sidia" class="btn btn-primary">Login SIDIA</a>
                    <livewire:language-switcher />
                    <livewire:theme-switcher />
                </div>
            </div>
        </div>
        <div class="border-y border-base-300 mx-auto px-4">
            @include('layouts.partials.header')
        </div>
    </header>

    <main>
        @if (isset($slot))
        {{ $slot }}
        @else
        @yield('content')
        @endif
    </main>


    @include('layouts.partials.footer')
    {{-- Back to Top Button --}}
    <button id="backToTopBtn" type="button" class="hidden h-12 w-12 fixed bottom-6 right-6 btn btn-rounded btn-primary shadow-lg z-50" title="{{ __('Back to Top') }}" onclick="window.scrollTo({ top: 0, behavior: 'smooth' });">
        <i class="bi bi-chevron-up text-xl"></i>
    </button>

    @livewireScripts
    <script>
        document.addEventListener('livewire:init', () => {
            // add condition when dark change kemenperin and bdi yogyakarta logo to white
            try {
                function updateLogos(theme) {
                    const k = document.getElementById('kemenperin-logo');
                    const b = document.getElementById('bdi-yogyakarta-logo');
                    if (!k || !b) return;
                    const isDark = theme === 'dark';

                    // allow overriding via data attributes on the <img> if desired
                    const kLight = k.dataset.lightSrc || '/images/kemenperin.svg';
                    const kDark = k.dataset.darkSrc || '/images/kemenperin-white.svg';
                    const bLight = b.dataset.lightSrc || '/images/bdi-yogyakarta-corpu.svg';
                    const bDark = b.dataset.darkSrc || '/images/bdi-yogyakarta-corpu-white.svg';

                    k.src = isDark ? kDark : kLight;
                    b.src = isDark ? bDark : bLight;
                }

                // apply current theme immediately
                updateLogos(document.documentElement.getAttribute('data-theme') || 'light');

                // watch for data-theme changes (covers Livewire updates and prefers-color changes)
                const observer = new MutationObserver(mutations => {
                    for (const m of mutations) {
                        if (m.attributeName === 'data-theme') {
                            updateLogos(document.documentElement.getAttribute('data-theme'));
                        }
                    }
                });
                observer.observe(document.documentElement, {
                    attributes: true,
                    attributeFilter: ['data-theme']
                });
            } catch (_) {}

            Livewire.on('theme-changed', ({
                mode
            }) => {
                try {
                    localStorage.setItem('theme-mode', mode);
                    const resolved = (mode === 'prefersdark') ?
                        'dark' :
                        (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
                    document.documentElement.setAttribute('data-theme', resolved);
                } catch (_) {}
            });

            const mq = window.matchMedia('(prefers-color-scheme: dark)');
            mq.addEventListener?.('change', e => {
                try {
                    const mode = localStorage.getItem('theme-mode') || 'default';
                    if (mode === 'default') {
                        document.documentElement.setAttribute('data-theme', e.matches ? 'dark' : 'light');
                    }
                } catch (_) {}
            });

            Livewire.on('locale-changed', ({
                locale
            }) => {
                try {
                    // Update <html lang=".."> di sisi klien biar langsung akurat
                    document.documentElement.setAttribute('lang', (locale || 'id').replace('_', '-'));
                } catch (_) {}

                // Pertahankan perilaku sekarang: re-morph / reload agar semua terjemahan tersetel dari server
                const url = window.location.pathname + window.location.search + window.location.hash;
                if (typeof Livewire.navigate === 'function') {
                    Livewire.navigate(url, {
                        replace: true,
                        scroll: false,
                        preserveScroll: true
                    });
                } else {
                    window.location.reload();
                }
            });
        });
        document.addEventListener("DOMContentLoaded", () => {
            const btn = document.getElementById("backToTopBtn");

            window.addEventListener("scroll", () => {
                if (window.scrollY > 200) {
                    btn.classList.remove("hidden");
                } else {
                    btn.classList.add("hidden");
                }
            });
        });
    </script>


    <!-- Stack for additional scripts -->
    @stack('scripts')
</body>

</html>