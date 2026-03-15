<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">
        {{-- Breadcrumbs --}}
        <nav class="text-sm breadcrumbs">
            <ul>
                <li><a href="{{ url('/') }}"><i class="bi bi-house-fill"></i></a></li>
                <li><a href="{{ route('pnbp.index') }}">Pelayanan PNBP</a></li>
                <li><a href="{{ route('pnbp.properti') }}">Properti</a></li>
                <li>{{ $property->name }}</li>
            </ul>
        </nav>
    </div>

    {{-- Main Content --}}
    <section class="bg-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Left Column: Info --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Image Section --}}
                    <div class="rounded-3xl overflow-hidden border border-base-200 shadow-sm bg-base-100">
                        @if($property->image && count((array)$property->image) > 0)
                            <img src="{{ Storage::url($property->image[0]) }}" alt="{{ $property->name }}" class="w-full h-auto object-cover max-h-[400px]" />
                        @else
                            <div class="w-full h-64 bg-gradient-to-br from-secondary/10 to-primary/10 flex items-center justify-center">
                                <i class="bi bi-building text-7xl text-base-content/10"></i>
                            </div>
                        @endif
                    </div>

                    {{-- Title & Badge --}}
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            <span class="badge badge-secondary">{{ str_replace('_', ' ', ucfirst($property->category)) }}</span>
                            <span class="badge badge-outline @if($property->available_rooms_count > 0) badge-success @else badge-error @endif">
                                {{ $property->available_rooms_count }} Unit Tersedia
                            </span>
                        </div>
                        <h1 class="text-3xl md:text-4xl font-bold text-base-content mb-4">{{ $property->name }}</h1>
                        @if($property->description)
                            <div class="prose max-w-none text-base-content/80">
                                {!! $property->description !!}
                            </div>
                        @endif
                    </div>

                    {{-- Detail Properti --}}
                    <div class="card bg-base-100 shadow border border-base-200">
                        <div class="card-body">
                            <h2 class="card-title text-xl mb-4">
                                <i class="bi bi-info-circle-fill text-secondary"></i>
                                Detail Properti
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                {{-- Tipe --}}
                                <div class="p-4 rounded-xl bg-base-200/50 border border-base-200">
                                    <span class="text-xs font-semibold uppercase text-base-content/50 block mb-2">Tipe Properti</span>
                                    <div class="flex items-center gap-2 text-sm font-medium">
                                        <i class="bi bi-building text-secondary"></i>
                                        <span>{{ str_replace('_', ' ', ucfirst($property->category)) }}</span>
                                    </div>
                                </div>

                                {{-- Kapasitas --}}
                                <div class="p-4 rounded-xl bg-base-200/50 border border-base-200">
                                    <span class="text-xs font-semibold uppercase text-base-content/50 block mb-2">Kapasitas Ruangan</span>
                                    <div class="flex items-center gap-2 text-sm font-medium">
                                        <i class="bi bi-people-fill text-secondary"></i>
                                        <span>{{ $property->capacity }} orang</span>
                                    </div>
                                </div>

                                {{-- Harga --}}
                                <div class="p-4 rounded-xl bg-base-200/50 border border-base-200">
                                    <span class="text-xs font-semibold uppercase text-base-content/50 block mb-2">Harga Sewa</span>
                                    <div class="flex items-center gap-2 text-sm font-medium">
                                        <i class="bi bi-cash-stack text-secondary"></i>
                                        <span>{{ 'Rp ' . number_format($property->price, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Sticky Sidebar --}}
                <div class="lg:col-span-1">
                    <div class="lg:sticky lg:top-24">
                        <div class="card bg-base-100 shadow-xl border border-base-200">
                            <div class="card-body">
                                <h3 class="text-lg font-semibold mb-2">Harga Sewa</h3>
                                <p class="text-3xl font-bold text-secondary mb-1">
                                    {{ 'Rp ' . number_format($property->price, 0, ',', '.') }}
                                </p>
                                <p class="text-sm text-base-content/50 mb-4">per sewa</p>

                                <div class="divider my-2"></div>

                                {{-- Info ringkas --}}
                                <div class="space-y-3 mb-6">
                                    <div class="flex items-center gap-3 text-sm">
                                        <i class="bi bi-building text-secondary"></i>
                                        <div>
                                            <span class="text-base-content/50 block text-xs">Tipe</span>
                                            <span class="font-medium">{{ str_replace('_', ' ', ucfirst($property->category)) }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 text-sm">
                                        <i class="bi bi-people-fill text-secondary"></i>
                                        <div>
                                            <span class="text-base-content/50 block text-xs">Kapasitas Ruangan</span>
                                            <span class="font-medium">{{ $property->capacity }} orang</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Action Buttons --}}
                                <a href="{{ route('pnbp.properti.register', ['id' => $property->id, 'slug' => Str::slug($property->name)]) }}"
                                   class="btn btn-secondary btn-block btn-lg">
                                    <i class="bi bi-cart-check mr-1"></i> Pesan Sekarang
                                </a>
                                <p class="text-xs text-center text-base-content/50 mt-2">Anda akan diarahkan ke halaman pemesanan</p>
                                <a href="{{ route('pnbp.properti') }}" class="btn btn-outline btn-block mt-2">
                                    <i class="bi bi-arrow-left mr-1"></i> Kembali ke Daftar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>
