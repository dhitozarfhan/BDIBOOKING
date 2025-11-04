<div class="p-4 sm:p-8 bg-base-100 shadow sm:rounded-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="{{ route('home') }}"><i class="bi bi-house-fill"></i></a></li>
                <li>Pelaporan Gratifikasi</li>
            </ul>
        </div>
        <h2 class="text-2xl font-bold text-base-content mt-4">
            <i class="bi bi-file-earmark-text-fill mr-2"></i> Pelaporan Gratifikasi
        </h2>

        <div class="mt-4 prose max-w-none">
            <p>Salah satu kebiasaan yang berlaku umum di masyarakat adalah pemberian tanda terima kasih atas jasa yang telah diberikan oleh petugas, baik dalam bentuk barang atau bahkan uang. Hal ini dapat menjadi suatu kebiasaan yang bersifat negatif dan dapat mengarah menjadi potensi perbuatan korupsi di kemudian hari. Potensi korupsi inilah yang berusaha dicegah oleh peraturan UU Tindak Pidana Korupsi.</p>
            <p>Oleh karena itu, berapapun nilai gratifikasi yang Anda terima, bila pemberian itu patut diduga berkaitan dengan jabatan/kewenangan yang dimiliki, maka sebaiknya segera dilaporkan ke Unit Pengelola Gratifikasi untuk dianalisa lebih lanjut.</p>
            <h6>Apa Saja Contoh-Contoh Kasus Gratifikasi yang Dilarang ?</h6>
            <ul>
                <li>Pemberian tiket perjalanan kepada pejabat atau keluarganya untuk keperluan pribadi secara cuma-cuma.</li>
                <li>Pemberian hadiah atau parsel kepada pejabat pada saat hari raya keagamaan oleh rekanan atau bawahannya.</li>
                <li>Hadiah atau sumbangan pada saat perkawinan anak dari pejabat oleh rekanan kantor pejabat tersebut.</li>
                <li>Pemberian potongan harga khusus bagi pejabat untuk pembelian barang dari rekanan.</li>
                <li>Pemberian biaya atau ongkos naik haji dari rekanan kepada pejabat.</li>
                <li>Pemberian hadiah ulang tahun atau pada acara-acara pribadi lainnya dari rekanan.</li>
                <li>Pemberian hadiah atau souvenir kepada pejabat pada saat kunjungan kerja</li>
                <li>Pemberian hadiah atau uang sebagai ucapan terima kasih karena telah dibantu.</li>
            </ul>
            <h6>Adapun sarana pelaporan yang disediakan adalah sebagai berikut.</h6>
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <tbody>
                        <tr>
                            <td>Website</td>
                            <td>:</td>
                            <td>
                                <a href="#" wire:click.prevent="setView('form')" class="link link-primary">Formulir Pelaporan Gratifikasi</a>
                            </td>
                        </tr>
                        <tr>
                            <td>Surat</td>
                            <td>:</td>
                            <td>BDI Yogyakarta<br/>Jl. Babarsari No. 245, Yogyakarta</td>
                        </tr>
                        <tr>
                            <td>Telepon</td>
                            <td>:</td>
                            <td>0274-487711</td>
                        </tr>
                        <tr>
                            <td>Faksimile</td>
                            <td>:</td>
                            <td>0274-487711</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            <h3 class="text-lg font-medium text-base-content">Layanan Terkait</h3>
            <div class="mt-2 flex flex-wrap gap-2">
                <a href="{{ route('gratification.form') }}" class="btn btn-primary">
                    <i class="bi bi-gift-fill"></i> Formulir Laporan
                </a>
                <a href="{{ route('gratification.status') }}" class="btn btn-info">
                    <i class="bi bi-check-circle-fill"></i> Status Laporan
                </a>
                <a href="{{ route('gratification.report') }}" class="btn btn-accent">
                    <i class="bi bi-bar-chart-fill"></i> Laporan Statistik
                </a>
            </div>
        </div>
    </div>
</div>