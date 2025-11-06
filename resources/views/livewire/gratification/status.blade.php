@section('title', 'Status Laporan Gratifikasi')
<<div class="p-4 sm:p-8 bg-base-100 shadow sm:rounded-lg" style="min-height: 43.5vh;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="{{ route('home') }}"><i class="bi bi-house-fill"></i></a></li>
                <li><a href="{{ route('gratification') }}">Pelaporan Gratifikasi</a></li>
                <li>Status Laporan</li>
            </ul>
        </div>
        <h2 class="text-2xl font-bold text-base-content mt-4">
            Status Laporan Gratifikasi
        </h2>
        <p class="mt-2 text-base-content/80">
            Masukkan kode register untuk melihat status laporan Anda.
        </p>
        <form wire:submit.prevent="checkStatus" class="mt-6 space-y-6">
            <div class="space-y-4">
                <div>
                    <label for="kode_register" class="label">
                        <span class="label-text">Kode Register <span class="text-red-500">*</span></span>
                    </label>
                    <div class="relative">
                        <i class="bi bi-upc-scan absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input id="kode_register" type="text" class="input input-bordered w-full pl-10" wire:model.lazy="kode_register">
                    </div>
                    @error('kode_register') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="flex items-center gap-4 mt-6">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Cek Status
                </button>
                <a href="{{ route('gratification') }}" class="btn btn-ghost btn-outline" wire:navigate>
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>