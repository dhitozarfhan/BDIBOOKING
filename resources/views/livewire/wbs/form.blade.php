<div class="p-4 sm:p-8 bg-base-100 shadow sm:rounded-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="{{ route('home') }}"><i class="bi bi-house-fill"></i></a></li>
                <li><a href="{{ route('wbs') }}">WBS Reporting</a></li>
                <li>{{ __('Report Form') }}</li>
            </ul>
        </div>
        <h2 class="text-2xl font-bold text-base-content mt-4">
            {{ __('WBS Report Form') }}
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
                                {{ session('kode_register') }}
                            </span>
                        </p>
                        <p class="text-sm mt-2 opacity-90">{{ __('Thank you for your participation in maintaining integrity.') }}</p>
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

                    <!-- Nama Pelapor -->
                    <div class="form-control">
                        <label for="nama_pelapor" class="label">
                            <span class="label-text font-semibold">{{ __('Reporter Name') }} <span class="text-error">*</span></span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="bi bi-person-fill text-base-content/40 group-focus-within:text-primary transition-colors"></i>
                            </div>
                            <input
                                id="nama_pelapor"
                                type="text"
                                class="input input-bordered w-full pl-11 focus:input-primary transition-all duration-200"
                                wire:model.lazy="nama_pelapor"
                                placeholder="{{ __('Enter full name') }}">
                        </div>
                        @error('nama_pelapor')
                        <label class="label">
                            <span class="label-text-alt text-error flex items-center">
                                <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                            </span>
                        </label>
                        @enderror
                    </div>

                    <!-- Nomor Identitas -->
                    <div class="form-control">
                        <label for="nomor_identitas" class="label">
                            <span class="label-text font-semibold">{{ __('Identity Number (KTP/SIM)') }}</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="bi bi-card-heading text-base-content/40 group-focus-within:text-primary transition-colors"></i>
                            </div>
                            <input
                                id="nomor_identitas"
                                type="text"
                                class="input input-bordered w-full pl-11 focus:input-primary transition-all duration-200"
                                wire:model.lazy="nomor_identitas"
                                placeholder="{{ __('Example: 3174012345678901') }}">
                        </div>
                        @error('nomor_identitas')
                        <label class="label">
                            <span class="label-text-alt text-error flex items-center">
                                <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                            </span>
                        </label>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div class="form-control">
                        <label for="alamat" class="label">
                            <span class="label-text font-semibold">{{ __('Address') }}</span>
                        </label>
                        <textarea
                            id="alamat"
                            rows="3"
                            class="textarea textarea-bordered w-full focus:textarea-primary transition-all duration-200 resize-none"
                            wire:model.lazy="alamat"
                            placeholder="{{ __('Enter full address') }}"></textarea>
                        @error('alamat')
                        <label class="label">
                            <span class="label-text-alt text-error flex items-center">
                                <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                            </span>
                        </label>
                        @enderror
                    </div>

                    <!-- Pekerjaan -->
                    <div class="form-control">
                        <label for="pekerjaan" class="label">
                            <span class="label-text font-semibold">{{ __('Occupation') }}</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="bi bi-briefcase-fill text-base-content/40 group-focus-within:text-primary transition-colors"></i>
                            </div>
                            <input
                                id="pekerjaan"
                                type="text"
                                class="input input-bordered w-full pl-11 focus:input-primary transition-all duration-200"
                                wire:model.lazy="pekerjaan"
                                placeholder="{{ __('Example: Civil Servant') }}">
                        </div>
                        @error('pekerjaan')
                        <label class="label">
                            <span class="label-text-alt text-error flex items-center">
                                <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                            </span>
                        </label>
                        @enderror
                    </div>

                    <!-- Telepon -->
                    <div class="form-control">
                        <label for="telepon" class="label">
                            <span class="label-text font-semibold">{{ __('Phone') }}</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="bi bi-telephone-fill text-base-content/40 group-focus-within:text-primary transition-colors"></i>
                            </div>
                            <input
                                id="telepon"
                                type="text"
                                class="input input-bordered w-full pl-11 focus:input-primary transition-all duration-200"
                                wire:model.lazy="telepon"
                                placeholder="{{ __('Example: 081234567890') }}">
                        </div>
                        @error('telepon')
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
                                class="input input-bordered w-full pl-11 focus:input-primary transition-all duration-200"
                                wire:model.lazy="email"
                                placeholder="{{ __('example@domain.com') }}">
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

                <!-- Kolom Kanan - Informasi Laporan -->
                <div class="lg:w-1/2 space-y-6">
                    <div class="flex items-center space-x-3 mb-6 pb-2 border-primary">
                        <h4 class="text-xl font-bold text-base-content">{{ __('Report Information') }}</h4>
                    </div>

                    <!-- Jenis Pelanggaran -->
                    <div class="form-control">
                        <label for="violation_id" class="label">
                            <span class="label-text font-semibold">{{ __('Type of Violation') }}</span>
                        </label>
                        <select
                            id="violation_id"
                            class="select select-bordered w-full focus:select-primary transition-all duration-200"
                            wire:model.lazy="violation_id">
                            <option value="">{{ __('Select a violation type') }}</option>
                            @foreach($violations as $violation)
                                <option value="{{ $violation->id }}">{{ $violation->name }}</option>
                            @endforeach
                        </select>
                        @error('violation_id')
                        <label class="label">
                            <span class="label-text-alt text-error flex items-center">
                                <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                            </span>
                        </label>
                        @enderror
                    </div>

                    <!-- Judul Laporan -->
                    <div class="form-control">
                        <label for="judul_laporan" class="label">
                            <span class="label-text font-semibold">{{ __('Report Title') }} <span class="text-error">*</span></span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="bi bi-card-text text-base-content/40 group-focus-within:text-primary transition-colors"></i>
                            </div>
                            <input
                                id="judul_laporan"
                                type="text"
                                class="input input-bordered w-full pl-11 focus:input-primary transition-all duration-200"
                                wire:model.lazy="judul_laporan"
                                placeholder="{{ __('Enter report title') }}">
                        </div>
                        @error('judul_laporan')
                        <label class="label">
                            <span class="label-text-alt text-error flex items-center">
                                <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                            </span>
                        </label>
                        @enderror
                    </div>

                    <!-- Uraian Laporan -->
                    <div class="form-control">
                        <label for="uraian_laporan" class="label">
                            <span class="label-text font-semibold">{{ __('Report Details') }} <span class="text-error">*</span></span>
                        </label>
                        <textarea
                            id="uraian_laporan"
                            rows="6"
                            class="textarea textarea-bordered w-full focus:textarea-primary transition-all duration-200 resize-none"
                            wire:model.lazy="uraian_laporan"
                            placeholder="{{ __('Describe the reported incident in detail') }}"></textarea>
                        @error('uraian_laporan')
                        <label class="label">
                            <span class="label-text-alt text-error flex items-center">
                                <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                            </span>
                        </label>
                        @enderror
                    </div>

                    <!-- Data Dukung -->
                    <div class="form-control">
                        <label for="data_dukung" class="label">
                            <span class="label-text font-semibold">{{ __('Supporting Data') }}</span>
                        </label>
                        <div class="flex flex-col gap-2">
                            <input
                                id="data_dukung"
                                type="file"
                                class="file-input file-input-bordered w-full max-w-full focus:file-input-primary transition-all duration-200"
                                wire:model="data_dukung">
                            @if($data_dukung)
                                <span class="text-sm text-base-content/80">{{ $data_dukung->getClientOriginalName() }}</span>
                            @endif
                        </div>
                        <label class="label">
                            <span class="label-text-alt text-base-content/80 flex items-center">
                                <i class="bi bi-info-circle-fill mr-1"></i>{{ __('Maximum file size 10MB. Supported formats: PDF, DOC, DOCX, JPG, PNG') }}
                            </span>
                        </label>
                        @error('data_dukung')
                        <label class="label">
                            <span class="label-text-alt text-error flex items-center">
                                <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                            </span>
                        </label>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Tombol Submit -->
            <div class="flex justify-end">
                <button type="submit" class="btn btn-primary px-8 py-3">
                    <i class="bi bi-send mr-2"></i>{{ __('Submit Report') }}
                </button>
            </div>
        </form>
    </div>
</div>