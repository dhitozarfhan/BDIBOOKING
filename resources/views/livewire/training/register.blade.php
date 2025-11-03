<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">
    {{-- Loading State --}}
    <div wire:loading class="w-full flex justify-center items-center py-10">
        <span class="loading loading-lg loading-spinner text-primary"></span>
        <span class="text-lg font-semibold text-base-content/80 ml-4">Memuat data diklat...</span>
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
        @elseif(empty($trainings))
            <div role="alert" class="alert alert-info">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <h3 class="font-bold">Informasi</h3>
                    <div class="text-xs">Tidak ada data diklat yang tersedia saat ini.</div>
                </div>
            </div>
        @else
            <div class="card bg-base-100 shadow-xl border border-base-200">
                <div class="card-body p-0">
                    <div class="px-6 py-6">
                        <h1 class="text-3xl font-bold text-base-content">Daftar Diklat Tersedia</h1>
                        <p class="text-sm text-base-content/70 mt-2">
                            Berikut rangkuman diklat yang sedang membuka pendaftaran. Gunakan tombol pada kolom terakhir untuk melihat detail atau langsung mendaftar.
                        </p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="table table-zebra w-full">
                            <thead class="bg-base-200 text-sm text-base-content/80">
                                <tr>
                                    <th>Nama Diklat</th>
                                    <th>Jenis</th>
                                    <th>Jadwal</th>
                                    <th>Lokasi</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($trainings as $training)
                                    @php
                                        $start = \Carbon\Carbon::parse($training['tgl_mulai']);
                                        $end = \Carbon\Carbon::parse($training['tgl_selesai']);
                                        $statusOpen = $training['allowed_reg'] === 'Y';
                                    @endphp
                                    <tr class="hover align-top">
                                        <td class="max-w-xs">
                                            <div class="space-y-1">
                                                <a href="{{ route('training.detail', ['id_diklat' => $training['id_diklat'], 'slug' => Str::slug($training['nama'])]) }}"
                                                   wire:navigate
                                                   class="font-semibold text-base-content hover:text-primary transition-colors">
                                                    {{ $training['nama'] }}
                                                </a>
                                                @if(!empty($training['penyelenggara']))
                                                    <div class="text-xs text-base-content/60">
                                                        {{ $training['penyelenggara'] }}
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="inline-flex items-center gap-2 px-3 py-1 text-xs font-semibold tracking-wide uppercase rounded-full bg-primary/10 text-primary">
                                                {{ $training['jenis_diklat'] }}
                                            </span>
                                        </td>
                                        <td class="text-sm">
                                            <div class="font-medium text-base-content">
                                                {{ $start->isoFormat('D MMM YYYY') }} - {{ $end->isoFormat('D MMM YYYY') }}
                                            </div>
                                        </td>
                                        <td class="text-sm text-base-content">
                                            {{ $training['tempat'] ?? 'Lokasi belum tersedia' }}
                                        </td>
                                        <td class="text-center">
                                            <div class="flex flex-col sm:flex-row items-center justify-center gap-2">
                                                <a href="{{ route('training.detail', ['id_diklat' => $training['id_diklat'], 'slug' => Str::slug($training['nama'])]) }}"
                                                   wire:navigate
                                                   class="btn btn-success">
                                                    Detail
                                                </a>
                                                @if($statusOpen)
                                                    <a href="{{ route('training.register', ['id_diklat' => $training['id_diklat'], 'slug' => Str::slug($training['nama'])]) }}"
                                                       wire:navigate
                                                       class="btn btn-primary">
                                                        Daftar
                                                    </a>
                                                @else
                                                    <span class="badge badge-outline badge-sm text-xs">Tutup</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
