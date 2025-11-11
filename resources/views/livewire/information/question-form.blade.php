<div class="container max-w-7xl flex mx-auto px-4 mt-10">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        <div class="lg:col-span-8">
            <div class="bg-base-100 p-8 rounded-lg shadow-md w-full mb-16">
                <h1 class="text-4xl font-bold text-base-content">{{ __('information.public_question') }}</h1>
                <hr class="my-8">
                @if (session()->has('message'))
                    <div class="alert alert-success shadow-lg" role="alert">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>{{ session('message') }}</span>
                    </div>
                @else
                    <div class="alert alert-info shadow-lg" role="alert">
                        <i class="bi bi-info-circle-fill"></i>
                        <p class="m-0">{{ __('information.question_hint') }}</p>
                    </div>
                @endif
                <form wire:submit.prevent="save" class="my-5 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Subject -->
                        <div class="form-control">
                            <label for="subject" class="label"><span class="label-text font-medium">{{ __('information.subject') }}</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <i class="bi bi-chat-left-text-fill text-gray-400"></i>
                                </span>
                                <input type="text" id="subject" wire:model="subject" class="input input-bordered w-full pl-10" placeholder="{{ __('Subject of your question') }}">
                            </div>
                            @error('subject') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Name -->
                        <div class="form-control">
                            <label for="name" class="label"><span class="label-text font-medium">{{ __('information.name') }}</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <i class="bi bi-person-fill text-gray-400"></i>
                                </span>
                                <input type="text" id="name" wire:model="name" class="input input-bordered w-full pl-10" placeholder="{{ __('Your full name') }}">
                            </div>
                            @error('name') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Mobile -->
                        <div class="form-control">
                            <label for="mobile" class="label"><span class="label-text font-medium">{{ __('information.mobile') }}</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <i class="bi bi-telephone-fill text-gray-400"></i>
                                </span>
                                <input type="text" id="mobile" wire:model="mobile" class="input input-bordered w-full pl-10" placeholder="{{ __('e.g., 08123456789') }}">
                            </div>
                            @error('mobile') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Email -->
                        <div class="form-control">
                            <label for="email" class="label"><span class="label-text font-medium">{{ __('information.email') }}</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <i class="bi bi-envelope-fill text-gray-400"></i>
                                </span>
                                <input type="email" id="email" wire:model="email" class="input input-bordered w-full pl-10" placeholder="{{ __('your.email@example.com') }}">
                            </div>
                            @error('email') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Message -->
                    <div class="form-control">
                        <label for="content" class="label"><span class="label-text font-medium">{{ __('information.message') }}</span></label>
                        <textarea id="content" wire:model="content" rows="6" class="textarea textarea-bordered w-full" placeholder="{{ __('Write your question or message here...') }}"></textarea>
                        @error('content') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end items-center gap-4 pt-4">
                        <div wire:loading wire:target="save" class="flex items-center text-sm text-primary">
                            <span class="loading loading-spinner loading-sm mr-2"></span>
                            {{ __('Submitting...') }}
                        </div>
                        <button type="submit" class="btn btn-primary shadow-lg hover:shadow-xl transition-all duration-200" wire:loading.attr="disabled">
                            <i class="bi bi-send-fill"></i>
                            {{ __('information.submit') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="lg:col-span-4">
            <div class="bg-base-100 rounded-lg shadow p-6">
                <h3 class="text-xl font-bold text-base-content mb-4">{{ __('Information') }}</h3>
                @include('components.information-sidebar')
            </div>
        </div>
    </div>
</div>