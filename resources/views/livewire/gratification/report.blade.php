<div class="p-4 sm:p-8 bg-base-100 shadow sm:rounded-lg">
    <div class="max-w-7xl mx-auto">
        <h2 class="text-2xl font-bold text-base-content">
            <i class="bi bi-bar-chart-line-fill mr-2"></i> Statistik Laporan Gratifikasi
        </h2>

        <div class="mt-6">
            <label for="tahun" class="label">
                <span class="label-text">Tahun</span>
            </label>
            <select id="tahun" class="select select-bordered w-full" wire:model="selectedYear" wire:change="updateReport">
                @for ($year = date('Y'); $year >= 2020; $year--)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endfor
            </select>
        </div>

        <!-- Grafik Jumlah Laporan per Bulan -->
        <div class="mt-8">
            <h3 class="text-lg font-medium text-base-content">Jumlah Laporan per Bulan ({{ $selectedYear }})</h3>
            <div class="mt-4 card bg-base-200 shadow-xl">
                <div class="card-body">
                    @if($reportCountData)
                        <div class="space-y-2">
                            @foreach($reportCountData as $month => $count)
                                <div class="flex items-center">
                                    <div class="w-24 text-sm">{{ date('F', mktime(0, 0, 0, $month, 1)) }}</div>
                                    <div class="flex-1 ml-4">
                                        <progress class="progress progress-primary w-full" value="{{ $count }}" max="{{ max($reportCountData) }}"></progress>
                                        <div class="text-xs text-base-content mt-1">{{ $count }} laporan</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-base-content">Data tidak tersedia</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Grafik Waktu Rata-rata Penyelesaian -->
        <div class="mt-8">
            <h3 class="text-lg font-medium text-base-content">Rata-rata Waktu Penyelesaian per Bulan ({{ $selectedYear }})</h3>
            <div class="mt-4 card bg-base-200 shadow-xl">
                <div class="card-body">
                    @if($timeToAnswerData)
                        <div class="space-y-2">
                            @foreach($timeToAnswerData as $month => $time)
                                <div class="flex items-center">
                                    <div class="w-24 text-sm">{{ date('F', mktime(0, 0, 0, $month, 1)) }}</div>
                                    <div class="flex-1 ml-4">
                                        <progress class="progress progress-accent w-full" value="{{ $time }}" max="{{ max($timeToAnswerData) }}"></progress>
                                        <div class="text-xs text-base-content mt-1">{{ $time }} hari</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-base-content">Data tidak tersedia</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Grafik Status Laporan -->
        <div class="mt-8">
            <h3 class="text-lg font-medium text-base-content">Distribusi Status Laporan ({{ $selectedYear }})</h3>
            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @if($statusData)
                    @foreach($statusData as $status)
                        <div class="card bg-base-200 shadow-xl">
                            <div class="card-body items-center text-center">
                                <h4 class="card-title">
                                    @if($status['status'] == 'I')
                                        <i class="bi bi-hourglass-split text-warning"></i> Inisiasi
                                    @elseif($status['status'] == 'P')
                                        <i class="bi bi-arrow-repeat text-info"></i> Proses
                                    @elseif($status['status'] == 'D')
                                        <i class="bi bi-cursor-fill text-primary"></i> Disposisi
                                    @elseif($status['status'] == 'T')
                                        <i class="bi bi-check-all text-success"></i> Selesai
                                    @else
                                        <i class="bi bi-question-circle-fill"></i> Status Tidak Dikenal
                                    @endif
                                </h4>
                                <p class="text-3xl font-bold">{{ $status['count'] }}</p>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-base-content">Data tidak tersedia</p>
                @endif
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('gratification') }}" class="btn btn-ghost">
                Kembali
            </a>
        </div>
    </div>
</div>