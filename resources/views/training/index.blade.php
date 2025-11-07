<x-app-layout>
    <div class="space-y-16">
        {{-- Hero Section --}}
        <div class="hero min-h-[60vh] bg-base-200">
            <div class="hero-content text-center">
                <div class="max-w-7xl">
                    <h1 class="text-5xl font-bold">Diklat 3-in-1</h1>
                    <h2 class="text-2xl font-semibold mt-2 mb-4">Pelatihan, Sertifikasi, & Penempatan Kerja</h2>
                    <p class="py-6 text-justify">
                        Pelatihan yang dilakukan oleh Balai Diklat Industri (BDI) menggunakan sistem
                        <strong>3-in-1</strong>: pelatihan, sertifikasi kompetensi, dan penempatan kerja.
                        Kurikulum dan modul dirancang mengacu pada kebutuhan industri untuk menciptakan
                        <em>link and match</em> yang kuat. Lulusan pelatihan yang kompeten dan siap kerja dijamin
                        melalui sertifikasi di akhir program. Untuk memfasilitasi ini, BDI telah membentuk
                        Tempat Uji Kompetensi (TUK) dan Lembaga Sertifikasi Profesi (LSP). Proses diakhiri
                        dengan penempatan kerja bagi lulusan, sesuai kesepakatan dengan mitra industri.
                    </p>
                    <a href="{{ route('register') }}" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5 mr-2">
                            <path d="M10 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM3.465 14.493a1.23 1.23 0 0 0 .41 1.412A9.957 9.957 0 0 0 10 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 0 0-13.074.003Z" />
                        </svg>
                        Daftar Sekarang
                    </a>
                </div>
            </div>
        </div>

        {{-- Steps Section --}}
        <section class="py-20 bg-base-100">
            <div class="container max-w-7xl mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-bold">Proses 3-in-1</h2>
                    <p class="text-lg mt-2 text-base-content/80">Tiga langkah utama untuk memastikan lulusan siap kerja.</p>
                </div>
                <div class="flex justify-center">
                    <ul class="steps steps-vertical lg:steps-horizontal">
                        <li class="step step-primary">
                            <div class="step-content text-left lg:text-center lg:w-64">
                                <h4 class="font-bold text-lg">Pelatihan Industri</h4>
                                <p class="text-sm">Berbasis kompetensi sesuai kebutuhan industri.</p>
                            </div>
                        </li>
                        <li class="step step-primary">
                            <div class="step-content text-left lg:text-center lg:w-64">
                                <h4 class="font-bold text-lg">Sertifikasi Kompetensi</h4>
                                <p class="text-sm">Uji kompetensi oleh LSP untuk menjamin keahlian.</p>
                            </div>
                        </li>
                        <li class="step step-primary">
                            <div class="step-content text-left lg:text-center lg:w-64">
                                <h4 class="font-bold text-lg">Penempatan Kerja</h4>
                                <p class="text-sm">Jaminan penempatan di perusahaan mitra.</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        {{-- Detailed Cards Section --}}
        <section class="py-20 bg-base-200">
            <div class="container max-w-7xl mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    {{-- Card 1: Pelatihan --}}
                    <div class="card bg-base-100 shadow-xl transform-gpu transition-all duration-300 hover:-translate-y-2">
                        <div class="card-body items-center text-center">
                            <div class="p-4 bg-primary rounded-full">
                               <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary-content" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h2 class="card-title mt-4">Pelatihan Industri</h2>
                            <ul class="list-disc text-left p-4 text-base-content/80">
                                <li>Jenis pelatihan sesuai kebutuhan industri.</li>
                                <li>Kurikulum mengacu pada standar kompetensi.</li>
                                <li>Didukung workshop dengan peralatan modern.</li>
                                <li>Pelaksanaan in-house maupun on-site.</li>
                            </ul>
                        </div>
                    </div>

                    {{-- Card 2: Sertifikasi --}}
                    <div class="card bg-base-100 shadow-xl transform-gpu transition-all duration-300 hover:-translate-y-2">
                        <div class="card-body items-center text-center">
                            <div class="p-4 bg-primary rounded-full">
                                 <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary-content" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h2 class="card-title mt-4">Sertifikasi Kompetensi</h2>
                            <ul class="list-disc text-left p-4 text-base-content/80">
                                <li>Uji kompetensi di akhir pelatihan.</li>
                                <li>Tempat Uji Kompetensi (TUK) terstandarisasi.</li>
                                <li>Mendapatkan sertifikat kompetensi dan diklat.</li>
                            </ul>
                        </div>
                    </div>

                    {{-- Card 3: Penempatan --}}
                    <div class="card bg-base-100 shadow-xl transform-gpu transition-all duration-300 hover:-translate-y-2">
                        <div class="card-body items-center text-center">
                            <div class="p-4 bg-primary rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary-content" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v.01" />
                                </svg>
                            </div>
                            <h2 class="card-title mt-4">Penempatan Kerja</h2>
                            <ul class="list-disc text-left p-4 text-base-content/80">
                                <li>MoU dengan berbagai perusahaan industri.</li>
                                <li>Jaminan penempatan kerja bagi seluruh lulusan.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>