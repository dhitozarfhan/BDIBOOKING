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
                        <div>
                            <label for="nama_pelapor" class="label">
                                <span class="label-text">Nama Pelapor <span class="text-red-500">*</span></span>
                            </label>
                            <div class="relative">
                                <i class="bi bi-person-fill absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input id="nama_pelapor" type="text" class="input input-bordered w-full pl-10" wire:model.lazy="nama_pelapor">
                            </div>
                            @error('nama_pelapor') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="nomor_identitas" class="label">
                                <span class="label-text">Nomor Identitas (KTP/SIM)</span>
                            </label>
                            <div class="relative">
                                <i class="bi bi-card-heading absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input id="nomor_identitas" type="text" class="input input-bordered w-full pl-10" wire:model.lazy="nomor_identitas">
                            </div>
                            @error('nomor_identitas') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="alamat" class="label">
                                <span class="label-text">Alamat</span>
                            </label>
                            <div class="relative">
                                <textarea id="alamat" class="textarea textarea-bordered w-full pl-10" wire:model.lazy="alamat"></textarea>
                            </div>
                            @error('alamat') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="pekerjaan" class="label">
                                <span class="label-text">Pekerjaan</span>
                            </label>
                            <div class="relative">
                                <input id="pekerjaan" type="text" class="input input-bordered w-full pl-10" wire:model.lazy="pekerjaan">
                            </div>
                            @error('pekerjaan') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="telepon" class="label">
                                <span class="label-text">Telepon</span>
                            </label>
                            <div class="relative">
                                <i class="bi bi-telephone-fill absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input id="telepon" type="text" class="input input-bordered w-full pl-10" wire:model.lazy="telepon">
                            </div>
                            @error('telepon') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="email" class="label">
                                <span class="label-text">Email</span>
                            </label>
                            <div class="relative">
                                <i class="bi bi-envelope-fill absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input id="email" type="email" class="input input-bordered w-full pl-10" wire:model.lazy="email">
                            </div>
                            @error('email') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Kolom Kanan --}}
                    <div class="space-y-6">
                        <h3 class="text-lg font-medium text-base-content border-b border-base-300 pb-2">Detail Laporan</h3>
                        <div>
                            <label for="judul_laporan" class="label">
                                <span class="label-text">Judul Laporan <span class="text-red-500">*</span></span>
                            </label>
                            <div class="relative">
                                <i class="bi bi-chat-left-text-fill absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input id="judul_laporan" type="text" class="input input-bordered w-full pl-10" wire:model.lazy="judul_laporan">
                            </div>
                            @error('judul_laporan') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="uraian_laporan" class="label">
                                <span class="label-text">Uraian Laporan <span class="text-red-500">*</span></span>
                            </label>
                            <div class="relative">
                                <textarea id="uraian_laporan" rows="5" class="textarea textarea-bordered w-full pl-10 lg:w-[567px] h-[231px] min-h-[231px]" wire:model.lazy="uraian_laporan"></textarea>
                            </div>
                            @error('uraian_laporan') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="data_dukung" class="label">
                                <span class="label-text">Data Dukung (doc/pdf/zip, max 1MB)</span>
                            </label>
                            <input id="data_dukung" type="file" class="file-input file-input-bordered w-full" wire:model="data_dukung">
                            <div wire:loading wire:target="data_dukung" class="text-sm text-gray-500 mt-1">Mengunggah...</div>
                            @error('data_dukung') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="label">
                                <span class="label-text">Verifikasi <span class="text-red-500">*</span></span>
                            </label>
                            <div class="mt-1" wire:ignore>
                                <div id="recaptcha-container"></div>
                            </div>
                            @error('gRecaptchaResponse') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </form>
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
    </div>
</div>

@push('scripts')
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
<script type="text/javascript">
    var onloadCallback = function() {
        grecaptcha.render('recaptcha-container', {
            'sitekey' : '{{ config("captcha.sitekey") }}',
            'callback' : function(response) {
                @this.set('gRecaptchaResponse', response);
            }
        });
    };
</script>
@endpush