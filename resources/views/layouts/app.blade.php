<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="{{ session('theme-mode', 'default') == 'default' ? 'light' : 'dark' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'BDI Yogyakarta'))</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('icons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('icons/favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('icons/apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <script>
    (function () {
        try {
            const saved = localStorage.getItem('theme-mode'); // 'default' | 'prefersdark'
            let theme = 'light';
            if (saved === 'prefersdark') {
            theme = 'dark';
            } else {
            // default: ikut prefers-color-scheme
            theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }
            document.documentElement.setAttribute('data-theme', theme);
        } catch (_) {}
        })();
    </script>
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
</head>

<body class="font-sans antialiased">
    <header>
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between py-4">
                <a href="{{ url('/') }}" class="flex items-center space-x-3" wire:navigate>
                    <img id="kemenperin-logo" class="h-16" src="{{ asset('images/kemenperin.svg') }}" alt="Kementerian Perindustris RI - Balai Diklat Industri Yogyakarta">
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

    @stack('modals')
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
                const kDark  = k.dataset.darkSrc  || '/images/kemenperin-white.svg';
                const bLight = b.dataset.lightSrc || '/images/bdi-yogyakarta-corpu.svg';
                const bDark  = b.dataset.darkSrc  || '/images/bdi-yogyakarta-corpu-white.svg';

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
            observer.observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });
        } catch (_) {}
        
        Livewire.on('theme-changed', ({ mode }) => {
            try {
            localStorage.setItem('theme-mode', mode);
            const resolved = (mode === 'prefersdark')
                ? 'dark'
                : (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
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

        Livewire.on('locale-changed', ({ locale }) => {
            // window.location.reload();
            const url = window.location.pathname + window.location.search + window.location.hash;

            if (typeof Livewire.navigate === 'function') {
                // Morph seluruh dokumen tanpa hard reload
                Livewire.navigate(url, {
                    replace: true,       // tidak menambah history baru
                    scroll: false,       // pertahankan posisi scroll
                    preserveScroll: true // alias keamanan
                });
            } else {
                // Fallback bila navigate belum tersedia (mis. Livewire < v3)
                window.location.reload();
            }
        });
    });
    </script>


    <!-- Stack for additional scripts -->
    @stack('scripts')
</body>
</html>
