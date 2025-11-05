<div class="p-4 sm:p-8 bg-base-100 shadow sm:rounded-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="{{ route('home') }}"><i class="bi bi-house-fill"></i></a></li>
                <li><a href="{{ route('gratification') }}">Pelaporan Gratifikasi</a></li>
                <li>Formulir Laporan</li>
            </ul>
        </div>
        <h2 class="text-2xl font-bold text-base-content mt-4">
            Formulir Laporan Gratifikasi
        </h2>

        @if (session()->has('message'))
            <div class="mt-4 alert alert-success bg-green-100 text-green-800 shadow-lg">
                <div>
                    <i class="bi bi-check-circle-fill"></i>
                    <span>
                        <strong class="font-bold">Berhasil!</strong>
                        <span class="block sm:inline">{{ session('message') }} <strong class="font-bold">{{ session('kode_register') }}</strong>. Terima kasih atas partisipasi Anda dalam menjaga integritas.</span>
                    </span>
                </div>
            </div>
        @else
            <div class="mt-4 alert alert-info shadow-lg">
                <div>
                    <i class="bi bi-info-circle-fill"></i>
                    <span>Setiap laporan akan dijaga kerahasiannya dan dalam hal terdapat bukti yang cukup akan ditindaklanjuti pada proses investigasi selanjutnya. Keberadaan pelaporan gratifikasi menciptakan sistem saling mengawasi terhadap etika, kesesuaian perilaku dan ketaatan prosedur kerja yang dilaksanakan oleh sumber daya manusia.</span>
                </div>
            </div>
        @endif

        <div class="mt-6 p-6 bg-base-200 rounded-lg border border-base-300">
            <form wire:submit.prevent="save" class="space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Kolom Kiri --}}
                    <div class="space-y-6">
                        <h3 class="text-lg font-medium text-base-content border-b border-base-300 pb-2">Informasi Pelapor</h3>
                        <div class="form-group">
                                <label for="nama_pelapor" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nama Pelapor <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="bi bi-person-fill text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                                    </div>
                                    <input 
                                        id="nama_pelapor" 
                                        type="text" 
                                        class="w-full pl-9 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-300" 
                                        wire:model.lazy="nama_pelapor"
                                        placeholder="Masukkan nama lengkap">
                                </div>
                                @error('nama_pelapor') <p class="text-red-500 text-xs mt-1 flex items-center"><i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}</p> @enderror
                            </div>

                            <!-- Nomor Identitas -->
                            <div class="form-group">
                                <label for="nomor_identitas" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nomor Identitas (KTP/SIM)
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="bi bi-card-heading text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                                    </div>
                                    <input 
                                        id="nomor_identitas" 
                                        type="text" 
                                        class="w-full pl-9 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-300" 
                                        wire:model.lazy="nomor_identitas"
                                        placeholder="Contoh: 3174012345678901">
                                </div>
                                @error('nomor_identitas') <p class="text-red-500 text-xs mt-1 flex items-center"><i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}</p> @enderror
                            </div>

                            <!-- Alamat -->
                            <div class="form-group">
                                <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Alamat
                                </label>
                                <div class="relative">
                                    <textarea 
                                        id="alamat" 
                                        rows="3"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-300" 
                                        wire:model.lazy="alamat"
                                        placeholder="Masukkan alamat lengkap"></textarea>
                                </div>
                                @error('alamat') <p class="text-red-500 text-xs mt-1 flex items-center"><i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}</p> @enderror
                            </div>

                            <!-- Pekerjaan -->
                            <div class="form-group">
                                <label for="pekerjaan" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Pekerjaan
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="bi bi-briefcase-fill text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                                    </div>
                                    <input 
                                        id="pekerjaan" 
                                        type="text" 
                                        class="w-full pl-9 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-300" 
                                        wire:model.lazy="pekerjaan"
                                        placeholder="Contoh: Pegawai Negeri Sipil">
                                </div>
                                @error('pekerjaan') <p class="text-red-500 text-xs mt-1 flex items-center"><i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}</p> @enderror
                            </div>

                            <!-- Telepon -->
                            <div class="form-group">
                                <label for="telepon" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Telepon
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="bi bi-telephone-fill text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                                    </div>
                                    <input 
                                        id="telepon" 
                                        type="text" 
                                        class="w-full pl-9 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-300" 
                                        wire:model.lazy="telepon"
                                        placeholder="Contoh: 08123456789">
                                </div>
                                @error('telepon') <p class="text-red-500 text-xs mt-1 flex items-center"><i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}</p> @enderror
                            </div>

                            <!-- Email -->
                            <div class="form-group">
                                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Email
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="bi bi-envelope-fill text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                                    </div>
                                    <input 
                                        id="email" 
                                        type="email" 
                                        class="w-full pl-9 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-300" 
                                        wire:model.lazy="email"
                                        placeholder="contoh @email.com">
                                </div>
                                @error('email') <p class="text-red-500 text-xs mt-1 flex items-center"><i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}</p> @enderror
                            </div>
                        </div>

                    {{-- Kolom Kanan --}}
                    <div class="space-y-6">
                        <h3 class="text-lg font-medium text-base-content border-b border-base-300 pb-2">Detail Laporan</h3>
                        <!-- Judul Laporan -->
                        <div class="form-group">
                            <label for="judul_laporan" class="block text-sm font-semibold text-gray-700 mb-2">
                                Judul Laporan <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="bi bi-chat-left-text-fill text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                                </div>
                                <input 
                                    id="judul_laporan" 
                                    type="text" 
                                    class="w-full pl-9 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-300" 
                                    wire:model.lazy="judul_laporan"
                                    placeholder="Masukkan judul laporan">
                            </div>
                            @error('judul_laporan') <p class="text-red-500 text-xs mt-1 flex items-center"><i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}</p> @enderror
                        </div>

                        <!-- Uraian Laporan -->
                        <div class="form-group">
                            <label for="uraian_laporan" class="block text-sm font-semibold text-gray-700 mb-2">
                                Uraian Laporan <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <textarea 
                                    id="uraian_laporan" 
                                    rows="5"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-300 resize-none" 
                                    wire:model.lazy="uraian_laporan"
                                    placeholder="Jelaskan secara rinci laporan Anda"></textarea>
                            </div>
                            @error('uraian_laporan') <p class="text-red-500 text-xs mt-1 flex items-center"><i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                                <label for="data_dukung" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Data Dukung
                                </label>
                                <div class="relative">
                                    <div class="flex items-center justify-center w-full">
                                        <label for="data_dukung" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition-all duration-200">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <i class="bi bi-cloud-arrow-up-fill text-4xl text-gray-400 mb-2"></i>
                                                <p class="mb-1 text-sm text-gray-600"><span class="font-semibold">Klik untuk upload</span> atau drag & drop</p>
                                                <p class="text-xs text-gray-500">DOC, PDF, ZIP (Max 1MB)</p>
                                            </div>
                                            <input id="data_dukung" type="file" class="hidden" wire:model="data_dukung" accept=".doc,.docx,.pdf,.zip" onchange="showPdfPreview(event)">
                                        </label>
                                    </div>
                                    <div wire:loading wire:target="data_dukung" class="mt-2 flex items-center text-sm text-indigo-600">
                                        <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Mengunggah file...
                                    </div>
                                </div>
                                @error('data_dukung') <p class="text-red-500 text-xs mt-1 flex items-center"><i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}</p> @enderror
                            </div>

                        <!-- PDF Preview Modal -->
                        <dialog id="pdf_preview_modal" class="modal">
                            <div class="modal-box w-11/12 max-w-5xl">
                                <h3 class="font-bold text-lg">Pratinjau PDF</h3>
                                <div class="py-4">
                                    <embed id="pdf-preview" src="" type="application/pdf" width="100%" height="600px" />
                                </div>
                                <div class="modal-action">
                                    <form method="dialog">
                                        <button class="btn">Tutup</button>
                                    </form>
                                </div>
                            </div>
                        </dialog>

                        
                    </div>
                </div>
                <div class="pt-6 border-t border-base-300 flex items-center justify-end gap-4">
                    <div wire:loading wire:target="save" class="text-sm text-gray-500">
                        Menyimpan...
                    </div>
                    <a href="{{ route('gratification') }}" class="btn btn-ghost">Kembali</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send-fill"></i> Kirim Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function showPdfPreview(event) {
        const [file] = event.target.files;
        if (file && file.type === 'application/pdf') {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('pdf-preview').src = e.target.result;
                document.getElementById('pdf_preview_modal').showModal();
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endpush