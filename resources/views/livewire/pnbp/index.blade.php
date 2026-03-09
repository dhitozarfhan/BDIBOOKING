<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">
        {{-- Breadcrumbs --}}
        <nav class="text-sm breadcrumbs">
            <ul>
                <li><a href="{{ url('/') }}"><i class="bi bi-house-fill"></i></a></li>
                <li>Pelayanan PNBP</li>
            </ul>
        </nav>
    </div>

    <section class="bg-base-100">
        <div class="container max-w-7xl mx-auto px-4">
            <div class="card" style="padding: 1rem;">
                <div>
                    <h2 class="text-4xl font-bold">Pelayanan PNBP</h2>
                    <h4 class="text-2xl font-semibold mt-2 mb-4">Pelayanan Berbayar untuk Umum</h4>
                </div>
                <div class="pt-2">
                    <div class="text-2xm leading-10">
                        BDI Yogyakarta menyelenggarakan pelayanan berbasis PNBP (Penerimaan Negara Bukan Pajak) yang
                        terbuka untuk masyarakat umum. Pelayanan ini dirancang untuk meningkatkan kompetensi
                        di berbagai bidang industri kreatif, digital, dan manajemen.
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Two Card Navigation --}}
    <section class="py-12 bg-white">
        <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Card Pelatihan --}}
                <a href="{{ route('pnbp.pelatihan') }}"
                   class="card border-2 border-base-300 bg-base-100 transition-all duration-300 hover:shadow-xl hover:border-primary hover:bg-primary/5 group">
                    <div class="card-body items-center text-center justify-center min-h-[280px] py-10">
                        <div class="w-20 h-20 rounded-full flex items-center justify-center mb-4 bg-base-200 text-base-content group-hover:bg-primary group-hover:text-white transition-all duration-300">
                            <i class="bi bi-mortarboard-fill text-4xl"></i>
                        </div>
                        <h2 class="card-title text-2xl font-bold group-hover:text-primary transition-colors duration-300">Pelatihan</h2>
                        <p class="text-sm text-gray-500 mt-2">Daftar pelatihan dan seminar yang tersedia untuk meningkatkan kompetensi Anda.</p>
                    </div>
                </a>

                {{-- Card Properti --}}
                <a href="{{ route('pnbp.properti') }}"
                   class="card border-2 border-base-300 bg-base-100 transition-all duration-300 hover:shadow-xl hover:border-secondary hover:bg-secondary/5 group">
                    <div class="card-body items-center text-center justify-center min-h-[280px] py-10">
                        <div class="w-20 h-20 rounded-full flex items-center justify-center mb-4 bg-base-200 text-base-content group-hover:bg-secondary group-hover:text-white transition-all duration-300">
                            <i class="bi bi-building-fill text-4xl"></i>
                        </div>
                        <h2 class="card-title text-2xl font-bold group-hover:text-secondary transition-colors duration-300">Properti</h2>
                        <p class="text-sm text-gray-500 mt-2">Sewa properti dan fasilitas yang tersedia untuk kebutuhan kegiatan Anda.</p>
                    </div>
                </a>
            </div>
        </div>
    </section>
</div>
