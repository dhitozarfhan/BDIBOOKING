<div>
    @if($currentView === 'index')
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-full">
                <h2 class="text-lg font-medium text-gray-900">
                    Pelaporan Gratifikasi
                </h2>

                <div class="mt-4 gratification-information">
                    <p>Salah satu kebiasaan yang berlaku umum di masyarakat adalah pemberian tanda terima kasih atas jasa yang telah diberikan oleh petugas, baik dalam bentuk barang atau bahkan uang. Hal ini dapat menjadi suatu kebiasaan yang bersifat negatif dan dapat mengarah menjadi potensi perbuatan korupsi di kemudian hari. Potensi korupsi inilah yang berusaha dicegah oleh peraturan UU Tindak Pidana Korupsi.</p>
                    <p class="mt-3">Oleh karena itu, berapapun nilai gratifikasi yang Anda terima, bila pemberian itu patut diduga berkaitan dengan jabatan/kewenangan yang dimiliki, maka sebaiknya segera dilaporkan ke Unit Pengelola Gratifikasi untuk dianalisa lebih lanjut.</p>
                    <h6 class="mt-4 font-bold">Apa Saja Contoh-Contoh Kasus Gratifikasi yang Dilarang ?</h6>
                    <ul class="list-disc pl-5 mt-2 space-y-1">
                        <li>Pemberian tiket perjalanan kepada pejabat atau keluarganya untuk keperluan pribadi secara cuma-cuma.</li>
                        <li>Pemberian hadiah atau parsel kepada pejabat pada saat hari raya keagamaan oleh rekanan atau bawahannya.</li>
                        <li>Hadiah atau sumbangan pada saat perkawinan anak dari pejabat oleh rekanan kantor pejabat tersebut.</li>
                        <li>Pemberian potongan harga khusus bagi pejabat untuk pembelian barang dari rekanan.</li>
                        <li>Pemberian biaya atau ongkos naik haji dari rekanan kepada pejabat.</li>
                        <li>Pemberian hadiah ulang tahun atau pada acara-acara pribadi lainnya dari rekanan.</li>
                        <li>Pemberian hadiah atau souvenir kepada pejabat pada saat kunjungan kerja</li>
                        <li>Pemberian hadiah atau uang sebagai ucapan terima kasih karena telah dibantu.</li>
                    </ul>
                    <h6 class="mt-4 font-bold">Adapun sarana pelaporan yang disediakan adalah sebagai berikut.</h6>
                    <table class="w-full mt-2 border-collapse border border-gray-300">
                        <tr class="border border-gray-300">
                            <td class="border border-gray-300 px-2 py-1">Website</td>
                            <td class="border border-gray-300 px-2 py-1">:</td>
                            <td class="border border-gray-300 px-2 py-1">
                                <a href="#" wire:click.prevent="setView('form')" class="text-blue-600 hover:underline">Formulir Pelaporan Gratifikasi</a>
                            </td>
                        </tr>
                        <tr class="border border-gray-300">
                            <td class="border border-gray-300 px-2 py-1">Surat</td>
                            <td class="border border-gray-300 px-2 py-1">:</td>
                            <td class="border border-gray-300 px-2 py-1">BDI Yogyakarta<br/>Jl. Babarsari No. 245, Yogyakarta</td>
                        </tr>
                        <tr class="border border-gray-300">
                            <td class="border border-gray-300 px-2 py-1">Telepon</td>
                            <td class="border border-gray-300 px-2 py-1">:</td>
                            <td class="border border-gray-300 px-2 py-1">0274-487711</td>
                        </tr>
                        <tr class="border border-gray-300">
                            <td class="border border-gray-300 px-2 py-1">Faksimile</td>
                            <td class="border border-gray-300 px-2 py-1">:</td>
                            <td class="border border-gray-300 px-2 py-1">0274-487711</td>
                        </tr>
                    </table>
                </div>

                <div class="mt-6">
                    <h3 class="text-md font-medium text-gray-900">Layanan Terkait</h3>
                    <div class="mt-2 flex flex-wrap gap-2">
                        <button wire:click="setView('form')" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Formulir Laporan
                        </button>
                        <button wire:click="setView('status')" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Status Laporan
                        </button>
                        <button wire:click="setView('report')" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Laporan Statistik
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @elseif($currentView === 'form')
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
    @elseif($currentView === 'status')
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
    @elseif($currentView === 'report')
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
                    <button type="button" wire:click="setView('index')" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Kembali
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>