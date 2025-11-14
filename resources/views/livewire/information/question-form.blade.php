<div class="container max-w-7xl flex mx-auto px-4 mt-10">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        <div class="lg:col-span-8">
            <div class="w-full mb-16">
                <h1 class="text-4xl font-bold text-base-content">{{ __('information.public_question') }}</h1>
                <br>
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
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Subject -->
                        <div class="form-control">
                            <label for="subject" class="label"><span class="label-text font-medium">{{ __('information.subject') }}</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <i class="bi bi-chat-left-text-fill text-gray-400"></i>
                                </span>
                                <input type="text" id="subject" wire:model="subject" class="input input-bordered w-full pl-12" placeholder="{{ __('Subject of your question') }}">
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
                                <input type="text" id="name" wire:model="name" class="input input-bordered w-full pl-12" placeholder="{{ __('Your full name') }}">
                            </div>
                            @error('name') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Identity Number -->
                        <div class="form-control">
                            <label for="identity_number" class="label"><span class="label-text font-medium">{{ __('information.identity_number') }}</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <i class="bi bi-credit-card-fill text-gray-400"></i>
                                </span>
                                <input type="text" id="identity_number" wire:model="identity_number" class="input input-bordered w-full pl-12" placeholder="{{ __('Your identity number') }}">
                            </div>
                            @error('identity_number') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Identity Card Attachment -->
                        <div class="form-control">
                            <label for="identity_card_attachment" class="label">
                                <span class="label-text font-semibold">{{ __('information.identity_card_attachment') }}</span>
                            </label>
                            <div class="relative">
                                @if ($identity_card_attachment)
                                    <div class="flex items-center justify-center w-full h-32 border-2 border-dashed border-success rounded-xl bg-base-200/50 overflow-hidden">
                                        @if($identity_card_attachment instanceof \Illuminate\Http\UploadedFile && $identity_card_attachment->guessExtension() && in_array($identity_card_attachment->guessExtension(), ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']))
                                            <div class="relative w-full h-full flex items-center justify-center">
                                                <img src="{{ $identity_card_attachment->temporaryUrl() }}" alt="{{ __('ID Card Preview') }}" class="w-full" style="aspect-ratio: 1/2; object-fit: contain;">
                                                <button wire:click="$set('identity_card_attachment', null)" type="button" class="absolute top-2 right-2 btn btn-xs btn-circle btn-error">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </div>
                                        @else
                                            <div class="text-center p-4">
                                                <i class="bi bi-file-earmark-check-fill text-4xl text-success mb-2"></i>
                                                <p class="text-sm font-semibold text-base-content truncate" title="{{ $identity_card_attachment->getClientOriginalName() }}">{{ $identity_card_attachment->getClientOriginalName() }}</p>
                                                <p class="text-xs text-base-content/60">({{ round($identity_card_attachment->getSize() / 1024) }} KB)</p>
                                                <button wire:click="$set('identity_card_attachment', null)" type="button" class="btn btn-xs btn-ghost text-error mt-2">
                                                    <i class="bi bi-x-lg"></i> {{ __('Remove') }}
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="flex items-center justify-center w-full">
                                        <label for="identity_card_attachment" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-base-300 rounded-xl cursor-pointer bg-base-200/50 hover:bg-base-200 transition-all duration-200">
                                            <div wire:loading.remove wire:target="identity_card_attachment" class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <i class="bi bi-cloud-arrow-up-fill text-4xl text-base-content/40 mb-2"></i>
                                                <p class="mb-1 text-sm text-base-content/80"><span class="font-semibold">{{ __('Click to upload') }}</span> {{ __('or drag & drop') }}</p>
                                            </div>
                                            <div wire:loading wire:target="identity_card_attachment" class="w-full h-full flex flex-col items-center justify-center">
                                                <span class="loading loading-spinner loading-lg text-primary"></span>
                                                <p class="mt-2 text-sm text-primary">{{ __('Uploading...') }}</p>
                                            </div>
                                            <input id="identity_card_attachment" type="file" class="hidden" wire:model="identity_card_attachment" accept="image/*">
                                        </label>
                                    </div>
                                @endif
                            </div>
                            <div class="px-4">
                                <span class="text-xs text-base-content/60">{{ __('Image (Max 2MB)') }}</span>
                            </div>
                            @error('identity_card_attachment') 
                            <label class="label">
                                <span class="label-text-alt text-error flex items-center">
                                    <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                                </span>
                            </label>
                            @enderror
                        </div>

                        <!-- Mobile -->
                        <div class="form-control">
                            <label for="mobile" class="label"><span class="label-text font-medium">{{ __('information.mobile') }}</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <i class="bi bi-telephone-fill text-gray-400"></i>
                                </span>
                                <input type="text" id="mobile" wire:model="mobile" class="input input-bordered w-full pl-12" placeholder="{{ __('e.g., 08123456789') }}">
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
                                <input type="email" id="email" wire:model="email" class="input input-bordered w-full pl-12" placeholder="{{ __('your.email@example.com') }}">
                            </div>
                            @error('email') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Message -->
                    <div class="form-control">
                        <label for="content" class="label"><span class="label-text font-medium">{{ __('information.message') }}</span></label>
                        <textarea id="content" wire:model="content" rows="6" class="textarea textarea-bordered w-full px-4 py-3" placeholder="{{ __('Write your question or message here...') }}"></textarea>
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
                @include('components.information-sidebar')
            </div>
        </div>
    </div>
</div>