<div class="p-4 sm:p-8 bg-base-100 shadow sm:rounded-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="{{ route('home') }}"><i class="bi bi-house-fill"></i></a></li>
                <li><a href="{{ route('gratification') }}">Pelaporan Gratifikasi</a></li>
                <li>Status Laporan</li>
            </ul>
        </div>
        <h2 class="text-2xl font-bold text-base-content mt-4">
            <i class="bi bi-check-circle-fill mr-2"></i> Status Laporan Gratifikasi
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
                <a href="{{ route('gratification') }}" class="btn btn-ghost">
                    Kembali
                </a>
            </div>
        </form>

        @if($showReportDetail)
            <div class="mt-8 card bg-base-200 shadow-xl">
                <div class="card-body">
                    <h3 class="card-title">Detail Laporan</h3>
                    
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-base-content/80">Judul Laporan</p>
                            <p class="font-medium">{{ $reportDetail->judul_laporan }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-base-content/80">Nama Pelapor</p>
                            <p class="font-medium">{{ $reportDetail->nama_pelapor }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-base-content/80">Tanggal Laporan</p>
                            <p class="font-medium">{{ $reportDetail->created_at ? $reportDetail->created_at->format('d M Y H:i') : 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-base-content/80">Status</p>
                            <p class="font-medium">
                                @if($reportDetail->status == 'I')
                                    <span class="badge badge-warning">Inisiasi</span>
                                @elseif($reportDetail->status == 'P')
                                    <span class="badge badge-info">Proses</span>
                                @elseif($reportDetail->status == 'D')
                                    <span class="badge badge-primary">Disposisi</span>
                                @elseif($reportDetail->status == 'T')
                                    <span class="badge badge-success">Selesai</span>
                                @else
                                    <span class="badge badge-ghost">Tidak Dikenal</span>
                                @endif
                            </p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm text-base-content/80">Uraian Laporan</p>
                            <p class="font-medium">{{ $reportDetail->uraian_laporan }}</p>
                        </div>
                    </div>
                    
                    @if($reportDetail->status === 'T')
                        <div class="mt-4">
                            <p class="text-sm text-base-content/80">Jawaban</p>
                            <p class="font-medium">{{ $reportDetail->jawaban ?? 'Jawaban belum tersedia' }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @elseif($statusError)
            <div class="mt-4 alert alert-error shadow-lg">
                <div>
                    <i class="bi bi-x-circle-fill"></i>
                    <span>{{ $statusError }}</span>
                </div>
            </div>
        @endif
    </div>
</div>