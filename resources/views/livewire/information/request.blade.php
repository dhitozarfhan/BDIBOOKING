@section('title', __('Public Information Request Form'))

<div class="p-4 sm:p-8 bg-base-100 shadow sm:rounded-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @php
            $breadcrumbs = [
                ['label' => __('Home'), 'url' => route('home')],
                ['label' => __('Public Information Request Form')]
            ];
        @endphp
        @include('livewire.information.partials.breadcrumb', ['items' => $breadcrumbs])
        <h2 class="text-2xl font-bold text-base-content mt-4">
            {{ __('Public Information Request Form') }}
        </h2>

        <br>

        {{-- Alert Messages --}}
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
                        <p class="text-sm mt-2 opacity-90">{{ __('Thank you for your request. We will process it soon.') }}</p>
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
                            {{ __('Please fill out this form to request public information. All fields marked with * are required.') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Form Container --}}
        <form wire:submit.prevent="save">
            <div class="flex flex-col lg:flex-row gap-8 mb-8">
                {{-- Kolom Kiri - Informasi Pemohon --}}
                <div class="lg:w-1/2 space-y-6">
                    <div class="flex items-center space-x-3 mb-6 pb-2 border-primary">
                        <h4 class="text-xl font-bold text-base-content">{{ __('Applicant Information') }}</h4>
                    </div>
                    {{-- Applicant Name --}}
                    <div class="form-control">
                        <label for="reporter_name" class="label">
                            <span class="label-text font-semibold">{{ __('Applicant Name') }} <span class="text-error">*</span></span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0  flex items-center pointer-events-none">
                                <i class="bi bi-person-fill text-base-content/40 group-focus-within:text-primary transition-colors"></i>
                            </div>
                            <input 
                                id="reporter_name" 
                                type="text" 
                                class="input input-bordered w-full pr-4 focus:input-primary transition-all duration-200" 
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

                    {{-- ID Card Number --}}
                    <div class="form-control">
                        <label for="id_card_number" class="label">
                            <span class="label-text font-semibold">{{ __('ID Card Number') }} <span class="text-error">*</span></span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none">
                                <i class="bi bi-card-heading text-base-content/40 group-focus-within:text-primary transition-colors"></i>
                            </div>
                            <input 
                                id="id_card_number" 
                                type="text" 
                                class="input input-bordered w-full pr-4 focus:input-primary transition-all duration-200" 
                                wire:model.lazy="id_card_number"
                                placeholder="{{ __('Enter ID card number') }}">
                        </div>
                        @error('id_card_number') 
                        <label class="label">
                            <span class="label-text-alt text-error flex items-center">
                                <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                            </span>
                        </label>
                        @enderror
                    </div>

                    {{-- Address --}}
                    <div class="form-control">
                        <label for="address" class="label">
                            <span class="label-text font-semibold">{{ __('Address') }} <span class="text-error">*</span></span>
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

                    {{-- Occupation --}}
                    <div class="form-control">
                        <label for="occupation" class="label">
                            <span class="label-text font-semibold">{{ __('Occupation') }}</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none">
                                <i class="bi bi-briefcase-fill text-base-content/40 group-focus-within:text-primary transition-colors"></i>
                            </div>
                            <input 
                                id="occupation" 
                                type="text" 
                                class="input input-bordered w-full pr-4 focus:input-primary transition-all duration-200" 
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

                    {{-- Mobile --}}
                    <div class="form-control">
                        <label for="mobile" class="label">
                            <span class="label-text font-semibold">{{ __('Mobile Number') }} <span class="text-error">*</span></span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none">
                                <i class="bi bi-telephone-fill text-base-content/40 group-focus-within:text-primary transition-colors"></i>
                            </div>
                            <input 
                                id="mobile" 
                                type="text" 
                                class="input input-bordered w-full pr-4 focus:input-primary transition-all duration-200" 
                                wire:model.lazy="mobile"
                                placeholder="{{ __('Enter phone number') }}">
                        </div>
                        @error('mobile') 
                        <label class="label">
                            <span class="label-text-alt text-error flex items-center">
                                <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                            </span>
                        </label>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="form-control">
                        <label for="email" class="label">
                            <span class="label-text font-semibold">{{ __('Email') }}</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none">
                                <i class="bi bi-envelope-fill text-base-content/40 group-focus-within:text-primary transition-colors"></i>
                            </div>
                            <input 
                                id="email" 
                                type="email" 
                                class="input input-bordered w-full pr-4 focus:input-primary transition-all duration-200" 
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

                    {{-- Grab Method --}}
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">{{ __('Information Acquisition Method') }} <span class="text-error">*</span></span>
                        </label>
                        <div class="space-y-2 pl-2">
                            <div class="flex items-center">
                                <input id="grab_see" type="checkbox" wire:model.live="grab_method" value="see" class="checkbox checkbox-primary">
                                <label for="grab_see" class="ml-3 text-base-content cursor-pointer">{{ __('See/View') }}</label>
                            </div>
                            <div class="flex items-center">
                                <input id="grab_read" type="checkbox" wire:model.live="grab_method" value="read" class="checkbox checkbox-primary">
                                <label for="grab_read" class="ml-3 text-base-content cursor-pointer">{{ __('Read') }}</label>
                            </div>
                            <div class="flex items-center">
                                <input id="grab_hear" type="checkbox" wire:model.live="grab_method" value="hear" class="checkbox checkbox-primary">
                                <label for="grab_hear" class="ml-3 text-base-content cursor-pointer">{{ __('Listen/Hear') }}</label>
                            </div>
                            <div class="flex items-center">
                                <input id="grab_write" type="checkbox" wire:model.live="grab_method" value="write" class="checkbox checkbox-primary">
                                <label for="grab_write" class="ml-3 text-base-content cursor-pointer">{{ __('Record/Write') }}</label>
                            </div>
                            <div class="flex items-center">
                                <input id="grab_hardcopy" type="checkbox" wire:model.live="grab_method" value="hardcopy" class="checkbox checkbox-primary">
                                <label for="grab_hardcopy" class="ml-3 text-base-content cursor-pointer">{{ __('Get Hardcopy') }}</label>
                            </div>
                            <div class="flex items-center">
                                <input id="grab_softcopy" type="checkbox" wire:model.live="grab_method" value="softcopy" class="checkbox checkbox-primary">
                                <label for="grab_softcopy" class="ml-3 text-base-content cursor-pointer">{{ __('Get Softcopy') }}</label>
                            </div>
                        </div>
                        @error('grab_method') 
                        <label class="label">
                            <span class="label-text-alt text-error flex items-center">
                                <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                            </span>
                        </label>
                        @enderror
                    </div>

                    {{-- Delivery Method --}}
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">{{ __('Copy Delivery Method') }}</span>
                        </label>
                        <div class="space-y-2 pl-2">
                            <div class="flex items-center">
                                <input id="delivery_direct" type="checkbox" wire:model="delivery_method" value="direct" class="checkbox checkbox-primary">
                                <label for="delivery_direct" class="ml-3 text-base-content cursor-pointer">{{ __('Pick Up Directly') }}</label>
                            </div>
                            <div class="flex items-center">
                                <input id="delivery_courier" type="checkbox" wire:model="delivery_method" value="courier" class="checkbox checkbox-primary">
                                <label for="delivery_courier" class="ml-3 text-base-content cursor-pointer">{{ __('Courier Service') }}</label>
                            </div>
                            <div class="flex items-center">
                                <input id="delivery_post" type="checkbox" wire:model="delivery_method" value="post" class="checkbox checkbox-primary">
                                <label for="delivery_post" class="ml-3 text-base-content cursor-pointer">{{ __('Postal Mail') }}</label>
                            </div>
                            <div class="flex items-center">
                                <input id="delivery_fax" type="checkbox" wire:model="delivery_method" value="fax" class="checkbox checkbox-primary">
                                <label for="delivery_fax" class="ml-3 text-base-content cursor-pointer">{{ __('Fax') }}</label>
                            </div>
                            <div class="flex items-center">
                                <input id="delivery_email" type="checkbox" wire:model="delivery_method" value="email" class="checkbox checkbox-primary">
                                <label for="delivery_email" class="ml-3 text-base-content cursor-pointer">{{ __('Email') }}</label>
                            </div>
                        </div>
                        @error('delivery_method') 
                        <label class="label">
                            <span class="label-text-alt text-error flex items-center">
                                <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                            </span>
                        </label>
                        @enderror
                    </div>
                </div>

                {{-- Kolom Kanan - Detail Permohonan --}}
                <div class="lg:w-1/2 space-y-6">
                    <div class="flex items-center space-x-3 mb-6 pb-2 border-secondary">
                        <h4 class="text-xl font-bold text-base-content">{{ __('Request Details') }}</h4>
                    </div>
                    {{-- Request Content --}}
                    <div class="form-control">
                        <label for="report_title" class="label">
                            <span class="label-text font-semibold">{{ __('Information Requested') }} <span class="text-error">*</span></span>
                        </label>
                        <textarea 
                            id="report_title" 
                            rows="10"
                            class="textarea textarea-bordered w-full px-4 py-3 focus:textarea-secondary transition-all duration-200 resize-none" 
                            wire:model.lazy="report_title"
                            placeholder="{{ __('Describe the information you are requesting...') }}"></textarea>
                        <label class="label">
                            <span class="label-text-alt text-base-content/60">{{ __('Be as specific as possible') }}</span>
                        </label>
                        @error('report_title') 
                        <label class="label">
                            <span class="label-text-alt text-error flex items-center">
                                <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                            </span>
                        </label>
                        @enderror
                    </div>

                    {{-- Purpose --}}
                    <div class="form-control">
                        <label for="used_for" class="label">
                            <span class="label-text font-semibold">{{ __('Purpose of Request') }} <span class="text-error">*</span></span>
                        </label>
                        <textarea 
                            id="used_for" 
                            rows="8"
                            class="textarea textarea-bordered w-full px-4 py-3 focus:textarea-secondary transition-all duration-200 resize-none" 
                            wire:model.lazy="used_for"
                            placeholder="{{ __('Explain how you will use this information...') }}"></textarea>
                        @error('used_for') 
                        <label class="label">
                            <span class="label-text-alt text-error flex items-center">
                                <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                            </span>
                        </label>
                        @enderror
                    </div>

                            <!-- Terms Acceptance -->
                            <div class="form-control">
                                <label class="cursor-pointer flex items-start space-x-2">
                                    <input type="checkbox" wire:model="rule_accepted" class="checkbox checkbox-sm mt-1">
                                    <span class="label-text">{{ __('I have read and agree to the') }} <a href="{{ route('information.provision') }}" target="_blank" class="link link-primary">{{ __('terms and conditions') }}</a> *</span>
                                </label>
                                @error('rule_accepted') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                            </div>



                    {{-- Submit Button --}}
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t-2 border-base-300">
                        <div class="flex items-center text-sm text-base-content/70">
                            <i class="bi bi-shield-lock-fill text-success text-lg mr-2"></i>
                            <span>{{ __('Your information will be kept confidential') }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div wire:loading wire:target="save" class="flex items-center text-sm text-secondary">
                                <span class="loading loading-spinner loading-sm mr-2"></span>
                                {{ __('Submitting...') }}
                            </div>
                            <button type="submit" class="btn btn-primary shadow-lg hover:shadow-xl transition-all duration-200" wire:loading.attr="disabled">
                                <i class="bi bi-send-fill"></i>
                                {{ __('Submit Request') }}
                            </button>
                            <a href="{{ route('information.request.status') }}" class="btn btn-outline btn-primary shadow-lg hover:shadow-xl transition-all duration-200">
                                <i class="bi bi-search"></i> {{ __('Check Status') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        {{-- Additional Info --}}
        <div class="mt-6 text-center text-sm text-base-content/60">
            <p>{{ __('Need help? Contact us at') }} <a href="mailto:bdiyogyakarta@kemenperin.go.id" class="link link-primary font-medium">bdiyogyakarta@kemenperin.go.id</a></p>
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
