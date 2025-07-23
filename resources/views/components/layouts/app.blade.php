<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        @vite('resources/css/app.css')
        <title>{{ $title ?? 'Page Title' }}</title>
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="bg-white">
            @yield('hero')
        </div>
        <main>
            {{ $slot }}
        </main>

        @include('layouts.partials.footer')
    </body>
</html>
