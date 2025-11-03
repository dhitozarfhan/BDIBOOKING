<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">
    <div wire:loading.flex wire:target="submit" class="w-full flex flex-col items-center py-10 gap-4">
        <span class="loading loading-lg loading-spinner text-primary"></span>
        <span class="text-lg font-semibold text-base-content/80">Memuat formulir pendaftaran...</span>
    </div>

    <div wire:loading.remove wire:target="submit">
        @if($error)
            <div role="alert" class="alert alert-error shadow-lg mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>{{ $error }}</span>
            </div>
        @elseif($diklat)
            <div class="hero rounded-2xl bg-gradient-to-br from-primary/10 via-secondary/10 to-primary/5 py-10 shadow-lg border border-base-200">
                <div class="hero-content flex-col lg:flex-row lg:items-center lg:justify-between w-full gap-8">
                    <div class="max-w-3xl space-y-4 text-center lg:text-left">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/20 text-primary text-xs font-semibold uppercase tracking-wide">
                            <svg class="w-3 h-3 fill-current" viewBox="0 0 16 16" aria-hidden="true">
                                <path d="M12 4H9.5l-.7-1.4A1 1 0 0 0 7.9 2h-2a1 1 0 0 0-.9.6L4.3 4H2a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V5a1 1 0 0 0-1-1ZM7 11l-3-3h2V6h2v2h2l-3 3Z"/>
                            </svg>
                            Form Pendaftaran Peserta
                        </div>
                        <h1 class="text-3xl md:text-4xl font-bold leading-tight text-base-content">
                            {{ $diklat['nama_lengkap'] ?? 'Diklat' }}
                        </h1>
                        <p class="text-base-content/70">
                            Lokasi penyelenggaraan: <span class="font-semibold text-base-content">{{ $diklat['tempat'] ?? '-' }}</span>
                        </p>
                    </div>
                    <div class="card bg-base-100 shadow-md border border-base-200">
                        <div class="card-body px-6 py-5">
                            <span class="text-xs font-semibold uppercase text-base-content/60">Ringkasan</span>
                            <div class="mt-2 space-y-2 text-sm">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-primary" viewBox="0 0 20 20" fill="currentColor"><path d="M6 2a1 1 0 0 1 1 1v1h6V3a1 1 0 1 1 2 0v1h1a2 2 0 0 1 2 2v2H2V6a2 2 0 0 1 2-2h1V3a1 1 0 1 1 2 0v1Zm12 7H2v7a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9Zm-11 3a1 1 0 0 1 2 0v2a1 1 0 1 1-2 0v-2Zm5 0a1 1 0 0 1 2 0v2a1 1 0 1 1-2 0v-2Z"/></svg>
                                    <span>{{ \Carbon\Carbon::parse($diklat['tgl_mulai'])->isoFormat('D MMM YYYY') }} - {{ \Carbon\Carbon::parse($diklat['tgl_selesai'])->isoFormat('D MMM YYYY') }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-primary" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 0 1 9.9 9.9l-4.243 4.243a1 1 0 0 1-1.414 0L5.05 13.95a7 7 0 0 1 0-9.9Zm4.95 5.95a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z" clip-rule="evenodd"/></svg>
                                    <span>{{ $diklat['jenis_diklat'] ?? '-' }}</span>
                                </div>
                            </div>
                            <a href="{{ route('training.detail', ['id_diklat' => $diklat['id_diklat'], 'slug' => Str::slug($diklat['nama'])]) }}" wire:navigate class="btn btn-ghost btn-sm mt-4">
                                Lihat Detail Diklat
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div role="alert" class="alert alert-warning my-8 shadow-sm">
                <div class="flex-1">
                    <h3 class="font-bold">Informasi Penting!</h3>
                    <ul class="list-decimal list-inside text-sm mt-2 space-y-1">
                        <li>Masukkan nomor handphone yang aktif sebagai kontak Anda.</li>
                        <li>Nomor KTP/NIK akan digunakan sebagai <b>username</b> untuk login.</li>
                        <li>Tanggal lahir akan digunakan sebagai <b>password</b> dengan format DDMMYY (contoh: 080701).</li>
                    </ul>
                </div>
            </div>

            <form wire:submit.prevent="submit" class="space-y-10" data-signature-form>
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="space-y-1">
                        <h2 class="text-2xl font-semibold text-base-content">Lengkapi Data Pendaftaran</h2>
                        <p class="text-sm text-base-content/60">Pastikan seluruh data diisi dengan benar sesuai dokumen resmi.</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('training.detail', ['id_diklat' => $diklat['id_diklat'], 'slug' => Str::slug($diklat['nama'])]) }}" wire:navigate class="btn btn-outline btn-sm">
                            Kembali ke Detail Diklat
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    {{-- Left Column --}}
                    <div class="space-y-8">
                        {{-- Biodata Card --}}
                        <div class="card bg-base-100 shadow-xl border border-base-200">
                            <div class="card-body">
                                <div class="space-y-2">
                                    <h2 class="card-title">Biodata Peserta</h2>
                                    <div class="divider mt-2"></div>
                                </div>
                                <div class="flex flex-col gap-6">
                                    <label class="form-control w-full">
                                        <div class="label"><span class="label-text">Nama Lengkap <span class="text-error">*</span></span></div>
                                        <input type="text" wire:model.lazy="nama" @class(['input input-bordered w-full', 'input-error' => $errors->has('nama')]) />
                                        @error('nama') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                                    </label>

                                    @if($diklat['jenis'] == 'sdma')
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <label class="form-control w-full">
                                            <div class="label"><span class="label-text">Titel, Gelar Depan</span></div>
                                            <input type="text" wire:model.lazy="titel" @class(['input input-bordered w-full', 'input-error' => $errors->has('titel')]) />
                                            @error('titel') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                                        </label>
                                        <label class="form-control w-full">
                                            <div class="label"><span class="label-text">Gelar Belakang</span></div>
                                            <input type="text" wire:model.lazy="gelar" @class(['input input-bordered w-full', 'input-error' => $errors->has('gelar')]) />
                                            @error('gelar') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                                        </label>
                                    </div>
                                    @endif

                                    <label class="form-control w-full">
                                        <div class="label"><span class="label-text">Nomor KTP / NIK <span class="text-error">*</span></span></div>
                                        <input type="text" wire:model.lazy="ktp" @class(['input input-bordered w-full', 'input-error' => $errors->has('ktp')]) />
                                        @error('ktp') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                                    </label>
                                    @if(($diklat['scan_ktp'] ?? 'N') != 'N' && ($diklat['kapan_upload_ktp'] ?? '') == 'initial')
                                    <label class="form-control w-full">
                                        <div class="label"><span class="label-text">Scan KTP (JPG) <span class="text-error">{{ $diklat['scan_ktp'] == 'Y' ? '*' : '' }}</span></span></div>
                                        <input type="file" wire:model="scan_ktp" @class(['file-input file-input-bordered w-full', 'input-error' => $errors->has('scan_ktp')]) />
                                        @error('scan_ktp') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                                    </label>
                                    @endif
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <label class="form-control w-full">
                                            <div class="label"><span class="label-text">Tempat Lahir <span class="text-error">*</span></span></div>
                                            <input type="text" wire:model.lazy="tempat_lahir" @class(['input input-bordered w-full', 'input-error' => $errors->has('tempat_lahir')]) />
                                            @error('tempat_lahir') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                                        </label>
                                        <label class="form-control w-full">
                                            <div class="label"><span class="label-text">Tanggal Lahir <span class="text-error">*</span></span></div>
                                            <input type="date" wire:model.lazy="tanggal_lahir" @class(['input input-bordered w-full', 'input-error' => $errors->has('tanggal_lahir')]) />
                                            @error('tanggal_lahir') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                                        </label>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <label class="form-control w-full">
                                            <div class="label"><span class="label-text">Jenis Kelamin <span class="text-error">*</span></span></div>
                                            <select wire:model.lazy="id_kelamin" @class(['select select-bordered', 'select-error' => $errors->has('id_kelamin')])>
                                                <option value="">-- Pilih --</option>
                                                @foreach($kelamin as $item)
                                                    <option value="{{ $item['id_kelamin'] }}">{{ $item['jenis_kelamin'] }}</option>
                                                @endforeach
                                            </select>
                                            @error('id_kelamin') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                                        </label>
                                        <label class="form-control w-full">
                                            <div class="label"><span class="label-text">Agama <span class="text-error">*</span></span></div>
                                            <select wire:model.lazy="id_agama" @class(['select select-bordered', 'select-error' => $errors->has('id_agama')])>
                                                <option value="">-- Pilih --</option>
                                                @foreach($agama as $item)
                                                    <option value="{{ $item['id_agama'] }}">{{ $item['nama'] }}</option>
                                                @endforeach
                                            </select>
                                            @error('id_agama') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                                        </label>
                                    </div>
                                    @if(($diklat['scan_foto'] ?? 'N') != 'N' && ($diklat['kapan_upload_foto'] ?? '') == 'initial')
                                    <label class="form-control w-full">
                                        <div class="label"><span class="label-text">Foto Latar Merah (JPG) <span class="text-error">{{ $diklat['scan_foto'] == 'Y' ? '*' : '' }}</span></span></div>
                                        <input type="file" wire:model="scan_foto" @class(['file-input file-input-bordered w-full', 'input-error' => $errors->has('scan_foto')]) />
                                        @error('scan_foto') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                                    </label>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Pendidikan Card --}}
                        <div class="card bg-base-100 shadow-xl border border-base-200">
                            <div class="card-body">
                                <div class="space-y-2">
                                    <h2 class="card-title">Pendidikan Terakhir</h2>
                                    <div class="divider mt-2"></div>
                                </div>
                                <div class="flex flex-col gap-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <label class="form-control w-full">
                                            <div class="label"><span class="label-text">Jenjang <span class="text-error">*</span></span></div>
                                            <select wire:model.lazy="id_pendidikan" @class(['select select-bordered', 'select-error' => $errors->has('id_pendidikan')])>
                                                <option value="">-- Pilih --</option>
                                                @foreach($pendidikan as $item)
                                                    <option value="{{ $item['id_pendidikan'] }}">{{ $item['nama'] }}</option>
                                                @endforeach
                                            </select>
                                            @error('id_pendidikan') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                                        </label>
                                        <label class="form-control w-full">
                                            <div class="label"><span class="label-text">Tahun Ijazah <span class="text-error">*</span></span></div>
                                            <input type="text" wire:model.lazy="pendidikan_tamat" @class(['input input-bordered w-full', 'input-error' => $errors->has('pendidikan_tamat')]) placeholder="YYYY"/>
                                            @error('pendidikan_tamat') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                                        </label>
                                    </div>
                                    <label class="form-control w-full">
                                        <div class="label"><span class="label-text">Jurusan <span class="text-error">*</span></span></div>
                                        <input type="text" wire:model.lazy="pendidikan_jurusan" @class(['input input-bordered w-full', 'input-error' => $errors->has('pendidikan_jurusan')]) />
                                        @error('pendidikan_jurusan') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                                    </label>
                                    @if(($diklat['scan_ijazah'] ?? 'N') != 'N' && ($diklat['kapan_upload_ijazah'] ?? '') == 'initial')
                                    <label class="form-control w-full">
                                        <div class="label"><span class="label-text">Scan Ijazah (JPG) <span class="text-error">{{ $diklat['scan_ijazah'] == 'Y' ? '*' : '' }}</span></span></div>
                                        <input type="file" wire:model="scan_ijazah" @class(['file-input file-input-bordered w-full', 'input-error' => $errors->has('scan_ijazah')]) />
                                        @error('scan_ijazah') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                                    </label>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if(($diklat['scan_suket_pengalaman_kerja'] ?? 'N') != 'N' && ($diklat['kapan_upload_suket_pengalaman_kerja'] ?? '') == 'initial')
                        <div class="card bg-base-100 shadow-xl border border-base-200">
                            <div class="card-body">
                                <div class="space-y-2">
                                    <h2 class="card-title">Surat Keterangan Pengalaman Kerja</h2>
                                    <div class="divider mt-2"></div>
                                </div>
                                <label class="form-control w-full">
                                    <div class="label"><span class="label-text">Scan Surat Keterangan (JPG) <span class="text-error">{{ $diklat['scan_suket_pengalaman_kerja'] == 'Y' ? '*' : '' }}</span></span></div>
                                    <input type="file" wire:model="scan_suket_pengalaman_kerja" @class(['file-input file-input-bordered w-full', 'input-error' => $errors->has('scan_suket_pengalaman_kerja')]) />
                                    @error('scan_suket_pengalaman_kerja') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                                </label>
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Right Column --}}
                    <div class="space-y-8">
                        @if($diklat['bigdata'] == 'Y')
                        <div class="card bg-base-100 shadow-xl border border-base-200">
                            <div class="card-body">
                                <div class="space-y-2">
                                    <h2 class="card-title">Pekerjaan Sebelumnya</h2>
                                    <div class="divider mt-2"></div>
                                </div>
                                <div class="flex flex-col gap-4">
                                    <p class="text-sm text-warning">Data untuk bagian ini belum tersedia dari API.</p>
                                    <select class="select select-bordered" disabled><option>Pekerjaan Sebelumnya</option></select>
                                    <select class="select select-bordered" disabled><option>Rata-rata Penghasilan</option></select>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="card bg-base-100 shadow-xl border border-base-200">
                            <div class="card-body">
                                <div class="space-y-2">
                                    <h2 class="card-title">Detail Alamat</h2>
                                    <div class="divider mt-2"></div>
                                </div>
                                <div class="flex flex-col gap-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="flex flex-col gap-1">
                                            <label class="form-control w-full">
                                                <div class="label"><span class="label-text">Provinsi <span class="text-error">*</span></span></div>
                                                <select wire:model.live="selectedProvinsi" @class(['select select-bordered', 'select-error' => $errors->has('selectedProvinsi')])>
                                                    <option value="">-- Pilih Provinsi --</option>
                                                    @foreach($provinsi as $item)
                                                        <option value="{{ $item['id_provinsi'] }}">{{ $item['provinsi'] }}</option>
                                                    @endforeach
                                                </select>
                                                @error('selectedProvinsi') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                                            </label>
                                        </div>
                                        <div class="flex flex-col gap-1">
                                            <label class="form-control w-full">
                                                <div class="label"><span class="label-text">Kabupaten / Kota <span class="text-error">*</span></span></div>
                                                <select wire:model.live="selectedKota" @class(['select select-bordered', 'select-error' => $errors->has('selectedKota')]) @disabled(empty($kota))>
                                                    <option value="">{{ empty($kota) ? 'Pilih provinsi terlebih dahulu' : '-- Pilih Kabupaten / Kota --' }}</option>
                                                    @foreach($kota as $item)
                                                        <option value="{{ $item['id_kota'] }}">{{ $item['kota'] }}</option>
                                                    @endforeach
                                                </select>
                                                @error('selectedKota') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                                            </label>
                                            <div wire:loading.flex wire:target="selectedProvinsi" class="label pt-0">
                                                <span class="label-text-alt text-primary">Memuat data kabupaten/kota...</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="flex flex-col gap-1">
                                            <label class="form-control w-full">
                                                <div class="label"><span class="label-text">Kecamatan <span class="text-error">*</span></span></div>
                                                <select wire:model.live="selectedKecamatan" @class(['select select-bordered', 'select-error' => $errors->has('selectedKecamatan')]) @disabled(empty($kecamatan))>
                                                    <option value="">{{ empty($kecamatan) ? 'Pilih kabupaten/kota terlebih dahulu' : '-- Pilih Kecamatan --' }}</option>
                                                    @foreach($kecamatan as $item)
                                                        <option value="{{ $item['id_kecamatan'] }}">{{ $item['kecamatan'] }}</option>
                                                    @endforeach
                                                </select>
                                                @error('selectedKecamatan') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                                            </label>
                                            <div wire:loading.flex wire:target="selectedKota" class="label pt-0">
                                                <span class="label-text-alt text-primary">Memuat data kecamatan...</span>
                                            </div>
                                        </div>
                                        <div class="flex flex-col gap-1">
                                            <label class="form-control w-full">
                                                <div class="label"><span class="label-text">Desa / Kelurahan <span class="text-error">*</span></span></div>
                                                <select wire:model.live="selectedDesa" @class(['select select-bordered', 'select-error' => $errors->has('selectedDesa')]) @disabled(empty($desa))>
                                                    <option value="">{{ empty($desa) ? 'Pilih kecamatan terlebih dahulu' : '-- Pilih Desa / Kelurahan --' }}</option>
                                                    @foreach($desa as $item)
                                                        <option value="{{ $item['id_desa'] }}">{{ $item['desa'] }}</option>
                                                    @endforeach
                                                </select>
                                                @error('selectedDesa') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                                            </label>
                                            <div wire:loading.flex wire:target="selectedKecamatan" class="label pt-0">
                                                <span class="label-text-alt text-primary">Memuat data desa/kelurahan...</span>
                                            </div>
                                        </div>
                                    </div>
                                    <label class="form-control w-full">
                                        <div class="label"><span class="label-text">Dusun, Nama Jalan, dsb. <span class="text-error">*</span></span></div>
                                        <input type="text" wire:model.lazy="dusun" @class(['input input-bordered w-full', 'input-error' => $errors->has('dusun')]) @disabled(empty($selectedDesa)) />
                                        @error('dusun') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                                    </label>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <label class="form-control w-full">
                                            <div class="label"><span class="label-text">RT</span></div>
                                            <input type="text" wire:model.lazy="rt" @class(['input input-bordered w-full', 'input-error' => $errors->has('rt')]) @disabled(empty($selectedDesa)) />
                                            @error('rt') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                                        </label>
                                        <label class="form-control w-full">
                                            <div class="label"><span class="label-text">RW</span></div>
                                            <input type="text" wire:model.lazy="rw" @class(['input input-bordered w-full', 'input-error' => $errors->has('rw')]) @disabled(empty($selectedDesa)) />
                                            @error('rw') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card bg-base-100 shadow-xl border border-base-200">
                            <div class="card-body">
                                <div class="space-y-2">
                                    <h2 class="card-title">Kontak</h2>
                                    <div class="divider mt-2"></div>
                                </div>
                                <div class="flex flex-col gap-6">
                                    <label class="form-control w-full">
                                        <div class="label"><span class="label-text">Handphone WhatsApp <span class="text-error">*</span></span></div>
                                        <input type="tel" wire:model.lazy="mobile" @class(['input input-bordered w-full', 'input-error' => $errors->has('mobile')]) />
                                        @error('mobile') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                                    </label>
                                    <div class="form-control w-full">
                                        <div class="flex items-end justify-between">
                                            <label class="label">
                                                <span class="label-text">Telepon</span>
                                            </label>
                                        </div>
                                        <input type="tel" wire:model.lazy="telepon" @class(['input input-bordered w-full', 'input-error' => $errors->has('telepon')]) />
                                        @error('telepon') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                                    </div>
                                    <label class="form-control w-full">
                                        <div class="label"><span class="label-text">Email <span class="text-error">*</span></span></div>
                                        <input type="email" wire:model.lazy="email" @class(['input input-bordered w-full', 'input-error' => $errors->has('email')]) />
                                        @error('email') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="card bg-base-100 shadow-xl border border-base-200">
                            <div class="card-body space-y-4">
                                <div class="flex items-center justify-between flex-wrap gap-3">
                                    <div>
                                        <h2 class="card-title">Tanda Tangan Peserta</h2>
                                        <p class="text-sm text-base-content/70">Mohon bubuhkan tanda tangan digital sebagai persetujuan data.</p>
                                    </div>
                                </div>

                                <div class="space-y-3" x-data="signaturePadComponent()" x-init="init()">
                                    <div
                                        class="rounded-2xl border border-dashed border-base-300/90 bg-base-200/60 p-4 shadow-inner"
                                        wire:ignore
                                    >
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="flex items-center gap-2 text-xs sm:text-sm text-base-content/60">
                                                <span>Gunakan kursor atau layar sentuh untuk menandatangani.</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <button type="button" class="btn btn-warning btn-xs" x-on:click.prevent="undo()">
                                                    Undo
                                                </button>
                                                <button type="button" class="btn btn-outline btn-xs btn-error" x-on:click.prevent="clear()">
                                                    Bersihkan
                                                </button>
                                            </div>
                                        </div>
                                        <div class="relative">
                                            <canvas
                                                x-ref="canvas"
                                                class="w-full h-48 rounded-xl bg-base-100 shadow-sm"
                                                style="touch-action: none;"
                                            ></canvas>
                                            <div class="pointer-events-none absolute inset-0 rounded-xl border-2 border-dashed border-base-300/80"></div>
                                        </div>
                                    </div>
                                    <input type="hidden" x-ref="hidden" name="ttd" wire:model.defer="ttd" />
                                </div>
                                @error('ttd') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                            </div>
                        </div>

                        @if($diklat['bigdata'] == 'Y')
                        <div class="card bg-base-100 shadow-xl border border-base-200">
                            <div class="card-body">
                                <div class="space-y-2">
                                    <h2 class="card-title">Data Keluarga</h2>
                                    <div class="divider mt-2"></div>
                                </div>
                                <div class="flex flex-col gap-4">
                                    <p class="text-sm text-warning">Data untuk bagian ini belum tersedia dari API.</p>
                                    <select class="select select-bordered" disabled><option>Status Pernikahan</option></select>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($diklat['jenis'] == 'sdma' || $diklat['jenis'] == 'infrastruktur_kompetensi')
                        <div class="card bg-base-100 shadow-xl border border-base-200">
                            <div class="card-body">
                                <div class="space-y-2">
                                    @if($diklat['jenis'] == 'sdma')
                                    <h2 class="card-title">Informasi Satuan Kerja</h2>
                                    @else
                                    <h2 class="card-title">Data Kompetensi & Instansi</h2>
                                    @endif
                                    <div class="divider mt-2"></div>
                                </div>
                                <div class="flex flex-col gap-4">
                                    <p class="text-sm text-warning">Data untuk bagian ini belum tersedia dari API.</p>
                                    @if($diklat['jenis'] == 'sdma')
                                    <input type="text" placeholder="NIP" class="input input-bordered w-full" disabled />
                                    <select class="select select-bordered" disabled><option>Pangkat</option></select>
                                    <input type="text" placeholder="Jabatan" class="input input-bordered w-full" disabled />
                                    <select class="select select-bordered" disabled><option>Asal Satker</option></select>
                                    @endif
                                    @if($diklat['jenis'] == 'infrastruktur_kompetensi')
                                    <input type="text" placeholder="No. Reg Asesor" class="input input-bordered w-full" disabled />
                                    <select class="select select-bordered" disabled><option>Nama LSP Induk</option></select>
                                    <select class="select select-bordered" disabled><option>Skema Sertifikasi</option></select>
                                    <input type="text" placeholder="Nama Instansi" class="input input-bordered w-full" disabled />
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="divider pt-4"></div>

                <div class="form-control">
                    <label class="cursor-pointer">
                        <input type="checkbox" wire:model.lazy="approval" class="checkbox checkbox-primary me-4" />
                        <span class="label-text">Saya menyatakan bahwa data yang saya isikan adalah benar dan saya menyetujui syarat dan ketentuan yang berlaku. <span class="text-error">*</span></span> 
                    </label>
                    @error('approval') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                </div>

                <div class="card-actions justify-between items-center mt-10">
                    <a href="{{ route('training.detail', ['id_diklat' => $diklat['id_diklat'], 'slug' => Str::slug($diklat['nama'])]) }}" wire:navigate class="btn btn-warning hidden md:inline-flex">
                        Kembali ke Detail Diklat
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <span wire:loading.remove wire:target="submit">Kirim Pendaftaran</span>
                        <span wire:loading wire:target="submit" class="loading loading-spinner"></span>
                        <span wire:loading wire:target="submit">Memproses...</span>
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>

