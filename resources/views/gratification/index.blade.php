<x-app-layout>
    <div class="container flex mx-auto px-4 mt-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
            <div class="lg:col-span-8">
                <div class="bg-white p-8 rounded-lg shadow-md w-full mb-16">
                    <h1 class="text-4xl font-bold">Pelaporan Gratifikasi</h1>
                    <hr class="my-8">
                    <p class="mb-4">
                        Salah satu kebiasaan yang berlaku umum di masyarakat adalah pemberian tanda terima kasih atas
                        jasa yang telah diberikan oleh petugas, baik dalam bentuk barang atau bahkan uang. Hal ini dapat
                        menjadi suatu kebiasaan yang bersifat negatif dan dapat mengarah menjadi potensi perbuatan
                        korupsi di kemudian hari. Potensi korupsi inilah yang berusaha dicegah oleh peraturan UU Tindak
                        Pidana Korupsi.
                    </p>
                    <p class="mb-4">
                        Oleh karena itu, berapapun nilai gratifikasi yang Anda terima, bila pemberian itu patut diduga
                        berkaitan dengan jabatan/kewenangan yang dimiliki, maka sebaiknya segera dilaporkan ke Unit
                        Pengelola Gratifikasi Balai Diklat Industri Yogyakarta untuk dianalisa lebih lanjut.
                    </p>
                    <h2 class="text-xl font-bold mb-2">Apa Saja Contoh-Contoh Kasus Gratifikasi yang Dilarang ?</h2>
                    <ul class="list-disc list-inside mb-4">
                        <li>Pemberian tiket perjalanan kepada pejabat atau keluarganya untuk keperluan pribadi secara
                            cuma-cuma.</li>
                        <li>Pemberian hadiah atau parsel kepada pejabat pada saat hari raya keagamaan oleh rekanan atau
                            bawahannya.</li>
                        <li>Hadiah atau sumbangan pada saat pernikahan anak dari pejabat oleh rekanan kantor pejabat
                            tersebut.</li>
                        <li>Pemberian potongan harga khusus bagi pejabat untuk pembelian barang dari rekanan.</li>
                        <li>Pemberian biaya atau ongkos naik haji dari rekanan kepada pejabat.</li>
                        <li>Pemberian hadiah ulang tahun atau pada acara-acara pribadi lainnya dari rekanan.</li>
                        <li>Pemberian hadiah atau souvenir kepada pejabat pada saat kunjungan kerja.</li>
                        <li>Pemberian hadiah atau uang sebagai ucapan terima kasih karena telah dibantu.</li>
                    </ul>
                    <h2 class="text-xl font-bold mb-2">Adapun sarana pelaporan yang disediakan adalah sebagai berikut.
                    </h2>
                    <div class="mb-4">
                        <p><span class="font-bold">Website</span> : <a
                                href="https://bdiyogyakarta.kemenperin.go.id/gratification/form"
                                class="text-blue-600">https://bdiyogyakarta.kemenperin.go.id/gratification/form</a></p>
                        <p><span class="font-bold">Surat</span> : Balai Diklat Industri Yogyakarta</p>
                        <p>Jalan Gedongkuning 140, Kotagede, Yogyakarta 55171</p>
                        <p><span class="font-bold">Telepon</span> : (0274) 373912</p>
                        <p><span class="font-bold">Faksmile</span> : (0274) 376048</p>
                    </div>
                    <p>Laporan dapat diakses lebih lanjut melalui <a
                            href="https://bdiyogyakarta.kemenperin.go.id/gratification/form" class="text-blue-600">Formulir
                            Pelaporan Gratifikasi Balai Diklat Industri Yogyakarta</a>.
                    </p>
                </div>
            </div>
            <div class="lg:col-span-4">
                @include('components.information-sidebar')
            </div>
        </div>
    </div>
</x-app-layout>
