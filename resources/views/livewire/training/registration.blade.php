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
            <div class="hero bg-base-200 rounded-lg shadow mb-8">
                <div class="hero-content text-center">
                    <div class="max-w-4xl">
                        <h1 class="text-3xl font-bold">Pendaftaran: {{ $diklat['nama_lengkap'] }}</h1>
                        <p class="py-2 text-sm text-base-content/70">di {{ $diklat['tempat'] }}</p>
                    </div>
                </div>
            </div>

            <div role="alert" class="alert alert-warning mb-8 shadow-sm">
                <div class="flex-1">
                    <h3 class="font-bold">Informasi Penting!</h3>
                    <ul class="list-decimal list-inside text-sm mt-2 space-y-1">
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
                            <div class="card-body space-y-6">
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
                        <div class="card bg-base-100 shadow-xl">
                            <div class="card-body space-y-6">
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
                    </div>

                    {{-- Right Column --}}
                    <div class="space-y-8">
                        @if($diklat['bigdata'] == 'Y')
                        <div class="card bg-base-100 shadow-xl">
                            <div class="card-body space-y-6">
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

                        <div class="card bg-base-100 shadow-xl">
                            <div class="card-body space-y-6">
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
                                        <input type="text" wire:model.lazy="dusun" @class(['input input-bordered w-full', 'input-error' => $errors->has('dusun')]) />
                                        @error('dusun') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                                    </label>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <label class="form-control w-full">
                                            <div class="label"><span class="label-text">RT</span></div>
                                            <input type="text" wire:model.lazy="rt" @class(['input input-bordered w-full', 'input-error' => $errors->has('rt')]) />
                                            @error('rt') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                                        </label>
                                        <label class="form-control w-full">
                                            <div class="label"><span class="label-text">RW</span></div>
                                            <input type="text" wire:model.lazy="rw" @class(['input input-bordered w-full', 'input-error' => $errors->has('rw')]) />
                                            @error('rw') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card bg-base-100 shadow-xl">
                            <div class="card-body space-y-6">
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

                        @if($diklat['bigdata'] == 'Y')
                        <div class="card bg-base-100 shadow-xl">
                            <div class="card-body space-y-6">
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
                        <div class="card bg-base-100 shadow-xl">
                            <div class="card-body space-y-6">
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
                    <label class="label cursor-pointer justify-start gap-4">
                        <input type="checkbox" wire:model.lazy="approval" class="checkbox checkbox-primary" />
                        <span class="label-text">Saya menyatakan bahwa data yang saya isikan adalah benar dan saya menyetujui syarat dan ketentuan yang berlaku. <span class="text-error">*</span></span> 
                    </label>
                    @error('approval') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                </div>

                <div class="card-actions justify-center mt-8">
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
