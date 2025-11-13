<div class="p-4 sm:p-8 bg-base-100 shadow sm:rounded-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @php
            $breadcrumbs = [
                ['label' => __('Beranda'), 'url' => route('home')],
                ['label' => __('Gratification Reporting'), 'url' => route('gratification')],
                ['label' => __('Report Form')]
            ];
        @endphp
        @include('livewire.gratification.partials.breadcrumb', ['items' => $breadcrumbs])
        <h2 class="text-2xl font-bold text-base-content mt-4">
            {{ __('Gratification Report Form') }}
        </h2>

        <br>

        <!-- Alert Messages -->
        @if (session()->has('message'))
            <div class="mb-6 alert alert-success shadow-lg animate-fade-in">
                <div class="flex items-start w-full">
                    <div class="flex-shrink-0">
                        <i class="bi bi-check-circle-fill text-2xl"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="font-bold text-lg">{{ __('Success!') }}</h3>
                        <p class="mt-1">
                            {{ session('message') }} 
                            <span class="badge badge-success badge-lg ml-2 font-semibold">
                                {{ session('registration_code') }}
                            </span>
                        </p>
                        <p class="text-sm mt-2 opacity-90">{{ __('Thank you for your participation in maintaining integrity.') }}</p>
                    </div>
                </div>
            </div>
        @else
            <div class="mb-6 alert alert-info shadow-lg">
                <div class="flex items-start w-full">
                    <div class="flex-shrink-0">
                        <i class="bi bi-info-circle-fill text-2xl"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <h4 class="font-bold text-base mb-2">{{ __('Important Information') }}</h4>
                        <p class="text-sm leading-relaxed">
                            {{ __('gratification.form.info_text') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form Container -->
        <form wire:submit.prevent="save">
            <div class="flex flex-col lg:flex-row gap-8 mb-8">
                <!-- Kolom Kiri - Informasi Pelapor -->
                <div class="lg:w-1/2 space-y-6">
                    <div class="flex items-center space-x-3 mb-6 pb-2 border-primary">
                        <h4 class="text-xl font-bold text-base-content">{{ __('Reporter Information') }}</h4>
                    </div>

                    <!-- Reporter Name -->
                    <div class="form-control">
                        <label for="reporter_name" class="label">
                            <span class="label-text font-semibold">{{ __('Reporter Name') }} <span class="text-error">*</span></span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="bi bi-person-fill text-base-content/40 group-focus-within:text-primary transition-colors"></i>
                            </div>
                            <input 
                                id="reporter_name" 
                                type="text" 
                                class="input input-bordered w-full pl-12 focus:input-primary transition-all duration-200" 
                                wire:model.lazy="reporter_name"
                                placeholder="{{ __('Enter full name') }}">
                        </div>
                        @error('reporter_name') 
                        <label class="label">
                            <span class="label-text-alt text-error flex items-center">
                                <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                            </span>
                        </label>
                        @enderror
                    </div>

                    <!-- Identity Number -->
                    <div class="form-control">
                        <label for="identity_number" class="label">
                            <span class="label-text font-semibold">{{ __('Identity Number (KTP/SIM)') }}</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="bi bi-card-heading text-base-content/40 group-focus-within:text-primary transition-colors"></i>
                            </div>
                            <input 
                                id="identity_number" 
                                type="text" 
                                class="input input-bordered w-full pl-12 focus:input-primary transition-all duration-200" 
                                wire:model.lazy="identity_number"
                                placeholder="{{ __('Enter identity number') }}">
                        </div>
                        @error('identity_number') 
                        <label class="label">
                            <span class="label-text-alt text-error flex items-center">
                                <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                            </span>
                        </label>
                        @enderror
                    </div>

                    <!-- Identity Card Attachment -->
                    <div class="form-control">
                        <label for="identity_card_attachment" class="label">
                            <span class="label-text font-semibold">{{ __('ID Card Scan') }}</span>
                        </label>
                        <div class="relative">
                            @if ($identity_card_attachment)
                                <div class="flex items-center justify-center w-full h-32 border-2 border-dashed border-success rounded-xl bg-base-200/50 overflow-hidden">
                                    @if($identity_card_attachment instanceof \Illuminate\Http\UploadedFile && $identity_card_attachment->guessExtension() && in_array($identity_card_attachment->guessExtension(), ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']))
                                        <div class="relative w-full h-full flex items-center justify-center">
                                            <img src="{{ $identity_card_attachment->temporaryUrl() }}" alt="{{ __('ID Card Preview') }}" class="w-full" style="aspect-ratio: 2/1; object-fit: contain;">
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
                                            <p class="text-xs text-base-content/60">{{ __('Image (JPG, PNG, etc. Max 2MB)') }}</p>
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
                        @error('identity_card_attachment') 
                        <label class="label">
                            <span class="label-text-alt text-error flex items-center">
                                <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                            </span>
                        </label>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="form-control">
                        <label for="address" class="label">
                            <span class="label-text font-semibold">{{ __('Address') }}</span>
                        </label>
                        <textarea 
                            id="address" 
                            rows="3"
                            class="textarea textarea-bordered w-full focus:textarea-primary transition-all duration-200 resize-none" 
                            wire:model.lazy="address"
                            placeholder="{{ __('Enter full address') }}"></textarea>
                        @error('address') 
                        <label class="label">
                            <span class="label-text-alt text-error flex items-center">
                                <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                            </span>
                        </label>
                        @enderror
                    </div>

                    <!-- Occupation -->
                    <div class="form-control">
                        <label for="occupation" class="label">
                            <span class="label-text font-semibold">{{ __('Occupation') }}</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="bi bi-briefcase-fill text-base-content/40 group-focus-within:text-primary transition-colors"></i>
                            </div>
                            <input 
                                id="occupation" 
                                type="text" 
                                class="input input-bordered w-full pl-12 focus:input-primary transition-all duration-200" 
                                wire:model.lazy="occupation"
                                placeholder="{{ __('Enter occupation') }}">
                        </div>
                        @error('occupation') 
                        <label class="label">
                            <span class="label-text-alt text-error flex items-center">
                                <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                            </span>
                        </label>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div class="form-control">
                        <label for="phone" class="label">
                            <span class="label-text font-semibold">{{ __('Phone') }}</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="bi bi-telephone-fill text-base-content/40 group-focus-within:text-primary transition-colors"></i>
                            </div>
                            <input 
                                id="phone" 
                                type="text" 
                                class="input input-bordered w-full pl-12 focus:input-primary transition-all duration-200" 
                                wire:model.lazy="phone"
                                placeholder="{{ __('Enter phone number') }}">
                        </div>
                        @error('phone') 
                        <label class="label">
                            <span class="label-text-alt text-error flex items-center">
                                <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                            </span>
                        </label>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="form-control">
                        <label for="email" class="label">
                            <span class="label-text font-semibold">{{ __('Email') }}</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="bi bi-envelope-fill text-base-content/40 group-focus-within:text-primary transition-colors"></i>
                            </div>
                            <input 
                                id="email" 
                                type="email" 
                                class="input input-bordered w-full pl-12 focus:input-primary transition-all duration-200" 
                                wire:model.lazy="email"
                                placeholder="{{ __('Enter email address') }}">
                        </div>
                        @error('email') 
                        <label class="label">
                            <span class="label-text-alt text-error flex items-center">
                                <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                            </span>
                        </label>
                        @enderror
                    </div>
                </div>

                <!-- Kolom Kanan - Detail Laporan -->
                <div class="lg:w-1/2 space-y-6">
                    <div class="flex items-center space-x-3 mb-6 pb-2 border-secondary">
                        <h4 class="text-xl font-bold text-base-content">{{ __('Report Details') }}</h4>
                    </div>

                    <!-- Report Title -->
                    <div class="form-control">
                        <label for="report_title" class="label">
                            <span class="label-text font-semibold">{{ __('Report Title') }} <span class="text-error">*</span></span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="bi bi-chat-left-text-fill text-base-content/40 group-focus-within:text-secondary transition-colors"></i>
                            </div>
                            <input 
                                id="report_title" 
                                type="text" 
                                class="input input-bordered w-full pl-12 focus:input-secondary transition-all duration-200" 
                                wire:model.lazy="report_title"
                                placeholder="{{ __('Brief summary of your report') }}">
                        </div>
                        @error('report_title') 
                        <label class="label">
                            <span class="label-text-alt text-error flex items-center">
                                <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                            </span>
                        </label>
                        @enderror
                    </div>

                    <!-- Report Description -->
                    <div class="form-control">
                        <label for="report_description" class="label">
                            <span class="label-text font-semibold">{{ __('Report Description') }} <span class="text-error">*</span></span>
                        </label>
                        <textarea 
                            id="report_description" 
                            rows="10"
                            class="textarea textarea-bordered w-full focus:textarea-secondary transition-all duration-200 resize-none" 
                            wire:model.lazy="report_description"
                            placeholder="{{ __('gratification.form.description_placeholder') }}"></textarea>
                        <label class="label">
                            <span class="label-text-alt text-base-content/60">{{ __('Include as much clear information as possible to facilitate the investigation process') }}</span>
                        </label>
                        @error('report_description') 
                        <label class="label">
                            <span class="label-text-alt text-error flex items-center">
                                <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                            </span>
                        </label>
                        @enderror
                    </div>

                    <!-- Attachment -->
                    <div class="form-control">
                        <label for="attachment" class="label">
                            <span class="label-text font-semibold">{{ __('Supporting Data') }}</span>
                        </label>
                        <div class="relative">
                            @if ($attachment)
                                <div class="flex items-center justify-center w-full h-32 border-2 border-dashed border-success rounded-xl bg-base-200/50">
                                    <div class="text-center p-4">
                                        <i class="bi bi-file-earmark-check-fill text-4xl text-success mb-2"></i>
                                        <p class="text-sm font-semibold text-base-content truncate" title="{{ $attachment->getClientOriginalName() }}">{{ $attachment->getClientOriginalName() }}</p>
                                        <p class="text-xs text-base-content/60">({{ round($attachment->getSize() / 1024) }} KB)</p>
                                        <button wire:click="$set('attachment', null)" type="button" class="btn btn-xs btn-ghost text-error mt-2">
                                            <i class="bi bi-x-lg"></i> {{ __('Remove') }}
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center justify-center w-full">
                                    <label for="attachment" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-base-300 rounded-xl cursor-pointer bg-base-200/50 hover:bg-base-200 transition-all duration-200">
                                        <div wire:loading.remove wire:target="attachment" class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <i class="bi bi-cloud-arrow-up-fill text-4xl text-base-content/40 mb-2"></i>
                                            <p class="mb-1 text-sm text-base-content/80"><span class="font-semibold">{{ __('Click to upload') }}</span> {{ __('or drag & drop') }}</p>
                                            <p class="text-xs text-base-content/60">{{ __('DOC, PDF, ZIP (Max 1MB)') }}</p>
                                        </div>
                                        <div wire:loading wire:target="attachment" class="w-full h-full flex flex-col items-center justify-center">
                                            <span class="loading loading-spinner loading-lg text-primary"></span>
                                            <p class="mt-2 text-sm text-primary">{{ __('Uploading...') }}</p>
                                        </div>
                                        <input id="attachment" type="file" class="hidden" wire:model="attachment" accept=".doc,.docx,.pdf,.zip">
                                    </label>
                                </div>
                            @endif
                        </div>
                        @error('attachment') 
                        <label class="label">
                            <span class="label-text-alt text-error flex items-center">
                                <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                            </span>
                        </label>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Security Notice & Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t-2 border-base-300">
                <div class="flex items-center text-sm text-base-content/70">
                    <i class="bi bi-shield-lock-fill text-success text-lg mr-2"></i>
                    <span>{{ __('Your report will be kept confidential') }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <div wire:loading wire:target="save" class="flex items-center text-sm text-secondary">
                        <span class="loading loading-spinner loading-sm mr-2"></span>
                        {{ __('Saving report...') }}
                    </div>
                    <a href="{{ route('gratification') }}" class="btn btn-ghost btn-outline" wire:navigate>
                        {{ __('Back') }}
                    </a>
                    <button type="submit" class="btn btn-primary shadow-lg hover:shadow-xl transition-all duration-200">
                        {{ __('Send Report') }}
                    </button>
                </div>
            </div>
        </form>

        <!-- Additional Info -->
        <div class="mt-6 text-center text-sm text-base-content/60">
            <p>{{ __('Need help? Contact us at') }} <a href="mailto:support@bdiyogyakarta.kemenperin.go.id" class="link link-primary font-medium">support@bdiyogyakarta.kemenperin.go.id</a></p>
        </div>
    </div>
</div>

@push('scripts')
<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in {
        animation: fade-in 0.5s ease-out;
    }

    /* Custom focus styles untuk dark mode */
    .input:focus, .textarea:focus, .select:focus {
        outline: 2px solid hsl(var(--p));
        outline-offset: 2px;
    }

    /* Smooth transitions untuk theme switching */
    * {
        transition-property: background-color, border-color, color, fill, stroke;
        transition-duration: 200ms;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Override transition untuk form inputs */
    .input, .textarea, .select, .btn {
        transition-property: background-color, border-color, color, box-shadow, transform;
    }
</style>
@endpush