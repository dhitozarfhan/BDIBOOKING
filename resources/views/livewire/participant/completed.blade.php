<div class="min-h-screen bg-base-200/30">
    <div class="flex container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Sidebar --}}
        @include('layouts.partials.participant-sidebar')

        {{-- Main Content --}}
        <div class="flex-1 min-w-0 p-8 lg:p-10">
            @if (session()->has('error'))
                <div class="alert alert-error mb-6 rounded-xl shadow-sm">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div class="mb-8">
                <h1 class="text-2xl font-bold text-base-content tracking-tight">Riwayat Layanan</h1>
                <p class="text-sm text-base-content/50 mt-1">Layanan Diklat & Properti yang telah Anda selesaikan beserta sertifikat.</p>
            </div>

            {{-- Filter Buttons --}}
            <div class="flex flex-wrap gap-2 mb-6">
                <button wire:click="setFilter('semua')"
                        class="btn btn-sm rounded-xl {{ $filter === 'semua' ? 'btn-primary' : 'btn-ghost bg-base-100 border border-base-200' }}">
                    Semua
                </button>
                <button wire:click="setFilter('pelatihan')"
                        class="btn btn-sm rounded-xl {{ $filter === 'pelatihan' ? 'btn-primary' : 'btn-ghost bg-base-100 border border-base-200' }}">
                    <i class="bi bi-mortarboard-fill"></i> Pelatihan
                </button>
                <button wire:click="setFilter('properti')"
                        class="btn btn-sm rounded-xl {{ $filter === 'properti' ? 'btn-secondary' : 'btn-ghost bg-base-100 border border-base-200' }}">
                    <i class="bi bi-building-fill"></i> Properti
                </button>
            </div>

            <div class="space-y-4">
                @forelse($bookings as $booking)
                    @php
                        $isTraining = $booking->bookable_type === \App\Models\Training::class;
                        $bookableName = $isTraining ? ($booking->bookable->title ?? 'Pelatihan') : ($booking->bookable->name ?? 'Properti');
                    @endphp
                    <div wire:key="completed-{{ $booking->id }}" class="bg-base-100 rounded-2xl border border-base-200/80 shadow-sm overflow-hidden">
                        <div class="px-6 py-5">
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-wrap items-center gap-2.5 mb-2">
                                        <h2 class="text-base font-bold text-base-content">{{ $bookableName }}</h2>
                                        {{-- Type Badge --}}
                                        @if($isTraining)
                                            <span class="badge badge-primary badge-sm font-semibold">Pelatihan</span>
                                        @else
                                            <span class="badge badge-secondary badge-sm font-semibold">Properti</span>
                                        @endif
                                        <span class="badge badge-info badge-sm font-semibold">Selesai</span>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-4 text-sm text-base-content/50">
                                        @if($booking->bookable->start_date ?? false)
                                            <span class="inline-flex items-center gap-1.5">
                                                <i class="bi bi-calendar-event text-base-content/30"></i>
                                                {{ $booking->bookable->start_date->format('d M Y') }} - {{ $booking->bookable->end_date->format('d M Y') }}
                                            </span>
                                        @endif
                                        @if($booking->bookable->location ?? false)
                                            <span class="inline-flex items-center gap-1.5">
                                                <i class="bi bi-geo-alt-fill text-base-content/30"></i>
                                                {{ $booking->bookable->location }}
                                            </span>
                                        @endif
                                        @if(!$isTraining && ($booking->bookable->capacity ?? false))
                                            <span class="inline-flex items-center gap-1.5">
                                                <i class="bi bi-people-fill text-base-content/30"></i>
                                                Kapasitas: {{ $booking->bookable->capacity }} orang
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Certificate --}}
                            @if($booking->certificate)
                                <div class="rounded-xl bg-success/10 border border-success/20 p-4">
                                    <div class="flex items-center justify-between flex-wrap gap-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-success/20 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-base-content">Sertifikat Tersedia</p>
                                                <p class="text-xs text-base-content/50">No. {{ $booking->certificate->certificate_number }} · Terbit {{ $booking->certificate->issued_at?->format('d M Y') }}</p>
                                            </div>
                                        </div>
                                        <button wire:click="downloadCertificate({{ $booking->id }})" class="btn btn-sm btn-success rounded-xl gap-1.5">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                            Download Sertifikat
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div class="rounded-xl bg-base-200/40 border border-base-200 p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-base-200/80 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-base-content/25" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-base-content/50">Sertifikat Belum Tersedia</p>
                                            <p class="text-xs text-base-content/35">Sedang dalam proses penerbitan.</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="bg-base-100 rounded-2xl border border-base-200/80 px-12 pt-10 pb-12 text-center">
                        <div class="w-14 h-14 rounded-2xl bg-base-200/60 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-7 h-7 text-base-content/25" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>
                        </div>
                        <p class="text-sm text-base-content/40 font-medium">Belum ada layanan yang diselesaikan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
