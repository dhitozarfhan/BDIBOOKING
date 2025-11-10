<div class="p-4 sm:p-8 bg-base-100 shadow sm:rounded-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="bg-base-100 rounded-lg shadow p-6">
                    <div class="mb-4 pb-4 border-b border-base-300">
                        <h2 class="text-2xl font-bold text-base-content">{{ __('information.public_question') }}</h2>
                    </div>
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
                    <form wire:submit.prevent="save" class="my-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="form-control">
                                    <label for="subject" class="label">
                                        <span class="label-text font-medium">{{ __('information.subject') }}</span>
                                    </label>
                                    <input type="text" id="subject" wire:model="subject" class="input input-bordered w-full">
                                    @error('subject') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-control">
                                    <label for="content" class="label">
                                        <span class="label-text font-medium">{{ __('information.message') }}</span>
                                    </label>
                                    <textarea id="content" wire:model="content" rows="4" class="textarea textarea-bordered w-full"></textarea>
                                    @error('content') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div class="form-control">
                                    <label for="name" class="label">
                                        <span class="label-text font-medium">{{ __('information.name') }}</span>
                                    </label>
                                    <input type="text" id="name" wire:model="name" class="input input-bordered w-full">
                                    @error('name') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-control">
                                    <label for="mobile" class="label">
                                        <span class="label-text font-medium">{{ __('information.mobile') }}</span>
                                    </label>
                                    <input type="text" id="mobile" wire:model="mobile" class="input input-bordered w-full">
                                    @error('mobile') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-control">
                                    <label for="email" class="label">
                                        <span class="label-text font-medium">{{ __('information.email') }}</span>
                                    </label>
                                    <input type="email" id="email" wire:model="email" class="input input-bordered w-full">
                                    @error('email') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                                </div>
                                <button type="submit" class="btn btn-primary w-full">{{ __('information.submit') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="lg:col-span-1">
                <div class="bg-base-100 rounded-lg shadow p-6">
                    <h3 class="text-xl font-bold text-base-content mb-4">{{ __('Information') }}</h3>
                    @include('components.information-sidebar')
                </div>
            </div>
        </div>
    </div>
</div>
