<div class="min-h-screen bg-base-200/30">
    <div class="flex">
        {{-- Sidebar --}}
        @include('layouts.partials.participant-sidebar')

        {{-- Main Content --}}
        <div class="flex-1 min-w-0 p-8 lg:p-10">
            @if (session()->has('success'))
                <div class="alert alert-success mb-6 rounded-xl shadow-sm">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="mb-8">
                <h1 class="text-2xl font-bold text-base-content tracking-tight">Profil</h1>
                <p class="text-sm text-base-content/50 mt-1">Kelola informasi akun Anda.</p>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                {{-- Profile Summary Card --}}
                <div class="lg:col-span-1">
                    <div class="bg-base-100 rounded-2xl border border-base-200/80 shadow-sm p-6 text-center">
                        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center mx-auto ring-4 ring-white shadow-lg">
                            <span class="text-2xl font-bold text-white">{{ strtoupper(substr($name ?? 'P', 0, 1)) }}</span>
                        </div>
                        <h2 class="text-lg font-bold mt-4 text-base-content">{{ $name }}</h2>
                        <p class="text-sm text-base-content/40">{{ $email }}</p>

                        @if($nik)
                            <div class="mt-5 rounded-xl bg-base-200/50 p-3.5">
                                <p class="text-[10px] font-semibold uppercase tracking-wider text-base-content/35">NIK</p>
                                <p class="font-mono text-sm font-bold mt-0.5 text-base-content">{{ $nik }}</p>
                            </div>
                        @endif

                        @if($institution)
                            <div class="mt-2 rounded-xl bg-base-200/50 p-3.5">
                                <p class="text-[10px] font-semibold uppercase tracking-wider text-base-content/35">Instansi</p>
                                <p class="text-sm font-medium mt-0.5 text-base-content/80">{{ $institution }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Edit Form --}}
                <div class="lg:col-span-2">
                    <div class="bg-base-100 rounded-2xl border border-base-200/80 shadow-sm p-6">
                        <h3 class="text-base font-bold text-base-content mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5 text-base-content/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            Edit Profil
                        </h3>

                        <form wire:submit="updateProfile" class="space-y-5">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="form-control">
                                    <label class="label pb-1"><span class="label-text text-xs font-semibold uppercase tracking-wider text-base-content/50">Nama Lengkap</span></label>
                                    <input type="text" wire:model="name" class="input input-bordered rounded-xl w-full focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                                    @error('name') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-control">
                                    <label class="label pb-1"><span class="label-text text-xs font-semibold uppercase tracking-wider text-base-content/50">Email</span></label>
                                    <input type="email" wire:model="email" class="input input-bordered rounded-xl w-full focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                                    @error('email') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-control">
                                    <label class="label pb-1"><span class="label-text text-xs font-semibold uppercase tracking-wider text-base-content/50">Telepon</span></label>
                                    <input type="text" wire:model="phone" class="input input-bordered rounded-xl w-full focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="+62..." />
                                    @error('phone') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-control">
                                    <label class="label pb-1"><span class="label-text text-xs font-semibold uppercase tracking-wider text-base-content/50">Instansi</span></label>
                                    <input type="text" wire:model="institution" class="input input-bordered rounded-xl w-full focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                                    @error('institution') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-control">
                                    <label class="label pb-1"><span class="label-text text-xs font-semibold uppercase tracking-wider text-base-content/50">Tempat Lahir</span></label>
                                    <input type="text" wire:model="birth_place" class="input input-bordered rounded-xl w-full focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                                    @error('birth_place') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-control">
                                    <label class="label pb-1"><span class="label-text text-xs font-semibold uppercase tracking-wider text-base-content/50">Tanggal Lahir</span></label>
                                    <input type="date" wire:model="birth_date" class="input input-bordered rounded-xl w-full focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                                    @error('birth_date') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-control">
                                <label class="label pb-1"><span class="label-text text-xs font-semibold uppercase tracking-wider text-base-content/50">Alamat</span></label>
                                <textarea wire:model="address" class="textarea textarea-bordered rounded-xl w-full focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" rows="3"></textarea>
                                @error('address') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="form-control">
                                    <label class="label pb-1"><span class="label-text text-xs font-semibold uppercase tracking-wider text-base-content/50">Provinsi</span></label>
                                    <select wire:model.live="province_id" class="select select-bordered rounded-xl w-full focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                        <option value="">Pilih Provinsi...</option>
                                        @foreach($provinces as $prov)
                                            <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('province_id') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-control">
                                    <label class="label pb-1"><span class="label-text text-xs font-semibold uppercase tracking-wider text-base-content/50">Kota / Kabupaten</span></label>
                                    <select wire:model="city_id" class="select select-bordered rounded-xl w-full focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                        <option value="">Pilih Kota/Kabupaten...</option>
                                        @foreach($cities as $ct)
                                            <option value="{{ $ct->id }}">{{ ucfirst($ct->type) }} {{ $ct->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('city_id') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="flex justify-end pt-2">
                                <button type="submit" class="btn bg-indigo-500 hover:bg-indigo-600 border-0 text-white rounded-xl gap-2 px-6 shadow-sm hover:shadow-md transition-all">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
