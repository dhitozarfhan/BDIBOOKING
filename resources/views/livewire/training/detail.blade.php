<div class="container mx-auto px-4 py-8">
    {{-- Loading State --}}
    <div wire:loading class="w-full flex justify-center items-center py-10">
        <span class="loading loading-lg loading-spinner text-primary"></span>
        <span class="text-lg font-semibold text-base-content/80 ml-4">Memuat detail diklat...</span>
    </div>

    {{-- Content --}}
    <div wire:loading.remove>
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
            <div class="hero bg-base-200 rounded-lg py-10">
                <div class="hero-content text-center">
                    <div class="max-w-2xl">
                        <div class="badge badge-accent mb-2">{{ $training['jenis_diklat'] }}</div>
                        <h1 class="text-4xl font-bold">{{ $training['nama_lengkap'] }}</h1>
                    </div>
                </div>
            </div>

            {{-- Details Card --}}
            <div class="card lg:card-side bg-base-100 shadow-xl mt-8">
                <div class="card-body">
                    <h2 class="card-title">Detail Informasi</h2>
                    <div class="divider my-0"></div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <div class="font-bold text-sm text-base-content/60">Jadwal Pelaksanaan</div>
                            <div>{{ \Carbon\Carbon::parse($training['tgl_mulai'])->isoFormat('D MMMM YYYY') }} - {{ \Carbon\Carbon::parse($training['tgl_selesai'])->isoFormat('D MMMM YYYY') }}</div>
                        </div>
                        <div>
                            <div class="font-bold text-sm text-base-content/60">Lokasi</div>
                            <div>{{ $training['tempat'] }}</div>
                        </div>
                        <div>
                            <div class="font-bold text-sm text-base-content/60">Penyelenggara</div>
                            <div>{{ $training['penyelenggara'] }}</div>
                        </div>
                        <div>
                            <div class="font-bold text-sm text-base-content/60">Angkatan</div>
                            <div>{{ $training['angkatan'] }}</div>
                        </div>
                        <div class="md:col-span-2">
                            <div class="font-bold text-sm text-base-content/60">Skema</div>
                            <div>{{ $training['uraian_skema'] }}</div>
                        </div>
                    </div>
                    <div class="card-actions justify-end mt-6">
                        @if($training['allowed_reg'] == 'Y')
                            <a href="{{ route('training.register', ['id_diklat' => $training['id_diklat']]) }}" wire:navigate class="btn btn-primary">
                                Daftar Sekarang
                            </a>
                        @else
                            <div class="badge badge-lg badge-neutral">Pendaftaran Ditutup</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Participants Table --}}
            @if(!empty($participants))
                <div class="card bg-base-100 shadow-xl mt-8">
                    <div class="card-body">
                        <h2 class="card-title">Peserta yang Terdaftar</h2>
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
                                            <th>Penempatan</th>
                                            <th>KTP</th>
                                            <th>TUK</th>
                                            <th>Lulus UK</th>
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
                                                <td>{{ $participant['penempatan'] }}</td>
                                                <td>{{ $participant['ktp'] }}</td>
                                                <td>{{ $participant['tuk'] }}</td>
                                                <td>{{ $participant['ukom'] }}</td>
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
            @endif

            {{-- Back Button --}}
            <div class="mt-8 text-center">
                <a href="{{ route('register') }}" wire:navigate class="btn btn-ghost">
                    &larr; Kembali ke daftar diklat
                </a>
            </div>
        @endif
    </div>
</div>