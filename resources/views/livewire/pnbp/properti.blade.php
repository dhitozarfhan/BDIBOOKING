<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">
        {{-- Breadcrumbs --}}
        <nav class="text-sm breadcrumbs">
            <ul>
                <li><a href="{{ url('/') }}"><i class="bi bi-house-fill"></i></a></li>
                <li><a href="{{ route('pnbp.index') }}">Pelayanan PNBP</a></li>
                <li>Properti</li>
            </ul>
        </nav>
    </div>

    {{-- Property List Section --}}
    <section class="py-8 bg-white">
        <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-center md:text-left">Daftar Properti Tersedia</h2>
            </div>

            @if($properties->isEmpty())
                <div class="text-center py-12">
                    <i class="bi bi-building text-6xl text-gray-300 mb-4 block"></i>
                    <p class="text-gray-500 text-lg">Belum ada properti yang tersedia saat ini.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($properties as $property)
                        <div class="card bg-base-100 shadow-xl overflow-hidden hover:shadow-2xl transition-shadow duration-300">
                             {{-- Image Section --}}
                             <div class="relative h-48 overflow-hidden">
                                @if($property->image && count((array)$property->image) > 0)
                                    <img src="{{ Storage::url($property->image[0]) }}" alt="{{ $property->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-secondary/10 to-primary/10 flex items-center justify-center">
                                        <i class="bi bi-building text-5xl text-base-content/10"></i>
                                    </div>
                                @endif
                                <div class="absolute top-3 left-3 flex gap-2">
                                    <span class="badge badge-secondary badge-sm shadow-sm">{{ str_replace('_', ' ', ucfirst($property->category)) }}</span>
                                </div>
                            </div>

                            <div class="card-body">
                                <h2 class="card-title text-xl font-bold">{{ $property->name }}</h2>
                                @if($property->description)
                                    <p class="text-sm text-gray-600 line-clamp-3">{{ $property->description }}</p>
                                @endif
                                <div class="flex items-center gap-4 mt-2 text-sm text-gray-500">
                                    <span><i class="bi bi-people-fill mr-1"></i> Kapasitas Ruangan: {{ $property->capacity }} orang</span>
                                </div>
                                <p class="text-lg font-bold text-secondary mt-2">
                                    {{ 'Rp ' . number_format($property->price, 0, ',', '.') }}
                                </p>
                                <div class="card-actions justify-end mt-auto">
                                    <a href="{{ route('pnbp.properti.detail', ['id' => $property->id, 'slug' => Str::slug($property->name)]) }}" class="btn btn-secondary btn-block">
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
