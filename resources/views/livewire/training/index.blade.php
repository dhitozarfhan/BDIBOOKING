<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">
        {{-- Breadcrumbs --}}
        <nav class="text-sm breadcrumbs mb-4">
            <ul>
                <li><a href="{{ url('/') }}"><i class="bi bi-house-fill"></i></a></li>
                <li>Diklat 3-in-1</li>
            </ul>
        </nav>
    </div>

    <section class="bg-base-200">
        <div class="container max-w-7xl mx-auto px-4">
            <div class="card p-4">
                <div class="mb-5">
                    <h2 class="text-4xl font-bold">Diklat 3-in-1</h2>
                    <h4 class="text-2xl font-semibold mt-2 mb-4">Pelatihan, Sertifikasi, & Penempatan Kerja</h4>
                </div>
                <div class="border-t pt-4">
                    <div class="text-2xl leading-10">
                        Pelatihan yang dilakukan oleh Balai Diklat Industri (BDI) menggunakan sistem
                        <strong>3-in-1</strong>: pelatihan, sertifikasi kompetensi, dan penempatan kerja.
                        Kurikulum dan modul diranced mengacu pada kebutuhan industri untuk menciptakan
                        <em>link and match</em> yang kuat. Lulusan pelatihan yang kompeten dan siap kerja dijamin
                        melalui sertifikasi di akhir program. Untuk memfasilitasi ini, BDI telah membentuk
                        Tempat Uji Kompetensi (TUK) dan Lembaga Sertifikasi Profesi (LSP). Proses diakhiri
                        dengan penempatan kerja bagi lulusan, sesuai kesepakatan dengan mitra industri.
                        <br/><br/>
                        <a href="{{ route('register') }}" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5 mr-2">
                                <path d="M10 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM3.465 14.493a1.23 1.23 0 0 0 .41 1.412A9.957 9.957 0 0 0 10 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 0 0-13.074.003Z" />
                            </svg>
                            Daftar Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-12 bg-base-200">
        <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Pelatihan Industri Card --}}
                <div class="card bg-base-100 shadow-lg hover:shadow-xl transition-shadow duration-300 ease-in-out">
                    <div class="card-body items-start text-left">
                        <div class="mb-4 text-primary shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h2 class="card-title">Pelatihan Industri</h2>
                        <ul class="list-disc text-left self-start pl-5 space-y-2 text-base-content/80 mt-4">
                            <li>Jenis pelatihan sesuai kebutuhan industri.</li>
                            <li>Kurikulum mengacu pada standar kompetensi.</li>
                            <li>Didukung workshop dengan peralatan modern.</li>
                            <li>Pelaksanaan in-house maupun on-site.</li>
                        </ul>
                    </div>
                </div>

                {{-- Sertifikasi Kompetensi Card --}}
                <div class="card bg-base-100 shadow-lg hover:shadow-xl transition-shadow duration-300 ease-in-out">
                    <div class="card-body items-start text-left">
                        <div class="mb-4 text-primary shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h2 class="card-title">Sertifikasi Kompetensi</h2>
                        <ul class="list-disc text-left self-start pl-5 space-y-2 text-base-content/80 mt-4">
                            <li>Uji kompetensi di akhir pelatihan.</li>
                            <li>Tempat Uji Kompetensi (TUK) terstandarisasi.</li>
                            <li>Mendapatkan sertifikat kompetensi dan diklat.</li>
                        </ul>
                    </div>
                </div>

                {{-- Penempatan Kerja Card --}}
                <div class="card bg-base-100 shadow-lg hover:shadow-xl transition-shadow duration-300 ease-in-out">
                    <div class="card-body items-start text-left">
                        <div class="mb-4 text-primary shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v.01" />
                            </svg>
                        </div>
                        <h2 class="card-title">Penempatan Kerja</h2>
                        <ul class="list-disc text-left self-start pl-5 space-y-2 text-base-content/80 mt-4">
                            <li>MoU dengan berbagai perusahaan industri.</li>
                            <li>Jaminan penempatan kerja bagi seluruh lulusan.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>