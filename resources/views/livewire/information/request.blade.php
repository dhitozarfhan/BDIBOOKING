<div class="container max-w-7xl flex mx-auto px-4 mt-10">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        <div class="lg:col-span-8">
            <div class="w-full mb-16">
                <h1 class="text-4xl font-bold text-base-content mb-6">{{ __('Public Information Request Form') }}</h1>
                
                @if (session()->has('message'))
                    <div class="alert alert-success shadow-lg mb-6" role="alert">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>{{ session('message') }}</span>
                    </div>
                @else
                    <div class="alert alert-info shadow-lg mb-6" role="alert">
                        <i class="bi bi-info-circle-fill"></i>
                        <p class="m-0">{{ __('Please fill out the form below to request public information. All fields marked with * are required.') }}</p>
                    </div>
                @endif

                <form wire:submit.prevent="save" class="my-5 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <!-- Name -->
                            <div class="form-control">
                                <label for="name" class="label">
                                    <span class="label-text font-medium"><i class="bi bi-person-fill mr-2"></i>{{ __('Applicant Name') }} *</span>
                                </label>
                                <input type="text" id="name" wire:model="name" class="input input-bordered w-full" placeholder="{{ __('Full Name') }}">
                                @error('name') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- ID Card Number -->
                            <div class="form-control">
                                <label for="id_card_number" class="label">
                                    <span class="label-text font-medium"><i class="bi bi-credit-card-fill mr-2"></i>{{ __('ID Card Number') }} *</span>
                                </label>
                                <input type="text" id="id_card_number" wire:model="id_card_number" class="input input-bordered w-full" placeholder="{{ __('ID Card Number') }}">
                                @error('id_card_number') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Address -->
                            <div class="form-control">
                                <label for="address" class="label">
                                    <span class="label-text font-medium"><i class="bi bi-geo-alt-fill mr-2"></i>{{ __('Address') }} *</span>
                                </label>
                                <textarea id="address" wire:model="address" rows="3" class="textarea textarea-bordered w-full" placeholder="{{ __('Full Address') }}"></textarea>
                                @error('address') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Occupation -->
                            <div class="form-control">
                                <label for="occupation" class="label">
                                    <span class="label-text font-medium"><i class="bi bi-briefcase-fill mr-2"></i>{{ __('Occupation') }} *</span>
                                </label>
                                <input type="text" id="occupation" wire:model="occupation" class="input input-bordered w-full" placeholder="{{ __('Occupation') }}">
                                @error('occupation') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Mobile -->
                            <div class="form-control">
                                <label for="mobile" class="label">
                                    <span class="label-text font-medium"><i class="bi bi-telephone-fill mr-2"></i>{{ __('Mobile Number') }} *</span>
                                </label>
                                <input type="text" id="mobile" wire:model="mobile" class="input input-bordered w-full" placeholder="{{ __('Mobile Number') }}">
                                @error('mobile') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Email -->
                            <div class="form-control">
                                <label for="email" class="label">
                                    <span class="label-text font-medium"><i class="bi bi-envelope-fill mr-2"></i>{{ __('Email') }} *</span>
                                </label>
                                <input type="email" id="email" wire:model="email" class="input input-bordered w-full" placeholder="{{ __('Email Address') }}">
                                @error('email') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Grab Method -->
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-medium">{{ __('Information Acquisition Method') }} *</span>
                                </label>
                                <div class="space-y-2">
                                    <label class="cursor-pointer flex items-center space-x-2">
                                        <input type="checkbox" wire:model.live="grab_method" value="see" class="checkbox checkbox-sm">
                                        <span class="label-text">{{ __('See/View') }}</span>
                                    </label>
                                    <label class="cursor-pointer flex items-center space-x-2">
                                        <input type="checkbox" wire:model.live="grab_method" value="read" class="checkbox checkbox-sm">
                                        <span class="label-text">{{ __('Read') }}</span>
                                    </label>
                                    <label class="cursor-pointer flex items-center space-x-2">
                                        <input type="checkbox" wire:model.live="grab_method" value="hear" class="checkbox checkbox-sm">
                                        <span class="label-text">{{ __('Listen/Hear') }}</span>
                                    </label>
                                    <label class="cursor-pointer flex items-center space-x-2">
                                        <input type="checkbox" wire:model.live="grab_method" value="write" class="checkbox checkbox-sm">
                                        <span class="label-text">{{ __('Record/Write') }}</span>
                                    </label>
                                    <label class="cursor-pointer flex items-center space-x-2">
                                        <input type="checkbox" wire:model.live="grab_method" value="hardcopy" class="checkbox checkbox-sm">
                                        <span class="label-text">{{ __('Get Hardcopy') }}</span>
                                    </label>
                                    <label class="cursor-pointer flex items-center space-x-2">
                                        <input type="checkbox" wire:model.live="grab_method" value="softcopy" class="checkbox checkbox-sm">
                                        <span class="label-text">{{ __('Get Softcopy') }}</span>
                                    </label>
                                </div>
                                @error('grab_method') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Delivery Method (conditional) -->
                            @if($show_delivery_method)
                                <div class="form-control" wire:transition>
                                    <label class="label">
                                        <span class="label-text font-medium">{{ __('Delivery Method') }} *</span>
                                    </label>
                                    <div class="space-y-2">
                                        <label class="cursor-pointer flex items-center space-x-2">
                                            <input type="checkbox" wire:model="delivery_method" value="direct" class="checkbox checkbox-sm">
                                            <span class="label-text">{{ __('Pick Up Directly') }}</span>
                                        </label>
                                        <label class="cursor-pointer flex items-center space-x-2">
                                            <input type="checkbox" wire:model="delivery_method" value="courier" class="checkbox checkbox-sm">
                                            <span class="label-text">{{ __('Courier Service') }}</span>
                                        </label>
                                        <label class="cursor-pointer flex items-center space-x-2">
                                            <input type="checkbox" wire:model="delivery_method" value="post" class="checkbox checkbox-sm">
                                            <span class="label-text">{{ __('Postal Mail') }}</span>
                                        </label>
                                        <label class="cursor-pointer flex items-center space-x-2">
                                            <input type="checkbox" wire:model="delivery_method" value="fax" class="checkbox checkbox-sm">
                                            <span class="label-text">{{ __('Fax') }}</span>
                                        </label>
                                        <label class="cursor-pointer flex items-center space-x-2">
                                            <input type="checkbox" wire:model="delivery_method" value="email" class="checkbox checkbox-sm">
                                            <span class="label-text">{{ __('Email') }}</span>
                                        </label>
                                    </div>
                                    @error('delivery_method') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                                </div>
                            @endif
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <!-- Request Content -->
                            <div class="form-control">
                                <label for="content" class="label">
                                    <span class="label-text font-medium">{{ __('Information Requested') }} *</span>
                                </label>
                                <textarea id="content" wire:model="content" rows="8" class="textarea textarea-bordered w-full" placeholder="{{ __('Describe the information you are requesting...') }}"></textarea>
                                @error('content') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Used For -->
                            <div class="form-control">
                                <label for="used_for" class="label">
                                    <span class="label-text font-medium">{{ __('Purpose of Request') }} *</span>
                                </label>
                                <textarea id="used_for" wire:model="used_for" rows="6" class="textarea textarea-bordered w-full" placeholder="{{ __('Explain how you will use this information...') }}"></textarea>
                                @error('used_for') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Terms Acceptance -->
                            <div class="form-control">
                                <label class="cursor-pointer flex items-start space-x-2">
                                    <input type="checkbox" wire:model="rule_accepted" class="checkbox checkbox-sm mt-1">
                                    <span class="label-text">{{ __('I have read and agree to the') }} <a href="{{ route('information.provision') }}" target="_blank" class="link link-primary">{{ __('terms and conditions') }}</a> *</span>
                                </label>
                                @error('rule_accepted') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Captcha -->
                            <div class="grid grid-cols-2 gap-4">
                                <div class="form-control">
                                    <label for="captcha" class="label">
                                        <span class="label-text font-medium"><i class="bi bi-shield-check mr-2"></i>{{ __('Enter Captcha') }} *</span>
                                    </label>
                                    <input type="text" id="captcha" wire:model="captcha" class="input input-bordered w-full" placeholder="{{ __('Captcha Code') }}">
                                    @error('captcha') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <a wire:click="refreshCaptcha" class="label-text link link-primary cursor-pointer">
                                            <i class="bi bi-arrow-clockwise mr-1"></i>{{ __('Refresh Captcha') }}
                                        </a>
                                    </label>
                                    <div class="bg-base-200 rounded-lg p-4 text-center border-2 border-base-300">
                                        <span class="text-2xl font-bold tracking-widest select-none" style="font-family: monospace; letter-spacing: 0.3em;">
                                            {{ session('captcha_value', $captcha_value) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-end items-center gap-4 pt-4">
                                <div wire:loading wire:target="save" class="flex items-center text-sm text-primary">
                                    <span class="loading loading-spinner loading-sm mr-2"></span>
                                    {{ __('Submitting...') }}
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg shadow-lg hover:shadow-xl transition-all duration-200" wire:loading.attr="disabled">
                                    <i class="bi bi-send-fill"></i>
                                    {{ __('Submit Request') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="lg:col-span-4">
            <div class="bg-base-100 rounded-lg shadow p-6 sticky top-4">
                @include('components.information-sidebar')
            </div>
        </div>
    </div>
</div>
