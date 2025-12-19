@section('title', __('Public Complaint Status'))
<div class="p-4 sm:p-8 bg-base-100 shadow sm:rounded-lg" style="min-height: 45vh;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @php
            $breadcrumbs = [
                ['label' => __('Beranda'), 'url' => route('home')],
                ['label' => __('Public Complaint'), 'url' => route('information.question')],
                ['label' => __('Report Status')]
            ];
        @endphp
        @include('livewire.information.partials.breadcrumb', ['items' => $breadcrumbs])
        <h2 class="text-2xl font-bold text-base-content mt-4">
            {{ __('Public Complaint Status') }}
        </h2>
        <p class="mt-2 text-base-content/80">
            {{ __('Enter the registration code to see the status of your complaint.') }}
        </p>

        @if (session()->has('statusError'))
            <div class="alert alert-error mt-4">
                <div>
                    <i class="bi bi-exclamation-triangle-fill text-xl mr-3"></i>
                    <span>{{ session('statusError') }}</span>
                </div>
            </div>
        @endif

        <form wire:submit.prevent="checkStatus" class="mt-6 space-y-6">
            <div class="space-y-4">
                <div>
                    <label for="registration_code" class="label">
                        <span class="label-text">{{ __('Registration Code') }} <span class="text-red-500">*</span></span>
                    </label>
                    <div class="relative">
                        <input id="registration_code" type="text" class="input input-bordered w-full" wire:model.lazy="registration_code" placeholder="{{ __('Enter your registration code') }}">
                    </div>
                    @error('registration_code') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="flex items-center gap-4 mt-6">
                <button type="submit" class="btn btn-primary">
                  {{ __('Check Status') }}
                </button>
                <a href="{{ route('information.question') }}" class="btn btn-ghost btn-outline" wire:navigate>
                    {{ __('Back') }}
                </a>
            </div>
        </form>
    </div>
</div>
