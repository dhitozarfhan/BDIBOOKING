<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <?php
    $breadcrumbs = [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Ibiza'],
    ];
    ?>

    @if (!empty($breadcrumbs))
        <nav aria-label="breadcrumb">
            <ol class="flex items-center space-x-2 text-sm text-base-content/80">
                @foreach ($breadcrumbs as $item)
                    <li>
                        @if ($loop->first)
                            <a wire:navigate href="{{ $item['url'] }}" class="hover:underline hover:text-primary">
                                <i class="bi bi-house-fill"></i>
                            </a>
                        @elseif (!isset($item['url']) || $loop->last)
                            <span class="font-medium text-base-content">
                                {{ $item['label'] }}
                            </span>
                        @else
                            <a wire:navigate href="{{ $item['url'] }}" class="hover:underline hover:text-primary">
                                {{ $item['label'] }}
                            </a>
                        @endif
                    </li>
                    @if (!$loop->last)
                        <li>
                            <i class="bi bi-chevron-right text-xs"></i>
                        </li>
                    @endif
                @endforeach
            </ol>
        </nav>
    @endif

    <div class="space-y-16 sm:space-y-24 mt-10">
        <!-- Hero Section -->
        <section>
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-base-content sm:text-6xl">Inkubator Bisnis Pazti Bisa (Ibiza)</h1>
            <p class="mt-6 text-lg leading-8 text-base-content/80">Menjadi wirausaha tangguh di industri plastik, kerajinan, dan produk kulit.</p>
            <div class="mt-10 flex items-center gap-x-6">
                <a href="#" class="btn btn-primary btn-lg shadow-lg hover:shadow-xl transition-shadow">
                    <i class="bi bi-rocket-takeoff-fill mr-2"></i>
                    Daftar Sekarang
                </a>
            </div>
        </section>

        <!-- Description Section -->
        <section class="bg-base-200/50 rounded-2xl mt-12 md:p-12">
            <div class="mx-auto max-w-7xl">
                <div class="grid grid-cols-1 gap-x-8 gap-y-16 sm:gap-y-20 lg:grid-cols-2 lg:items-start">
                    <div class="lg:pr-4">
                        <div class="lg:max-w-lg mx-auto text-center">
                            <h2 class="text-base font-semibold leading-7 text-primary">Tentang Program</h2>
                            <p class="mt-2 text-3xl font-bold tracking-tight text-base-content sm:text-4xl">Membentuk Wirausaha Industri Unggul</p>
                            <p class="mt-6 text-lg leading-8 text-base-content/80">
                                Balai Diklat Industri Yogyakarta mengambil semangat bahwa Inkubator Bisnis Pazti Bisa (Ibiza) agar para tenant memiliki jiwa optimis dalam mengembangkan usaha barunya.
                            </p>
                        </div>
                        <div class="mt-2 border-t border-base-300 pt-8">
                            <h3 class="text-lg font-semibold text-base-content">Fokus Bidang Industri</h3>
                            <div class="mt-4 flex flex-wrap gap-4">
                                <span class="badge badge-lg badge-success badge-outline"><i class="bi bi-box-fill mr-2"></i> Plastik</span>
                                <span class="badge badge-lg badge-info badge-outline"><i class="bi bi-gem mr-2"></i> Kerajinan Kulit</span>
                                <span class="badge badge-lg badge-error badge-outline"><i class="bi bi-handbag-fill mr-2"></i> Produk Kulit</span>
                            </div>
                        </div>
                    </div>


                    <div class="lg:pl-4 text-base-content/80 space-y-6 text-base leading-7">
                        <div>
                            <h3 class="font-semibold text-base-content mt-4">Landasan Program</h3>
                            <p class="mt-2">
                                Program pengembangan wirausaha nasional telah menjadi salah satu program utama pemerintah sebagaimana diamanatkan dalam <b>Perpres No. 27 Tahun 2013</b> dalam penyelenggaraan kegiatan inkubator bisnis.
                            </p>
                            <p class="mt-4">
                                <b>Undang-undang No. 3 Tahun 2014</b> tentang Perindustrian menyebutkan bahwa pembinaan wirausaha industri juga mencakup pembinaan wirausaha yang bertujuan untuk menciptakan wirausaha yang berkarakter, bermental kewirausahaan, serta berkompetensi di bidang usahanya.
                            </p>
                        </div>
                        <div class="border-t border-base-300 pt-6">
                            <h3 class="font-semibold text-base-content">Mengapa Inkubator?</h3>
                            <p class="mt-2">
                                Bisnis pada tahap pemula (<em>start up</em>) menghadapi risiko kegagalan yang tinggi. Inkubator hadir sebagai infrastruktur untuk memperkecil risiko tersebut dengan menyediakan fasilitas dan aktivitas bagi pengusaha pemula (tenant).
                            </p>
                            <p class="mt-4">
                                Fasilitas ini mencakup tempat usaha, fasilitas produksi, pelatihan, akses teknologi, tenaga kerja, modal, dan pasar. Interaksi antar tenant menciptakan lingkungan yang kondusif untuk tumbuhnya usaha baru.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section>
            <div class="mx-auto max-w-7xl mt-12">
                <div class="text-center max-w-2xl mx-auto">
                    <h2 class="text-3xl font-bold tracking-tight text-base-content sm:text-4xl">Layanan Inkubasi</h2>
                    <p class="mt-4 text-lg leading-8 text-base-content/80">
                        Para tenant akan mendapatkan serangkaian layanan komprehensif untuk mengakselerasi pertumbuhan bisnis.
                    </p>
                </div>
                <div class="mt-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Card 1 - Pengembangan Produk -->
                    <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-shadow h-full border border-transparent hover:border-success/50">
                        <div class="card-body p-8">
                            <div class="w-16 h-16 flex items-center justify-center rounded-2xl bg-success/10 text-success mb-6">
                                <i class="bi bi-tools text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-4 text-base-content">Pengembangan Produk</h3>
                            <p class="text-base-content/70 leading-relaxed mb-4">
                                Bimbingan teknis, manajerial, konseling tim, penjadwalan project, dan evaluasi capaian Business Model Canvas.
                            </p>
                            <div class="divider my-2"></div>
                            <ul class="space-y-2 text-base-content/90">
                                <li class="flex items-center"><i class="bi bi-check-circle-fill mr-3 text-success"></i> Product Development</li>
                                <li class="flex items-center"><i class="bi bi-check-circle-fill mr-3 text-success"></i> Product Simulation</li>
                                <li class="flex items-center"><i class="bi bi-check-circle-fill mr-3 text-success"></i> Evaluasi Bulanan & Akhir</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Card 2 - Pengembangan Pasar -->
                    <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-shadow h-full border border-transparent hover:border-info/50">
                        <div class="card-body p-8">
                            <div class="w-16 h-16 flex items-center justify-center rounded-2xl bg-info/10 text-info mb-6">
                                <i class="bi bi-graph-up-arrow text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-4 text-base-content">Pengembangan Pasar</h3>
                            <p class="text-base-content/70 leading-relaxed mb-4">
                                Fasilitasi pameran atau temu pelanggan, bimbingan teknis, manajerial, dan evaluasi capaian Business Model Canvas.
                            </p>
                            <div class="divider my-2"></div>
                            <ul class="space-y-2 text-base-content/90">
                                <li class="flex items-center"><i class="bi bi-check-circle-fill mr-3 text-info"></i> Konsultasi Bisnis</li>
                                <li class="flex items-center"><i class="bi bi-check-circle-fill mr-3 text-info"></i> Business Matching</li>
                                <li class="flex items-center"><i class="bi bi-check-circle-fill mr-3 text-info"></i> Evaluasi</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Card 3 - Start Up Graduate Program -->
                    <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-shadow h-full border border-transparent hover:border-error/50">
                        <div class="card-body p-8">
                            <div class="w-16 h-16 flex items-center justify-center rounded-2xl bg-error/10 text-error mb-6">
                                <i class="bi bi-mortarboard-fill text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-4 text-base-content">Start Up Graduate Program</h3>
                            <p class="text-base-content/70 leading-relaxed mb-4">
                                Program lanjutan untuk memantau perkembangan bisnis pasca program inkubasi reguler.
                            </p>
                            <div class="divider my-2"></div>
                            <ul class="space-y-2 text-base-content/90">
                                <li class="flex items-center"><i class="bi bi-check-circle-fill mr-3 text-error"></i> Pemantauan Pasca Inkubasi</li>
                                <li class="flex items-center"><i class="bi bi-check-circle-fill mr-3 text-error"></i> Pendampingan Lanjutan</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>