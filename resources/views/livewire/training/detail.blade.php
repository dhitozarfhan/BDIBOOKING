<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">
    {{-- Loading State --}}
    <div wire:loading class="w-full flex justify-center items-center py-10">
        <span class="loading loading-lg loading-spinner text-primary"></span>
        <span class="text-lg font-semibold text-base-content/80 ml-4">Memuat detail diklat...</span>
    </div>

    {{-- Content --}}
    <div wire:loading.remove>
        @if($successMessage)
            <div role="alert" class="alert alert-success mb-6 shadow">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <div>
                    <h3 class="font-bold">Berhasil!</h3>
                    <div class="text-xs">{{ $successMessage }}</div>
                </div>
            </div>
        @endif

        @if($error)
            <div role="alert" class="alert alert-error">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <div>
                    <h3 class="font-bold">Terjadi Kesalahan!</h3>
                    <div class="text-xs">{{ $error }}</div>
                </div>
            </div>
        @elseif(empty($training))
            <div role="alert" class="alert alert-info">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <h3 class="font-bold">Informasi</h3>
                    <div class="text-xs">Detail diklat tidak dapat ditemukan.</div>
                </div>
            </div>
        @else
            {{-- Hero section for the training title --}}
            <div class="hero rounded-2xl bg-gradient-to-br from-primary/10 via-secondary/10 to-primary/5 py-10 shadow-lg border border-base-200">
                <div class="hero-content flex-col lg:flex-row lg:items-center lg:justify-between w-full">
                    <div class="max-w-3xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/20 text-primary text-xs font-semibold uppercase tracking-wide mb-3">
                            <svg class="w-3 h-3 fill-current" viewBox="0 0 16 16" aria-hidden="true">
                                <path d="M12 4H9.5l-.7-1.4A1 1 0 0 0 7.9 2h-2a1 1 0 0 0-.9.6L4.3 4H2a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V5a1 1 0 0 0-1-1ZM7 11l-3-3h2V6h2v2h2l-3 3Z"/>
                            </svg>
                            {{ $training['jenis_diklat'] }}
                        </div>
                        <h1 class="text-3xl md:text-4xl font-bold leading-tight text-base-content">
                            {{ $training['nama_lengkap'] }}
                        </h1>
                        <p class="mt-4 text-base-content/70">
                            {{ $training['uraian_skema'] }}
                        </p>
                    </div>
                    <div class="card bg-base-100 shadow-md mt-6 lg:mt-0">
                        <div class="card-body px-5 py-4">
                            <span class="text-xs font-semibold uppercase text-base-content/60">Status Pendaftaran</span>
                            @if(($training['allowed_reg'] ?? 'N') === 'Y')
                                <div class="text-2xl font-bold text-success mt-1 mb-3">Masih Dibuka</div>
                                <a href="{{ $registrationRoute }}"
                                   wire:navigate
                                   class="btn btn-primary btn-lg">
                                    Daftar Sekarang
                                </a>
                            @else
                                <div class="text-2xl font-bold text-error mt-1 mb-3">Ditutup</div>
                                <span class="badge badge-neutral">Pendaftaran Ditutup</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Details Card --}}
            <div class="card bg-base-100 shadow-xl mt-8 border border-base-200">
                <div class="card-body space-y-6">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div>
                            <h2 class="card-title text-2xl">Ringkasan Diklat</h2>
                            <p class="text-sm text-base-content/60">Informasi singkat mengenai jadwal dan penyelenggara.</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="p-4 rounded-xl bg-base-200/60 border border-base-200 flex flex-col gap-2">
                            <span class="text-xs font-semibold uppercase text-base-content/60">Jadwal Pelaksanaan</span>
                            <div class="flex items-start gap-2 text-sm">
                                <svg class="w-4 h-4 mt-1 text-primary" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 0 1 1 1v1h6V3a1 1 0 1 1 2 0v1h1a2 2 0 0 1 2 2v2H2V6a2 2 0 0 1 2-2h1V3a1 1 0 1 1 2 0v1Zm12 7H2v7a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9Zm-11 3a1 1 0 0 1 2 0v2a1 1 0 1 1-2 0v-2Zm5 0a1 1 0 0 1 2 0v2a1 1 0 1 1-2 0v-2Z" clip-rule="evenodd" />
                                </svg>
                                <span>{{ \Carbon\Carbon::parse($training['tgl_mulai'])->isoFormat('D MMMM YYYY') }} - {{ \Carbon\Carbon::parse($training['tgl_selesai'])->isoFormat('D MMMM YYYY') }}</span>
                            </div>
                        </div>
                        <div class="p-4 rounded-xl bg-base-200/60 border border-base-200 flex flex-col gap-2">
                            <span class="text-xs font-semibold uppercase text-base-content/60">Lokasi</span>
                            <div class="flex items-start gap-2 text-sm">
                                <svg class="w-4 h-4 mt-1 text-primary" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 0 1 9.9 9.9l-4.243 4.243a1 1 0 0 1-1.414 0L5.05 13.95a7 7 0 0 1 0-9.9ZM10 11a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z" clip-rule="evenodd" />
                                </svg>
                                <span>{{ $training['tempat'] }}</span>
                            </div>
                        </div>
                        <div class="p-4 rounded-xl bg-base-200/60 border border-base-200 flex flex-col gap-2">
                            <span class="text-xs font-semibold uppercase text-base-content/60">Penyelenggara</span>
                            <div class="flex items-start gap-2 text-sm">
                                <svg class="w-4 h-4 mt-1 text-primary" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10.076 2.223a1 1 0 0 0-1.152 0l-7 5A1 1 0 0 0 2 8h1v6a2 2 0 0 0 2 2h2v-3a3 3 0 1 1 6 0v3h2a2 2 0 0 0 2-2V8h1a1 1 0 0 0 .576-1.777l-7-5Z" clip-rule="evenodd" />
                                </svg>
                                <span>{{ $training['penyelenggara'] }}</span>
                            </div>
                        </div>
                        <div class="p-4 rounded-xl bg-base-200/60 border border-base-200 flex flex-col gap-2">
                            <span class="text-xs font-semibold uppercase text-base-content/60">Angkatan</span>
                            <div class="flex items-start gap-2 text-sm">
                                <svg class="w-4 h-4 mt-1 text-primary" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M3 4a1 1 0 0 1 1-1h2.25a3 3 0 0 1 2.45 1.3l.6.85a1 1 0 0 0 .82.41h4.88a1 1 0 0 1 .97 1.243l-1.35 5.4a2 2 0 0 1-1.94 1.513H9l.78 3.114a1 1 0 0 1-.97 1.243H7.5a1 1 0 0 1-.94-.658L4.11 6H4a1 1 0 0 1-1-1Z" />
                                </svg>
                                <span>{{ $training['angkatan'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Participants Table --}}
            @if(!empty($participants))
                <div class="card bg-base-100 shadow-xl mt-8">
                    <div class="card-body">
                        <div class="flex items-center justify-between gap-4 flex-wrap">
                            <div>
                                <h2 class="card-title text-xl">Peserta Terdaftar</h2>
                                <p class="text-sm text-base-content/60">Daftar peserta akan diperbarui setelah proses verifikasi admin.</p>
                            </div>
                            <span class="badge badge-lg badge-primary badge-outline">{{ count($participants) }} Peserta</span>
                        </div>
                        <div class="divider my-0"></div>
                        <div class="overflow-x-auto mt-4">
                            <table class="table table-zebra w-full">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Umur</th>
                                        <th>L/P</th>
                                        <th>Pendidikan</th>
                                        @if($training['jenis'] == 'sdmi')
                                            <th>KTP</th>
                                        @elseif($training['jenis'] == 'sdma')
                                            <th>Asal Satker</th>
                                            <th>NIP</th>
                                            <th>Jabatan</th>
                                            <th>Pangkat</th>
                                        @elseif($training['jenis'] == 'infrastruktur_kompetensi')
                                            <th>Nomor Reg Asesor</th>
                                            <th>LSP Induk</th>
                                            <th>Skema yang Dipilih</th>
                                            <th>Instansi Tempat Bekerja</th>
                                        @endif
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($participants as $participant)
                                        <tr>
                                            <td>{{ $participant['nama'] }}</td>
                                            <td>{{ $participant['umur'] }}</td>
                                            <td>{{ $participant['kelamin'] }}</td>
                                            <td>{{ $participant['pendidikan'] }}</td>
                                            @if($training['jenis'] == 'sdmi')
                                                <td>{{ $participant['ktp'] }}</td>
                                            @elseif($training['jenis'] == 'sdma')
                                                <td>{{ $participant['satker'] }}</td>
                                                <td>{{ $participant['nip'] }}</td>
                                                <td>{{ $participant['jabatan'] }}</td>
                                                <td>{{ $participant['pangkat'] }}</td>
                                            @elseif($training['jenis'] == 'infrastruktur_kompetensi')
                                                <td>{{ $participant['nomor_reg_asesor'] }}</td>
                                                <td>{{ $participant['lsp'] }}</td>
                                                <td>{{ $participant['skema'] }}</td>
                                                <td>{{ $participant['instansi'] }}</td>
                                            @endif
                                            <td>
                                                <div class="badge 
                                                    @switch($participant['status'])
                                                        @case('Diterima') badge-success @break
                                                        @case('Ditolak') badge-error @break
                                                        @case('Direview') badge-warning @break
                                                        @default badge-ghost @endswitch
                                                ">{{ $participant['status'] }}</div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-info shadow-md mt-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <div>
                        <h3 class="font-bold">Belum ada peserta terdaftar</h3>
                        <div class="text-xs">Daftar peserta akan muncul setelah pendaftaran diverifikasi oleh admin.</div>
                    </div>
                </div>
            @endif

            {{-- Back Button --}}
            <div class="mt-8 text-center">
                <a href="{{ route('register') }}" wire:navigate class="btn btn-warning">
                    Kembali ke daftar diklat
                </a>
            </div>
        @endif
    </div>
</div>
