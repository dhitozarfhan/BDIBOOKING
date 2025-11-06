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

        <br>

        <!-- Alert Messages -->
        @if (session()->has('message'))
            <div class="mb-6 alert alert-success shadow-lg animate-fade-in">
                <div class="flex items-start w-full">
                    <div class="flex-shrink-0">
                        <i class="bi bi-check-circle-fill text-2xl"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="font-bold text-lg">Berhasil!</h3>
                        <p class="mt-1">
                            {{ session('message') }} 
                            <span class="badge badge-success badge-lg ml-2 font-semibold">
                                {{ session('kode_register') }}
                            </span>
                        </p>
                        <p class="text-sm mt-2 opacity-90">Terima kasih atas partisipasi Anda dalam menjaga integritas.</p>
                    </div>
                </div>
            </div>
        @else
            <div class="mb-6 alert alert-info shadow-lg">
                <div class="flex items-start w-full">
                    <div class="flex-shrink-0">
                        <i class="bi bi-info-circle-fill text-2xl"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <h4 class="font-bold text-base mb-2">Informasi Penting</h4>
                        <p class="text-sm leading-relaxed">
                            Setiap laporan akan dijaga kerahasiannya dan dalam hal terdapat bukti yang cukup akan ditindaklanjuti pada proses investigasi selanjutnya. Keberadaan pelaporan gratifikasi menciptakan sistem saling mengawasi terhadap etika, kesesuaian perilaku dan ketaatan prosedur kerja.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form Container -->
        <form wire:submit.prevent="save">
            <div class="flex flex-col lg:flex-row gap-8 mb-8">
                <!-- Kolom Kiri - Informasi Pelapor -->
                <div class="lg:w-1/2 space-y-6">
                    <div class="flex items-center space-x-3 mb-6 pb-2 border-primary">
                        <h4 class="text-xl font-bold text-base-content">Informasi Pelapor</h4>
                    </div>

                    <!-- Nama Pelapor -->
                    <div class="form-control">
                        <label for="nama_pelapor" class="label">
                            <span class="label-text font-semibold">Nama Pelapor <span class="text-error">*</span></span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="bi bi-person-fill text-base-content/40 group-focus-within:text-primary transition-colors"></i>
                            </div>
                            <input 
                                id="nama_pelapor" 
                                type="text" 
                                class="input input-bordered w-full pl-11 focus:input-primary transition-all duration-200" 
                                wire:model.lazy="nama_pelapor"
                                placeholder="Masukkan nama lengkap">
                        </div>
                        @error('nama_pelapor') 
                        <label class="label">
                            <span class="label-text-alt text-error flex items-center">
                                <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                            </span>
                        </label>
                        @enderror
                    </div>

                    <!-- Nomor Identitas -->
                    <div class="form-control">
                        <label for="nomor_identitas" class="label">
                            <span class="label-text font-semibold">Nomor Identitas (KTP/SIM)</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="bi bi-card-heading text-base-content/40 group-focus-within:text-primary transition-colors"></i>
                            </div>
                            <input 
                                id="nomor_identitas" 
                                type="text" 
                                class="input input-bordered w-full pl-11 focus:input-primary transition-all duration-200" 
                                wire:model.lazy="nomor_identitas"
                                placeholder="Contoh: 3174012345678901">
                        </div>
                        @error('nomor_identitas') 
                        <label class="label">
                            <span class="label-text-alt text-error flex items-center">
                                <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                            </span>
                        </label>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div class="form-control">
                        <label for="alamat" class="label">
                            <span class="label-text font-semibold">Alamat</span>
                        </label>
                        <textarea 
                            id="alamat" 
                            rows="3"
                            class="textarea textarea-bordered w-full focus:textarea-primary transition-all duration-200 resize-none" 
                            wire:model.lazy="alamat"
                            placeholder="Masukkan alamat lengkap"></textarea>
                        @error('alamat') 
                        <label class="label">
                            <span class="label-text-alt text-error flex items-center">
                                <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                            </span>
                        </label>
                        @enderror
                    </div>

                    <!-- Pekerjaan -->
                    <div class="form-control">
                        <label for="pekerjaan" class="label">
                            <span class="label-text font-semibold">Pekerjaan</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="bi bi-briefcase-fill text-base-content/40 group-focus-within:text-primary transition-colors"></i>
                            </div>
                            <input 
                                id="pekerjaan" 
                                type="text" 
                                class="input input-bordered w-full pl-11 focus:input-primary transition-all duration-200" 
                                wire:model.lazy="pekerjaan"
                                placeholder="Contoh: Pegawai Negeri Sipil">
                        </div>
                        @error('pekerjaan') 
                        <label class="label">
                            <span class="label-text-alt text-error flex items-center">
                                <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                            </span>
                        </label>
                        @enderror
                    </div>

                    <!-- Telepon -->
                    <div class="form-control">
                        <label for="telepon" class="label">
                            <span class="label-text font-semibold">Telepon</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="bi bi-telephone-fill text-base-content/40 group-focus-within:text-primary transition-colors"></i>
                            </div>
                            <input 
                                id="telepon" 
                                type="text" 
                                class="input input-bordered w-full pl-11 focus:input-primary transition-all duration-200" 
                                wire:model.lazy="telepon"
                                placeholder="Contoh: 08123456789">
                        </div>
                        @error('telepon') 
                        <label class="label">
                            <span class="label-text-alt text-error flex items-center">
                                <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                            </span>
                        </label>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="form-control">
                        <label for="email" class="label">
                            <span class="label-text font-semibold">Email</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="bi bi-envelope-fill text-base-content/40 group-focus-within:text-primary transition-colors"></i>
                            </div>
                            <input 
                                id="email" 
                                type="email" 
                                class="input input-bordered w-full pl-11 focus:input-primary transition-all duration-200" 
                                wire:model.lazy="email"
                                placeholder="contoh@email.com">
                        </div>
                        @error('email') 
                        <label class="label">
                            <span class="label-text-alt text-error flex items-center">
                                <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                            </span>
                        </label>
                        @enderror
                    </div>
                </div>

                <!-- Kolom Kanan - Detail Laporan -->
                <div class="lg:w-1/2 space-y-6">
                    <div class="flex items-center space-x-3 mb-6 pb-2 border-secondary">
                        <h4 class="text-xl font-bold text-base-content">Detail Laporan</h4>
                    </div>

                    <!-- Judul Laporan -->
                    <div class="form-control">
                        <label for="judul_laporan" class="label">
                            <span class="label-text font-semibold">Judul Laporan <span class="text-error">*</span></span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="bi bi-chat-left-text-fill text-base-content/40 group-focus-within:text-secondary transition-colors"></i>
                            </div>
                            <input 
                                id="judul_laporan" 
                                type="text" 
                                class="input input-bordered w-full pl-11 focus:input-secondary transition-all duration-200" 
                                wire:model.lazy="judul_laporan"
                                placeholder="Ringkasan singkat dari laporan Anda">
                        </div>
                        @error('judul_laporan') 
                        <label class="label">
                            <span class="label-text-alt text-error flex items-center">
                                <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                            </span>
                        </label>
                        @enderror
                    </div>

                    <!-- Uraian Laporan -->
                    <div class="form-control">
                        <label for="uraian_laporan" class="label">
                            <span class="label-text font-semibold">Uraian Laporan <span class="text-error">*</span></span>
                        </label>
                        <textarea 
                            id="uraian_laporan" 
                            rows="10"
                            class="textarea textarea-bordered w-full focus:textarea-secondary transition-all duration-200 resize-none" 
                            wire:model.lazy="uraian_laporan"
                            placeholder="Jelaskan secara detail kronologi kejadian, siapa yang terlibat, kapan dan di mana kejadian berlangsung..."></textarea>
                        <label class="label">
                            <span class="label-text-alt text-base-content/60">Sertakan informasi sejelas mungkin untuk mempermudah proses investigasi</span>
                        </label>
                        @error('uraian_laporan') 
                        <label class="label">
                            <span class="label-text-alt text-error flex items-center">
                                <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                            </span>
                        </label>
                        @enderror
                    </div>

                    <!-- Data Dukung -->
                    <div class="form-control">
                        <label for="data_dukung" class="label">
                            <span class="label-text font-semibold">Data Dukung</span>
                        </label>
                        <div class="relative">
                            @if ($data_dukung)
                                <div class="flex items-center justify-center w-full h-32 border-2 border-dashed border-success rounded-xl bg-base-200/50">
                                    <div class="text-center p-4">
                                        <i class="bi bi-file-earmark-check-fill text-4xl text-success mb-2"></i>
                                        <p class="text-sm font-semibold text-base-content truncate" title="{{ $data_dukung->getClientOriginalName() }}">{{ $data_dukung->getClientOriginalName() }}</p>
                                        <p class="text-xs text-base-content/60">({{ round($data_dukung->getSize() / 1024) }} KB)</p>
                                        <button wire:click="$set('data_dukung', null)" type="button" class="btn btn-xs btn-ghost text-error mt-2">
                                            <i class="bi bi-x-lg"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center justify-center w-full">
                                    <label for="data_dukung" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-base-300 rounded-xl cursor-pointer bg-base-200/50 hover:bg-base-200 transition-all duration-200">
                                        <div wire:loading.remove wire:target="data_dukung" class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <i class="bi bi-cloud-arrow-up-fill text-4xl text-base-content/40 mb-2"></i>
                                            <p class="mb-1 text-sm text-base-content/80"><span class="font-semibold">Klik untuk upload</span> atau drag & drop</p>
                                            <p class="text-xs text-base-content/60">DOC, PDF, ZIP (Max 1MB)</p>
                                        </div>
                                        <div wire:loading wire:target="data_dukung" class="w-full h-full flex flex-col items-center justify-center">
                                            <span class="loading loading-spinner loading-lg text-primary"></span>
                                            <p class="mt-2 text-sm text-primary">Mengunggah...</p>
                                        </div>
                                        <input id="data_dukung" type="file" class="hidden" wire:model="data_dukung" accept=".doc,.docx,.pdf,.zip">
                                    </label>
                                </div>
                            @endif
                        </div>
                        @error('data_dukung') 
                        <label class="label">
                            <span class="label-text-alt text-error flex items-center">
                                <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                            </span>
                        </label>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Security Notice & Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t-2 border-base-300">
                <div class="flex items-center text-sm text-base-content/70">
                    <i class="bi bi-shield-lock-fill text-success text-lg mr-2"></i>
                    <span>Laporan Anda akan dijaga kerahasiaannya</span>
                </div>
                <div class="flex items-center gap-3">
                    <div wire:loading wire:target="save" class="flex items-center text-sm text-secondary">
                        <span class="loading loading-spinner loading-sm mr-2"></span>
                        Menyimpan laporan...
                    </div>
                    <a href="{{ route('gratification') }}" class="btn btn-ghost btn-outline" wire:navigate>
                        Kembali
                    </a>
                    <button type="submit" class="btn btn-primary shadow-lg hover:shadow-xl transition-all duration-200">
                        Kirim Laporan
                    </button>
                </div>
            </div>
        </form>

        <!-- Additional Info -->
        <div class="mt-6 text-center text-sm text-base-content/60">
            <p>Butuh bantuan? Hubungi kami di <a href="mailto:support@bdiyogyakarta.kemenperin.go.id" class="link link-primary font-medium">support@bdiyogyakarta.kemenperin.go.id</a></p>
        </div>
    </div>
</div>

@push('scripts')
<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in {
        animation: fade-in 0.5s ease-out;
    }

    /* Custom focus styles untuk dark mode */
    .input:focus, .textarea:focus, .select:focus {
        outline: 2px solid hsl(var(--p));
        outline-offset: 2px;
    }

    /* Smooth transitions untuk theme switching */
    * {
        transition-property: background-color, border-color, color, fill, stroke;
        transition-duration: 200ms;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Override transition untuk form inputs */
    .input, .textarea, .select, .btn {
        transition-property: background-color, border-color, color, box-shadow, transform;
    }
</style>
@endpush    