<div class="min-h-screen bg-base-200/30">
    <div class="flex container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Sidebar --}}
        @include('layouts.partials.participant-sidebar')

        {{-- Main Content --}}
        <div class="flex-1 min-w-0 p-8 lg:p-10">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-base-content tracking-tight">Diklat Diikuti</h1>
                <p class="text-sm text-base-content/50 mt-1">Diklat yang sedang Anda ikuti atau menunggu persetujuan.</p>
            </div>

            <div class="space-y-4">
                @forelse($bookings as $booking)
                    <div wire:key="enrolled-{{ $booking->id }}" class="bg-base-100 rounded-2xl border border-base-200/80 shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
                        <div class="px-6 py-6">
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                <div class="flex-1 min-w-0">
                                    <h2 class="text-base font-bold text-base-content mb-2">{{ $booking->bookable->title ?? 'Diklat' }}</h2>
                                    <div class="flex flex-wrap items-center gap-3 mb-3">
                                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full
                                            {{ $booking->status === 'approved' 
                                                ? 'bg-emerald-50 text-emerald-600 ring-1 ring-emerald-500/20' 
                                                : 'bg-amber-50 text-amber-600 ring-1 ring-amber-500/20' }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $booking->status === 'approved' ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                                            {{ $booking->status === 'approved' ? 'Disetujui' : 'Menunggu' }}
                                        </span>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-4 text-sm text-base-content/50">
                                        @if($booking->bookable->start_date ?? false)
                                            <span class="inline-flex items-center gap-1.5">
                                                <svg class="w-4 h-4 text-base-content/30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                {{ $booking->bookable->start_date->format('d M Y') }} - {{ $booking->bookable->end_date->format('d M Y') }}
                                            </span>
                                        @endif
                                        @if($booking->bookable->location ?? false)
                                            <span class="inline-flex items-center gap-1.5">
                                                <svg class="w-4 h-4 text-base-content/30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                                {{ $booking->bookable->location }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="shrink-0">
                                    <a href="{{ route('pnbp.detail', ['id_diklat' => $booking->bookable->id, 'slug' => Str::slug($booking->bookable->title)]) }}" class="btn btn-sm btn-ghost text-indigo-600 hover:bg-indigo-50 rounded-xl gap-1.5">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                                        Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-base-100 rounded-2xl border border-base-200/80 px-12 pt-10 pb-12 text-center">
                        <div class="w-14 h-14 rounded-2xl bg-base-200/60 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-7 h-7 text-base-content/25" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        </div>
                        <p class="text-sm text-base-content/40 font-medium">Belum ada diklat yang sedang diikuti.</p>
                        <a href="{{ route('pnbp.index') }}" wire:navigate class="btn btn-sm btn-primary rounded-xl mt-4 gap-1.5">Lihat Daftar Diklat</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
