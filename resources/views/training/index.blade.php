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
        <div class="grid grid-cols-1 gap-8 text-center md:grid-cols-3">
            <!-- Step 1: Pelatihan -->
            <div class="card relative bg-base-200 p-8 transform-gpu transition-all duration-300 hover:scale-105 hover:shadow-xl">
                <div class="absolute -left-4 -top-4 flex h-12 w-12 items-center justify-center rounded-full bg-primary text-xl font-bold text-primary-content">
                    <span>01</span>
                </div>
                <div class="mb-4 flex justify-center pt-8">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-12 w-12 text-primary">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0l-2.072-1.036A30.022 30.022 0 0112 3.493a30.019 30.019 0 0110.072 5.617l-2.072 1.036m-16.142 0c.217.025.435.048.654.071m15.482 0c.219-.023.437-.046.654-.071m-16.142 0a30.017 30.017 0 01-2.658-.814m16.142 0a30.017 30.017 0 00-2.658-.814m-10.832 0a60.436 60.436 0 00-2.658-.814" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold">Pelatihan Industri</h3>
                <p class="text-base-content/80 mt-2 leading-relaxed">Berbasis kompetensi sesuai kebutuhan industri.</p>
            </div>

            <!-- Step 2: Sertifikasi -->
            <div class="card relative bg-base-200 p-8 transform-gpu transition-all duration-300 hover:scale-105 hover:shadow-xl">
                <div class="absolute -left-4 -top-4 flex h-12 w-12 items-center justify-center rounded-full bg-primary text-xl font-bold text-primary-content">
                    <span>02</span>
                </div>
                <div class="mb-4 flex justify-center pt-8">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-12 w-12 text-primary">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.745 3.745 0 013.296-1.043A3.745 3.745 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.745 3.745 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold">Sertifikasi Kompetensi</h3>
                <p class="text-base-content/80 mt-2 leading-relaxed">Uji kompetensi oleh LSP untuk menjamin keahlian.</p>
            </div>

            <!-- Step 3: Penempatan -->
            <div class="card relative bg-base-200 p-8 transform-gpu transition-all duration-300 hover:scale-105 hover:shadow-xl">
                <div class="absolute -left-4 -top-4 flex h-12 w-12 items-center justify-center rounded-full bg-primary text-xl font-bold text-primary-content">
                    <span>03</span>
                </div>
                <div class="mb-4 flex justify-center pt-8">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-12 w-12 text-primary">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.07a2.25 2.25 0 01-2.25 2.25H5.998a2.25 2.25 0 01-2.25-2.25v-4.07a2.25 2.25 0 01.521-1.458l2.25-3.375a2.25 2.25 0 012.664-.642l1.32 1.32a2.25 2.25 0 002.828 0l1.32-1.32a2.25 2.25 0 012.664.642l2.25 3.375a2.25 2.25 0 01.521 1.458z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold">Penempatan Kerja</h3>
                <p class="text-base-content/80 mt-2 leading-relaxed">Jaminan penempatan di perusahaan mitra.</p>
            </div>
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