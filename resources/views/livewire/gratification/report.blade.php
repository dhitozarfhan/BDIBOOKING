<div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
    <div class="max-w-full">
        <h2 class="text-lg font-medium text-gray-900">
            Statistik Laporan Gratifikasi
        </h2>

        <div class="mt-6">
            <label for="tahun" class="block font-medium text-sm text-gray-700">Tahun</label>
            <select id="tahun" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" wire:model="selectedYear" wire:change="updateReport">
                @for ($year = date('Y'); $year >= 2020; $year--)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endfor
            </select>
        </div>

        <!-- Grafik Jumlah Laporan per Bulan -->
        <div class="mt-8">
            <h3 class="text-md font-medium text-gray-900">Jumlah Laporan per Bulan ({{ $selectedYear }})</h3>
            <div class="mt-4 bg-gray-50 p-4 rounded-lg border border-gray-200">
                <!-- Di sini akan ditampilkan grafik jumlah laporan per bulan -->
                @if($reportCountData)
                    <div class="space-y-2">
                        @foreach($reportCountData as $month => $count)
                            <div class="flex items-center">
                                <div class="w-16 text-sm text-gray-600">{{ date('F', mktime(0, 0, 0, $month, 1)) }}</div>
                                <div class="flex-1 ml-4">
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ ($count / max($reportCountData)) * 100 }}%"></div>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $count }} laporan</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-600">Data tidak tersedia</p>
                @endif
            </div>
        </div>

        <!-- Grafik Waktu Rata-rata Penyelesaian -->
        <div class="mt-8">
            <h3 class="text-md font-medium text-gray-900">Rata-rata Waktu Penyelesaian per Bulan ({{ $selectedYear }})</h3>
            <div class="mt-4 bg-gray-50 p-4 rounded-lg border border-gray-200">
                @if($timeToAnswerData)
                    <div class="space-y-2">
                        @foreach($timeToAnswerData as $month => $time)
                            <div class="flex items-center">
                                <div class="w-16 text-sm text-gray-600">{{ date('F', mktime(0, 0, 0, $month, 1)) }}</div>
                                <div class="flex-1 ml-4">
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ min(($time / max($timeToAnswerData)) * 100, 100) }}%"></div>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $time }} hari</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-600">Data tidak tersedia</p>
                @endif
            </div>
        </div>

        <!-- Grafik Status Laporan -->
        <div class="mt-8">
            <h3 class="text-md font-medium text-gray-900">Distribusi Status Laporan ({{ $selectedYear }})</h3>
            <div class="mt-4 bg-gray-50 p-4 rounded-lg border border-gray-200">
                @if($statusData)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach($statusData as $status)
                            <div class="bg-white p-4 rounded-lg border border-gray-200">
                                <div class="text-sm text-gray-600">
                                    @if($status['status'] == 'I')
                                        Inisiasi
                                    @elseif($status['status'] == 'P')
                                        Proses
                                    @elseif($status['status'] == 'D')
                                        Disposisi
                                    @elseif($status['status'] == 'T')
                                        Selesai
                                    @else
                                        Status Tidak Dikenal
                                    @endif
                                </div>
                                <div class="text-2xl font-bold mt-1">{{ $status['count'] }}</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-600">Data tidak tersedia</p>
                @endif
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('gratification') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Kembali
            </a>
        </div>
    </div>
</div>