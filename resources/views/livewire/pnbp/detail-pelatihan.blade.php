<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">
        {{-- Breadcrumbs --}}
        <nav class="text-sm breadcrumbs">
            <ul>
                <li><a href="{{ url('/') }}"><i class="bi bi-house-fill"></i></a></li>
                <li><a href="{{ route('pnbp.index') }}">Pelayanan PNBP</a></li>
                <li><a href="{{ route('pnbp.pelatihan') }}">Pelatihan</a></li>
                <li>{{ $training->title }}</li>
            </ul>
        </nav>
    </div>

    {{-- Flash Messages --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div role="alert" class="alert alert-success mb-6 shadow">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <div>
                    <h3 class="font-bold">Berhasil!</h3>
                    <div class="text-xs">{{ session('success') }}</div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div role="alert" class="alert alert-error mb-6 shadow">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <div>
                    <h3 class="font-bold">Terjadi Kesalahan!</h3>
                    <div class="text-xs">{{ session('error') }}</div>
                </div>
            </div>
        @endif
    </div>

    {{-- Main Content --}}
    <section class="bg-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Left Column: Image & Info --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Image --}}
                    <div class="card bg-base-100 shadow-xl overflow-hidden border border-base-200">
                        <figure class="h-64 md:h-80 overflow-hidden">
                            <img src="{{ $training->image ? Storage::url($training->image) : asset('images/default-training.jpg') }}"
                                 alt="{{ $training->title }}"
                                 class="w-full h-full object-cover" />
                        </figure>
                    </div>

                    {{-- Title & Description --}}
                    <div>
                        <div class="flex gap-2 mb-3">
                            @if($training->type)
                                <span class="badge badge-primary">{{ $training->type }}</span>
                            @endif
                        </div>
                        <h1 class="text-3xl md:text-4xl font-bold text-base-content mb-4">{{ $training->title }}</h1>
                        @if($training->description)
                            <div class="prose max-w-none text-base-content/80">
                                {!! $training->description !!}
                            </div>
                        @endif
                    </div>

                    {{-- Detail Pelaksanaan --}}
                    <div class="card bg-base-100 shadow border border-base-200">
                        <div class="card-body">
                            <h2 class="card-title text-xl mb-4">
                                <i class="bi bi-info-circle-fill text-primary"></i>
                                Detail Pelaksanaan
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                {{-- Jadwal --}}
                                <div class="p-4 rounded-xl bg-base-200/50 border border-base-200">
                                    <span class="text-xs font-semibold uppercase text-base-content/50 block mb-2">Jadwal</span>
                                    <div class="flex items-center gap-2 text-sm font-medium">
                                        <i class="bi bi-calendar-event text-primary"></i>
                                        <span>{{ $training->start_date->format('d M Y') }} - {{ $training->end_date->format('d M Y') }}</span>
                                    </div>
                                </div>

                                {{-- Lokasi --}}
                                <div class="p-4 rounded-xl bg-base-200/50 border border-base-200">
                                    <span class="text-xs font-semibold uppercase text-base-content/50 block mb-2">Lokasi</span>
                                    <div class="flex items-center gap-2 text-sm font-medium">
                                        <i class="bi bi-geo-alt-fill text-primary"></i>
                                        <span>{{ $training->location ?? '-' }}</span>
                                    </div>
                                </div>

                                {{-- Kuota --}}
                                <div class="p-4 rounded-xl bg-base-200/50 border border-base-200">
                                    <span class="text-xs font-semibold uppercase text-base-content/50 block mb-2">Kuota</span>
                                    <div class="flex items-center gap-2 text-sm font-medium">
                                        <i class="bi bi-people-fill text-primary"></i>
                                        <span>{{ $training->quota == 0 ? 'Tidak Terbatas' : $training->quota . ' Peserta' }}</span>
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
                                <h3 class="text-lg font-semibold mb-2">Investasi</h3>
                                <p class="text-3xl font-bold text-secondary mb-1">
                                    {{ 'Rp ' . number_format($training->price, 0, ',', '.') }}
                                </p>
                                <p class="text-sm text-base-content/50 mb-4">per peserta</p>

                                <div class="divider my-2"></div>

                                {{-- Info ringkas --}}
                                <div class="space-y-3 mb-6">
                                    <div class="flex items-center gap-3 text-sm">
                                        <i class="bi bi-calendar-event text-primary"></i>
                                        <div>
                                            <span class="text-base-content/50 block text-xs">Jadwal</span>
                                            <span class="font-medium">{{ $training->start_date->format('d M Y') }} - {{ $training->end_date->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 text-sm">
                                        <i class="bi bi-geo-alt-fill text-primary"></i>
                                        <div>
                                            <span class="text-base-content/50 block text-xs">Lokasi</span>
                                            <span class="font-medium">{{ $training->location ?? '-' }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 text-sm">
                                        <i class="bi bi-people-fill text-primary"></i>
                                        <div>
                                            <span class="text-base-content/50 block text-xs">Kuota</span>
                                            <span class="font-medium">{{ $training->quota == 0 ? 'Tidak Terbatas' : $training->quota . ' Peserta' }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Action Button --}}
                                @if(Auth::guard('participant')->check())
                                    @if($isRegistered)
                                        <div class="alert alert-success py-3">
                                            <i class="bi bi-check-circle-fill"></i>
                                            <span class="font-bold text-sm">Anda Sudah Terdaftar</span>
                                        </div>
                                        <a href="{{ route('participant.dashboard') }}" class="btn btn-outline btn-block mt-3">
                                            <i class="bi bi-speedometer2 mr-1"></i> Lihat Dashboard
                                        </a>
                                    @else
                                        <button wire:click="register" class="btn btn-secondary btn-block btn-lg">
                                            <i class="bi bi-pencil-square mr-1"></i> Daftar Sekarang
                                        </button>
                                    @endif
                                @else
                                    <button wire:click="register" class="btn btn-secondary btn-block btn-lg">
                                        <i class="bi bi-pencil-square mr-1"></i> Daftar Sekarang
                                    </button>
                                    <p class="text-xs text-center text-base-content/50 mt-2">Anda akan diarahkan untuk login terlebih dahulu</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>