@pushOnce('scripts')
<script>
    function loadSignaturePad(callback) {
        const src = "https://cdn.jsdelivr.net/npm/signature_pad@5.1.1/dist/signature_pad.umd.min.js";
        if (window.SignaturePad) {
            callback();
            return;
        }
        let script = document.querySelector(`script[src="${src}"]`);
        if (script) {
            script.addEventListener('load', callback);
            return;
        }
        script = document.createElement('script');
        script.src = src;
        script.onload = callback;
        document.head.appendChild(script);
    }

    window.signaturePadComponent = window.signaturePadComponent || function () {
        return {
            signaturePad: null,
            canvas: null,
            hiddenInput: null,
            syncFn: null,
            init() {
                loadSignaturePad(() => {
                    this.canvas = this.$refs.canvas;
                    this.hiddenInput = this.$refs.hidden;

                    const width = this.canvas.parentElement ? this.canvas.parentElement.clientWidth : 600;
                    const height = 220;

                    this.canvas.width = width;
                    this.canvas.height = height;

                    this.signaturePad = new SignaturePad(this.canvas, {
                        penColor: '#1f2937',
                        backgroundColor: 'rgba(255,255,255,0)',
                        minWidth: 0.75,
                        maxWidth: 2.5,
                    });

                    if (this.hiddenInput.value) {
                        try {
                            this.signaturePad.fromDataURL(this.hiddenInput.value);
                        } catch (error) {
                            console.warn('Tanda tangan tersimpan tidak dapat dimuat ulang.', error);
                            this.signaturePad.clear();
                        }
                    }

                    this.signaturePad.onEnd = () => {
                        this.sync();
                    };

                    this.registerSync();
                    this.sync();
                });
            },
            undo() {
                if (!this.signaturePad) {
                    return;
                }

                const data = this.signaturePad.toData();
                if (data.length) {
                    data.pop();
                    this.signaturePad.fromData(data);
                }
                this.sync();
            },
            clear() {
                if (!this.signaturePad) {
                    return;
                }
                this.signaturePad.clear();
                this.sync();
            },
            registerSync() {
                window.__registrationSignatureSyncers = window.__registrationSignatureSyncers || new Set();
                if (this.syncFn) {
                    window.__registrationSignatureSyncers.delete(this.syncFn);
                }
                this.syncFn = () => this.sync();
                window.__registrationSignatureSyncers.add(this.syncFn);
            },
            sync() {
                if (!this.hiddenInput) {
                    return;
                }

                const value = this.signaturePad && !this.signaturePad.isEmpty()
                    ? this.signaturePad.toDataURL('image/png')
                    : '';

                if (this.hiddenInput.value !== value) {
                    this.hiddenInput.value = value;
                    this.hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
                }
            },
        };
    };

    (function bindSignatureFormListener() {
        const attach = () => {
            const form = document.querySelector('[data-signature-form]');
            if (!form || form.dataset.signatureSyncBound) {
                return;
            }
            form.dataset.signatureSyncBound = 'true';
            form.addEventListener('submit', () => {
                if (!window.__registrationSignatureSyncers) {
                    return;
                }
                window.__registrationSignatureSyncers.forEach((fn) => {
                    try {
                        fn();
                    } catch (error) {
                        console.warn('Gagal menyinkronkan tanda tangan sebelum submit.', error);
                    }
                });
            }, { capture: true });
        };

        document.addEventListener('DOMContentLoaded', attach);
        document.addEventListener('livewire:navigated', attach);
    })();
</script>
@endPushOnce
