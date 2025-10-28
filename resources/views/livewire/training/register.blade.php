<div class="container mx-auto px-4 py-8">
    <div wire:loading class="w-full text-center">
        <div class="flex justify-center items-center">
            <svg class="animate-spin -ml-1 mr-3 h-10 w-10 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-lg font-semibold text-gray-700">Memuat data diklat...</span>
        </div>
    </div>

    <div wire:loading.remove>
        @if($error)
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                <p class="font-bold">Terjadi Kesalahan</p>
                <p>{{ $error }}</p>
            </div>
        @elseif(empty($trainings))
            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4" role="alert">
                <p class="font-bold">Informasi</p>
                <p>Tidak ada data diklat yang tersedia saat ini.</p>
            </div>
        @else
            <h1 class="text-3xl font-bold mb-6 text-gray-800">Pendaftaran Diklat</h1>
            <div class="overflow-x-auto bg-white rounded-lg shadow-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                Nama Diklat
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                Jenis
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                Jadwal
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                Lokasi
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Aksi</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($trainings as $training)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-normal">
                                    <div class="text-sm font-semibold text-gray-900">{{ $training['nama'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $training['jenis_diklat'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($training['tgl_mulai'])->isoFormat('D MMM YYYY') }} - {{ \Carbon\Carbon::parse($training['tgl_selesai'])->isoFormat('D MMM YYYY') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-normal text-sm text-gray-600">
                                    {{ $training['tempat'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @if($training['allowed_reg'] == 'Y')
                                        <a href="{{ $training['register_url'] . $training['id_diklat'] }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-sm">
                                            Daftar
                                        </a>
                                    @else
                                        <span class="px-3 py-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-200 text-gray-800">
                                            Pendaftaran Ditutup
                                        </span>
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
