<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="corporate">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
</head>

<body class="font-sans antialiased bg-gray-100">
    <header class="bg-white">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between py-4">
                <a href="{{ url('/') }}" class="flex items-center space-x-3" wire:navigate>
                    <img class="h-16 w-auto" src="{{ asset('images/kemenperin.svg') }}" alt="Kementerian Perindustris RI - Balai Diklat Industri Yogyakarta">
                    <img class="h-16" src="{{ asset('images/bdi-yogyakarta-corpu.svg') }}" alt="Balai Diklat Industri Yogyakarta">
                </a>

                <a href="https://bdiyogyakarta.kemenperin.go.id/sidia" class="btn btn-primary">Login SIDIA</a>

            </div>
        </div>
        <hr class="border-gray-200">
        <div class="container mx-auto px-4">
            @include('layouts.partials.header')
        </div>
    </header>
    <!-- <div class="sticky z-50 top-0">
        
    </div> -->

    <div class="bg-white" style="background-image: url('{{ asset('images/background/bg-batik.png') }}'); background-size: contain;">
        @yield('carousel')
    </div>

    <div class="bg-white">
        @yield('hero')
    </div>

    <main>
        {{ $slot }}
    </main>

    @include('layouts.partials.footer')

    @stack('modals')
    @livewireScripts

    <!-- Stack for additional scripts -->
    @stack('scripts')
</body>
</html>
