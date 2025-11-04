<div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
    <div class="max-w-full">
        <h2 class="text-lg font-medium text-gray-900">
            Status Laporan Gratifikasi
        </h2>

        <p class="mt-2 text-sm text-gray-600">
            Masukkan kode register untuk melihat status laporan Anda.
        </p>

        <form wire:submit.prevent="checkStatus" class="mt-6 space-y-6">
            <div class="space-y-4">
                <div>
                    <label for="kode_register" class="block font-medium text-sm text-gray-700">Kode Register <span class="text-red-500">*</span></label>
                    <input id="kode_register" type="text" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" wire:model.lazy="kode_register">
                    @error('kode_register') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex items-center gap-4 mt-6">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Cek Status
                </button>
                <button type="button" wire:click="setView('index')" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-200 focus:bg-gray-200 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Kembali
                </button>
            </div>
        </form>

        @if($showReportDetail)
            <div class="mt-8 bg-gray-50 p-4 rounded-lg border border-gray-200">
                <h3 class="text-md font-medium text-gray-900">Detail Laporan</h3>
                
                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Judul Laporan</p>
                        <p class="font-medium">{{ $reportDetail->judul_laporan }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nama Pelapor</p>
                        <p class="font-medium">{{ $reportDetail->nama_pelapor }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Tanggal Laporan</p>
                        <p class="font-medium">{{ $reportDetail->created_at ? $reportDetail->created_at->format('d M Y H:i') : 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        <p class="font-medium">
                            @if($reportDetail->status == 'I')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Inisiasi</span>
                            @elseif($reportDetail->status == 'P')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Proses</span>
                            @elseif($reportDetail->status == 'D')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Disposisi</span>
                            @elseif($reportDetail->status == 'T')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Selesai</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Tidak Dikenal</span>
                            @endif
                        </p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-600">Uraian Laporan</p>
                        <p class="font-medium">{{ $reportDetail->uraian_laporan }}</p>
                    </div>
                </div>
                
                @if($reportDetail->status === 'T')
                    <div class="mt-4">
                        <p class="text-sm text-gray-600">Jawaban</p>
                        <p class="font-medium">{{ $reportDetail->jawaban ?? 'Jawaban belum tersedia' }}</p>
                    </div>
                @endif
            </div>
        @elseif($statusError)
            <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ $statusError }}</span>
            </div>
        @endif
    </div>
</div>