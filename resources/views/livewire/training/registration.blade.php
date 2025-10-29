<div class="container mx-auto px-4 py-8">
    <div wire:loading class="w-full flex justify-center items-center py-10">
        <span class="loading loading-lg loading-spinner text-primary"></span>
        <span class="text-lg font-semibold text-base-content/80 ml-4">Memuat Form Pendaftaran...</span>
    </div>

    <div wire:loading.remove>
        @if($error)
            <div role="alert" class="alert alert-error">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>{{ $error }}</span>
            </div>
        @elseif($diklat)
            <div class="hero bg-base-200 rounded-lg py-10 mb-8">
                <div class="hero-content text-center">
                    <div class="max-w-4xl">
                        <h1 class="text-3xl font-bold">Pendaftaran: {{ $diklat['nama_lengkap'] }}</h1>
                        <p class="py-2">di {{ $diklat['tempat'] }}</p>
                    </div>
                </div>
            </div>

            <div role="alert" class="alert alert-warning mb-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                <div>
                    <h3 class="font-bold">Informasi Penting!</h3>
                    <ul class="list-decimal list-inside text-sm">
                        <li>Masukkan nomor handphone yang aktif sebagai kontak Anda.</li>
                        <li>Nomor KTP/NIK akan digunakan sebagai <b>username</b> untuk login.</li>
                        <li>Tanggal lahir akan digunakan sebagai <b>password</b> dengan format DDMMYY (contoh: 080701).</li>
                    </ul>
                </div>
            </div>

            <form wire:submit.prevent="submit" class="space-y-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    {{-- Left Column --}}
                    <div class="space-y-8">
                        {{-- Biodata Card --}}
                        <div class="card bg-base-100 shadow-xl">
                            <div class="card-body">
                                <h2 class="card-title">Biodata Peserta</h2>
                                <div class="divider my-1"></div>
                                <label class="form-control w-full">
                                    <div class="label"><span class="label-text">Nama Lengkap <span class="text-error">*</span></span></div>
                                    <input type="text" wire:model.lazy="nama" class="input input-bordered w-full" />
                                </label>
                                <label class="form-control w-full">
                                    <div class="label"><span class="label-text">Nomor KTP / NIK <span class="text-error">*</span></span></div>
                                    <input type="text" wire:model.lazy="ktp" class="input input-bordered w-full" />
                                </label>
                                @if(($diklat['scan_ktp'] == 'O' || $diklat['scan_ktp'] == 'Y') && $diklat['kapan_upload_ktp'] == 'initial')
                                <label class="form-control w-full">
                                    <div class="label"><span class="label-text">Scan KTP (JPG)</span></div>
                                    <input type="file" wire:model="scan_ktp" class="file-input file-input-bordered w-full" />
                                </label>
                                @endif
                                <label class="form-control w-full">
                                    <div class="label"><span class="label-text">Tempat Lahir <span class="text-error">*</span></span></div>
                                    <input type="text" wire:model.lazy="tempat_lahir" class="input input-bordered w-full" />
                                </label>
                                <label class="form-control w-full">
                                    <div class="label"><span class="label-text">Tanggal Lahir <span class="text-error">*</span></span></div>
                                    <input type="date" wire:model.lazy="tanggal_lahir" class="input input-bordered w-full" />
                                </label>
                                <label class="form-control w-full">
                                    <div class="label"><span class="label-text">Jenis Kelamin <span class="text-error">*</span></span></div>
                                    <select wire:model.lazy="id_kelamin" class="select select-bordered">
                                        <option value="">-- Pilih --</option>
                                        @foreach($kelamin as $item)
                                            <option value="{{ $item['id_kelamin'] }}">{{ $item['jenis_kelamin'] }}</option>
                                        @endforeach
                                    </select>
                                </label>
                                <label class="form-control w-full">
                                    <div class="label"><span class="label-text">Agama <span class="text-error">*</span></span></div>
                                    <select wire:model.lazy="id_agama" class="select select-bordered">
                                        <option value="">-- Pilih --</option>
                                        @foreach($agama as $item)
                                            <option value="{{ $item['id_agama'] }}">{{ $item['nama'] }}</option>
                                        @endforeach
                                    </select>
                                </label>
                                @if(($diklat['scan_foto'] == 'O' || $diklat['scan_foto'] == 'Y') && $diklat['kapan_upload_foto'] == 'initial')
                                <label class="form-control w-full">
                                    <div class="label"><span class="label-text">Foto Latar Merah (JPG)</span></div>
                                    <input type="file" wire:model="scan_foto" class="file-input file-input-bordered w-full" />
                                </label>
                                @endif
                            </div>
                        </div>

                        {{-- Pendidikan Card --}}
                        <div class="card bg-base-100 shadow-xl">
                            <div class="card-body">
                                <h2 class="card-title">Pendidikan Terakhir</h2>
                                <div class="divider my-1"></div>
                                <label class="form-control w-full">
                                    <div class="label"><span class="label-text">Jenjang <span class="text-error">*</span></span></div>
                                    <select wire:model.lazy="id_pendidikan" class="select select-bordered">
                                        <option value="">-- Pilih --</option>
                                        @foreach($pendidikan as $item)
                                            <option value="{{ $item['id_pendidikan'] }}">{{ $item['nama'] }}</option>
                                        @endforeach
                                    </select>
                                </label>
                                <label class="form-control w-full">
                                    <div class="label"><span class="label-text">Jurusan <span class="text-error">*</span></span></div>
                                    <input type="text" wire:model.lazy="pendidikan_jurusan" class="input input-bordered w-full" />
                                </label>
                                <label class="form-control w-full">
                                    <div class="label"><span class="label-text">Tahun Ijazah <span class="text-error">*</span></span></div>
                                    <input type="text" wire:model.lazy="pendidikan_tamat" class="input input-bordered w-full" placeholder="YYYY"/>
                                </label>
                                @if(($diklat['scan_ijazah'] == 'O' || $diklat['scan_ijazah'] == 'Y') && $diklat['kapan_upload_ijazah'] == 'initial')
                                <label class="form-control w-full">
                                    <div class="label"><span class="label-text">Scan Ijazah (JPG)</span></div>
                                    <input type="file" wire:model="scan_ijazah" class="file-input file-input-bordered w-full" />
                                </label>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="space-y-8">
                        {{-- Alamat Card --}}
                        <div class="card bg-base-100 shadow-xl">
                            <div class="card-body">
                                <h2 class="card-title">Detail Alamat</h2>
                                <div class="divider my-1"></div>
                                <label class="form-control w-full">
                                    <div class="label"><span class="label-text">Provinsi <span class="text-error">*</span></span></div>
                                    <select wire:model.live="selectedProvinsi" class="select select-bordered">
                                        <option value="">-- Pilih Provinsi --</option>
                                        @foreach($provinsi as $item)
                                            <option value="{{ $item['id_provinsi'] }}">{{ $item['provinsi'] }}</option>
                                        @endforeach
                                    </select>
                                </label>
                                {{-- Dependent dropdowns would follow --}}
                                <label class="form-control w-full">
                                    <div class="label"><span class="label-text">Dusun, Nama Jalan, dsb. <span class="text-error">*</span></span></div>
                                    <input type="text" wire:model.lazy="dusun" class="input input-bordered w-full" />
                                </label>
                                <div class="grid grid-cols-2 gap-4">
                                    <label class="form-control w-full">
                                        <div class="label"><span class="label-text">RT</span></div>
                                        <input type="text" wire:model.lazy="rt" class="input input-bordered w-full" />
                                    </label>
                                    <label class="form-control w-full">
                                        <div class="label"><span class="label-text">RW</span></div>
                                        <input type="text" wire:model.lazy="rw" class="input input-bordered w-full" />
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Kontak Card --}}
                        <div class="card bg-base-100 shadow-xl">
                            <div class="card-body">
                                <h2 class="card-title">Kontak</h2>
                                <div class="divider my-1"></div>
                                <label class="form-control w-full">
                                    <div class="label"><span class="label-text">Telepon</span></div>
                                    <input type="tel" wire:model.lazy="telepon" class="input input-bordered w-full" />
                                </label>
                                <label class="form-control w-full">
                                    <div class="label"><span class="label-text">Handphone WhatsApp <span class="text-error">*</span></span></div>
                                    <input type="tel" wire:model.lazy="mobile" class="input input-bordered w-full" />
                                </label>
                                <label class="form-control w-full">
                                    <div class="label"><span class="label-text">Email <span class="text-error">*</span></span></div>
                                    <input type="email" wire:model.lazy="email" class="input input-bordered w-full" />
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-actions justify-center mt-8">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <span wire:loading.remove>Kirim Pendaftaran</span>
                        <span wire:loading class="loading loading-spinner"></span>
                        <span wire:loading>Memproses...</span>
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>
