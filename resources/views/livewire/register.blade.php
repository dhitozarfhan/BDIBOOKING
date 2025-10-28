<div>
    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold mb-6">Diklat Tersedia</h1>

        @if($error)
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ $error }}</span>
            </div>
        @elseif(empty($trainings))
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
                <p>Tidak ada diklat yang tersedia untuk saat ini.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($trainings as $training)
                    <div class="bg-white border rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold mb-2">{{ $training['nama_diklat'] ?? 'Nama tidak tersedia' }}</h2>
                        <p class="text-gray-600"><span class="font-semibold">Jadwal:</span> {{ $training['tgl_mulai'] ?? '-' }} s/d {{ $training['tgl_selesai'] ?? '-' }}</p>
                        <p class="text-gray-600"><span class="font-semibold">Lokasi:</span> {{ $training['lokasi'] ?? '-' }}</p>
                        <p class="text-gray-600"><span class="font-semibold">Pendaftaran:</span> {{ $training['tgl_registrasi_mulai'] ?? '-' }} s/d {{ $training['tgl_registrasi_selesai'] ?? '-' }}</p>
                        <div class="mt-4">
                            <a href="#" class="text-blue-500 hover:underline">Detail & Daftar</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
