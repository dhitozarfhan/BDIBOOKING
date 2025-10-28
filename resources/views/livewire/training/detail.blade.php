<div class="container mx-auto px-4 py-8">
    <div wire:loading class="w-full text-center py-10">
        <div class="flex justify-center items-center">
            <svg class="animate-spin -ml-1 mr-3 h-10 w-10 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-lg font-semibold text-gray-700">Memuat detail diklat...</span>
        </div>
    </div>

    <div wire:loading.remove>
        @if($error)
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md" role="alert">
                <p class="font-bold">Terjadi Kesalahan</p>
                <p>{{ $error }}</p>
            </div>
        @elseif(empty($training))
             <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded-md" role="alert">
                <p class="font-bold">Informasi</p>
                <p>Detail diklat tidak dapat ditemukan.</p>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="p-6 sm:p-8">
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800 mb-3">
                        {{ $training['jenis_diklat'] }}
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-4">{{ $training['nama_lengkap'] }}</h1>
                    

                    <div class="mt-6 sm:mt-8 border-t border-gray-200 pt-6">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Jadwal Pelaksanaan</dt>
                                <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ \Carbon\Carbon::parse($training['tgl_mulai'])->isoFormat('D MMMM YYYY') }} - {{ \Carbon\Carbon::parse($training['tgl_selesai'])->isoFormat('D MMMM YYYY') }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Lokasi</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $training['tempat'] }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Penyelenggara</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $training['penyelenggara'] }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Angkatan</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $training['angkatan'] }}</dd>
                            </div>
                             <div class="md:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Skema</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $training['uraian_skema'] }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 sm:px-8 sm:py-5 flex items-center justify-end">
                     @if($training['allowed_reg'] == 'Y')
                        <a href="{{ $training['register_url'] . $training['id_diklat'] }}" target="_blank" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-base text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md">
                            Daftar Sekarang
                        </a>
                    @else
                        <span class="px-4 py-3 inline-flex text-sm leading-5 font-semibold rounded-full bg-gray-200 text-gray-800">
                            Pendaftaran Sudah Ditutup
                        </span>
                    @endif
                </div>
            </div>
             <div class="mt-8 text-center">
                <a href="/register" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                    &larr; Kembali ke daftar diklat
                </a>
            </div>
        @endif
    </div>
</div>
