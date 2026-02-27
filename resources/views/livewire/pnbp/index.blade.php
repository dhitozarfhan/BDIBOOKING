<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">
        {{-- Breadcrumbs --}}
        <nav class="text-sm breadcrumbs">
            <ul>
                <li><a href="{{ url('/') }}"><i class="bi bi-house-fill"></i></a></li>
                <li>Diklat PNBP</li>
            </ul>
        </nav>
    </div>

    <section class="bg-base-100">
        <div class="container max-w-7xl mx-auto px-4">
            <div class="card" style="padding: 1rem;">
                <div>
                    <h2 class="text-4xl font-bold">Diklat PNBP</h2>
                    <h4 class="text-2xl font-semibold mt-2 mb-4">Pelatihan Berbayar untuk Umum</h4>
                </div>
                <div class="pt-2">
                    <div class="text-2xm leading-10">
                        BDI Yogyakarta menyelenggarakan pelatihan berbasis PNBP (Penerimaan Negara Bukan Pajak) yang
                        terbuka untuk masyarakat umum. Pelatihan ini dirancang untuk meningkatkan kompetensi
                        di berbagai bidang industri kreatif, digital, dan manajemen.
                        <br/><br/>
                        <br/><br/>

                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Training List Section --}}
    <section class="py-12 bg-white">
        <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex flex-col md:flex-row gap-4 items-center justify-between">
                <h2 class="text-3xl font-bold text-center md:text-left">Daftar Pelatihan Tersedia</h2>
                <div class="flex flex-wrap gap-2 justify-center md:justify-end">
                    <button wire:click="$set('selectedCategory', '')" class="btn btn-sm {{ $selectedCategory === '' ? 'btn-primary' : 'btn-outline' }}">Semua</button>
                    @foreach($categories as $category)
                        <button wire:click="$set('selectedCategory', '{{ $category }}')" class="btn btn-sm {{ $selectedCategory === $category ? 'btn-primary' : 'btn-outline' }}">{{ $category }}</button>
                    @endforeach
                </div>
            </div>
            
            @if($trainings->isEmpty())
                <div class="text-center py-12">
                    <p class="text-gray-500 text-lg">Belum ada diklat PNBP yang tersedia saat ini.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($trainings as $training)
                        <div class="card bg-base-100 shadow-xl overflow-hidden hover:shadow-2xl transition-shadow duration-300">
                            <figure class="h-48 overflow-hidden">
                                <img src="{{ $training->image ? Storage::url($training->image) : asset('images/default-training.jpg') }}" alt="{{ $training->title }}" class="w-full h-full object-cover transition-transform duration-300 hover:scale-105" />
                            </figure>
                            <div class="card-body">
                                <div class="flex gap-2 mb-2">
                                    @if($training->type)
                                        <span class="badge badge-primary badge-sm">{{ $training->type }}</span>
                                    @endif
                                </div>
                                <h2 class="card-title text-xl font-bold line-clamp-2 min-h-[3.5rem]">{{ $training->title }}</h2>
                                <p class="text-sm text-gray-600 mb-2">
                                    <i class="bi bi-calendar-event mr-2"></i>
                                    {{ $training->start_date->format('d M Y') }} - {{ $training->end_date->format('d M Y') }}
                                </p>
                                <p class="text-lg font-bold text-secondary mb-4">
                                    {{ 'Rp ' . number_format($training->price, 0, ',', '.') }}
                                </p>
                                <div class="card-actions justify-end mt-auto">
                                    <a href="{{ route('pnbp.detail', ['id_diklat' => $training->id, 'slug' => Str::slug($training->title)]) }}" class="btn btn-secondary btn-block">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</div>
