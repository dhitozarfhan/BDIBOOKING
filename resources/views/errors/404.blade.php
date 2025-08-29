@extends('layouts.app')

@section('title', '404 – '. __('Page not found'))

@section('content')
<div class="min-h-[70vh] flex flex-col items-center justify-center text-center px-6 py-12">
    <h1 class="text-6xl font-bold text-error">404</h1>
    <h2 class="mt-4 text-2xl font-semibold">{{ __('Page not found') }}</h2>
    <p class="mt-2 text-base-content/70 max-w-md">
        {{ __('Sorry, the page you are looking for is not available or has been moved.') }}
    </p>

    <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
        <a wire:navigate href="{{ url('/') }}" class="btn btn-primary">
            ← {{ __('Back to Home') }}
        </a>
        @if(url()->previous())
            <a wire:navigate href="{{ url()->previous() }}" class="btn btn-outline">
                🔙 {{ __('Previous Page') }}
            </a>
        @endif
    </div>
</div>
@endsection
