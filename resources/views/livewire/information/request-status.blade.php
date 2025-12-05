<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-primary-600 px-6 py-4">
            <h2 class="text-xl font-bold text-white">{{ __('Check Information Request Status') }}</h2>
        </div>
        
        <div class="p-6">
            @if (session()->has('statusError'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('statusError') }}</span>
                </div>
            @endif

            <form wire:submit.prevent="checkStatus">
                <div class="mb-4">
                    <label for="registration_code" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Registration Code') }}</label>
                    <input type="text" wire:model="registration_code" id="registration_code" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('registration_code') border-red-500 @enderror" placeholder="Enter your registration code">
                    @error('registration_code') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        {{ __('Check Status') }}
                    </button>
                    <a href="{{ route('information.request') }}" class="inline-block align-baseline font-bold text-sm text-primary-600 hover:text-primary-800">
                        {{ __('Back to Form') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
