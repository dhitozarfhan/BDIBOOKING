<div class="container mx-auto px-4 py-8">
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
            <h1 class="text-4xl font-bold mb-6">Pendaftaran Diklat Tersedia</h1>
            <div class="overflow-x-auto rounded-lg shadow-lg">
                <table class="table table-zebra w-full">
                    {{-- head --}}
                    <thead class="bg-base-200">
                        <tr>
                            <th>Nama Diklat</th>
                            <th>Jenis</th>
                            <th>Jadwal</th>
                            <th>Lokasi</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($trainings as $training)
                            <tr class="hover">
                                <td>
                                    <a href="{{ route('training.detail', ['id_diklat' => $training['id_diklat']]) }}" wire:navigate class="font-bold hover:underline">
                                        {{ $training['nama'] }}
                                    </a>
                                </td>
                                <td><div class="badge badge-accent badge-outline">{{ $training['jenis_diklat'] }}</div></td>
                                <td>{{ \Carbon\Carbon::parse($training['tgl_mulai'])->isoFormat('D MMM YYYY') }} - {{ \Carbon\Carbon::parse($training['tgl_selesai'])->isoFormat('D MMM YYYY') }}</td>
                                <td>{{ $training['tempat'] }}</td>
                                <td>
                                     @if($training['allowed_reg'] == 'Y')
                                        <a href="{{ $training['register_url'] . $training['id_diklat'] }}" target="_blank" class="btn btn-primary btn-sm">
                                            Daftar
                                        </a>
                                    @else
                                        <div class="badge badge-neutral">Pendaftaran Ditutup</div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>