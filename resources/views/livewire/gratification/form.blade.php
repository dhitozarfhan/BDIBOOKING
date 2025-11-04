<div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
    <div class="max-w-full">
        <h2 class="text-lg font-medium text-gray-900">
            Formulir Laporan Gratifikasi
        </h2>

        @if (session()->has('message'))
            <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Berhasil!</strong>
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @else
            <div class="mt-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
                <p class="m-0">Setiap laporan akan dijaga kerahasiannya dan dalam hal terdapat bukti yang cukup akan ditindaklanjuti pada proses investigasi selanjutnya. Keberadaan pelaporan gratifikasi menciptakan sistem saling mengawasi terhadap etika, kesesuaian perilaku dan ketaatan prosedur kerja yang dilaksanakan oleh sumber daya manusia.</p>
            </div>
        @endif

        <form wire:submit.prevent="save" class="mt-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Kolom Kiri --}}
                <div class="space-y-6">
                    <div>
                        <label for="nama_pelapor" class="block font-medium text-sm text-gray-700">Nama Pelapor <span class="text-red-500">*</span></label>
                        <input id="nama_pelapor" type="text" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" wire:model.lazy="nama_pelapor">
                        @error('nama_pelapor') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="nomor_identitas" class="block font-medium text-sm text-gray-700">Nomor Identitas (KTP/SIM)</label>
                        <input id="nomor_identitas" type="text" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" wire:model.lazy="nomor_identitas">
                        @error('nomor_identitas') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="alamat" class="block font-medium text-sm text-gray-700">Alamat</label>
                        <textarea id="alamat" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" wire:model.lazy="alamat"></textarea>
                        @error('alamat') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="pekerjaan" class="block font-medium text-sm text-gray-700">Pekerjaan</label>
                        <input id="pekerjaan" type="text" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" wire:model.lazy="pekerjaan">
                        @error('pekerjaan') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="telepon" class="block font-medium text-sm text-gray-700">Telepon</label>
                        <input id="telepon" type="text" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" wire:model.lazy="telepon">
                        @error('telepon') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                        <input id="email" type="email" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" wire:model.lazy="email">
                        @error('email') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Kolom Kanan --}}
                <div class="space-y-6">
                    <div>
                        <label for="judul_laporan" class="block font-medium text-sm text-gray-700">Judul Laporan <span class="text-red-500">*</span></label>
                        <input id="judul_laporan" type="text" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" wire:model.lazy="judul_laporan">
                        @error('judul_laporan') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="uraian_laporan" class="block font-medium text-sm text-gray-700">Uraian Laporan <span class="text-red-500">*</span></label>
                        <textarea id="uraian_laporan" rows="5" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" wire:model.lazy="uraian_laporan"></textarea>
                        @error('uraian_laporan') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="data_dukung" class="block font-medium text-sm text-gray-700">Data Dukung (doc/pdf/zip, max 1MB)</label>
                        <input id="data_dukung" type="file" class="mt-1 block w-full" wire:model="data_dukung">
                        <div wire:loading wire:target="data_dukung" class="text-sm text-gray-500 mt-1">Mengunggah...</div>
                        @error('data_dukung') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4 mt-6">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Kirim Laporan
                </button>
                <button type="button" wire:click="setView('index')" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-200 focus:bg-gray-200 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Kembali
                </button>
                <div wire:loading wire:target="save" class="text-sm text-gray-500">
                    Menyimpan...
                </div>
            </div>
        </form>
    </div>
</div>