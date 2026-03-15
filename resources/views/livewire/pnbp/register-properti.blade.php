<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
    {{-- Breadcrumbs --}}
    <nav class="text-sm breadcrumbs mb-6">
        <ul>
            <li><a href="{{ url('/') }}"><i class="bi bi-house-fill"></i></a></li>
            <li><a href="{{ route('pnbp.index') }}">Pelayanan PNBP</a></li>
            <li><a href="{{ route('pnbp.properti') }}">Properti</a></li>
            <li><a href="{{ route('pnbp.properti.detail', ['id' => $property->id, 'slug' => Str::slug($property->name)]) }}">{{ $property->name }}</a></li>
            <li>Pemesanan</li>
        </ul>
    </nav>

    {{-- Hero Header --}}
    <div class="bg-base-100 rounded-3xl border border-base-200/80 shadow-sm overflow-hidden mb-8">
        <div class="relative">
            {{-- Banner BG --}}
            <div class="relative h-40 md:h-48 overflow-hidden bg-gradient-to-br from-secondary/20 via-primary/10 to-secondary/5">
                <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-black/20 to-transparent"></div>
                <div class="absolute top-4 left-4">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/20 backdrop-blur-md text-white text-xs font-semibold uppercase tracking-wider border border-white/20">
                        <i class="bi bi-building-fill"></i>
                        Sewa Properti
                    </span>
                </div>
            </div>

            {{-- Info Overlay --}}
            <div class="relative -mt-16 px-6 md:px-8 pb-6 md:pb-8">
                <div class="bg-base-100 rounded-2xl border border-base-200/80 shadow-lg p-5 md:p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex gap-2 mb-2">
                                <span class="badge badge-secondary badge-sm">{{ str_replace('_', ' ', ucfirst($property->category)) }}</span>
                            </div>
                            <h1 class="text-xl md:text-2xl font-bold text-base-content leading-tight mb-2">
                                {{ $property->name }}
                            </h1>
                            <p class="text-sm text-base-content/50 leading-relaxed">
                                Silakan lengkapi formulir pemesanan di bawah ini untuk menyewa properti.
                            </p>
                        </div>
                        <div class="shrink-0 flex flex-col items-start md:items-end gap-1">
                            <span class="text-xs font-semibold uppercase tracking-wider text-base-content/35">Harga Sewa</span>
                            <span class="text-2xl font-bold text-secondary">
                                {{ 'Rp ' . number_format($property->price, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Booking Form --}}
    <div class="bg-base-100 rounded-3xl border border-base-200/80 shadow-sm overflow-hidden">
        <form wire:submit.prevent="submit">
            {{-- Jadwal Penyewaan --}}
            <div class="px-6 md:px-8 pt-6 md:pt-8 pb-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-secondary/15 to-primary/15 flex items-center justify-center">
                        <i class="bi bi-calendar-event text-secondary text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-base-content">Jadwal Penyewaan</h2>
                        <p class="text-xs text-base-content/40">Tentukan tanggal mulai dan selesai penyewaan</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Tanggal Mulai --}}
                    <div>
                        <label class="block mb-2">
                            <span class="text-sm font-semibold text-base-content">Tanggal Mulai <span class="text-red-400">*</span></span>
                        </label>
                        <input type="date" wire:model="start_date"
                               class="input input-bordered w-full rounded-xl focus:border-secondary focus:ring-1 focus:ring-secondary @error('start_date') input-error @enderror" />
                        @error('start_date')
                            <p class="flex items-center gap-1 mt-2 text-xs text-red-500">
                                <i class="bi bi-exclamation-triangle-fill"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Tanggal Selesai --}}
                    <div>
                        <label class="block mb-2">
                            <span class="text-sm font-semibold text-base-content">Tanggal Selesai <span class="text-red-400">*</span></span>
                        </label>
                        <input type="date" wire:model="end_date"
                               class="input input-bordered w-full rounded-xl focus:border-secondary focus:ring-1 focus:ring-secondary @error('end_date') input-error @enderror" />
                        @error('end_date')
                            <p class="flex items-center gap-1 mt-2 text-xs text-red-500">
                                <i class="bi bi-exclamation-triangle-fill"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="divider mx-6 md:mx-8 my-0"></div>

            {{-- Jumlah --}}
            <div class="px-6 md:px-8 py-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500/15 to-teal-500/15 flex items-center justify-center">
                        <i class="bi bi-people-fill text-emerald-500 text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-base-content">Jumlah Unit</h2>
                        <p class="text-xs text-base-content/40">Tentukan jumlah unit yang ingin disewa</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                    {{-- Counter --}}
                    <div class="flex items-center gap-4">
                        <button type="button" wire:click="$set('quantity', {{ max(1, $quantity - 1) }})"
                                class="w-12 h-12 rounded-xl bg-base-200/60 hover:bg-red-50 hover:text-red-600 text-base-content/60 flex items-center justify-center transition-all duration-200 text-xl font-bold border border-base-200/80 hover:border-red-200">
                            −
                        </button>
                        <input type="number" wire:model.live="quantity" min="1" max="{{ $max_quantity }}"
                               class="w-20 h-12 text-center text-xl font-bold rounded-xl border border-base-200/80 focus:border-secondary focus:ring-1 focus:ring-secondary bg-base-100 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" />
                        <button type="button" wire:click="$set('quantity', {{ min($max_quantity, $quantity + 1) }})"
                                class="w-12 h-12 rounded-xl bg-base-200/60 hover:bg-emerald-50 hover:text-emerald-600 text-base-content/60 flex items-center justify-center transition-all duration-200 text-xl font-bold border border-base-200/80 hover:border-emerald-200"
                                @if($quantity >= $max_quantity) disabled @endif>
                            +
                        </button>
                        <span class="text-sm text-base-content/50 ml-1">unit (Maks: {{ $max_quantity }})</span>
                    </div>

                    {{-- Total Price --}}
                    <div class="bg-gradient-to-br from-pink-50 to-secondary/10 rounded-2xl px-6 py-4 border border-secondary/20">
                        <span class="text-xs font-semibold uppercase tracking-wider text-secondary/60 block mb-1">Total Biaya</span>
                        <span class="text-2xl font-bold text-secondary">
                            Rp {{ number_format($total_price, 0, ',', '.') }}
                        </span>
                        @if($total_days > 0)
                            <span class="block text-xs text-secondary/60 mt-0.5">
                                {{ $total_days }} hari × {{ $quantity }} unit × Rp {{ number_format($property->price, 0, ',', '.') }}
                            </span>
                        @endif
                    </div>
                </div>

                @error('quantity')
                    <p class="flex items-center gap-1 mt-3 text-xs text-red-500">
                        <i class="bi bi-exclamation-triangle-fill"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="divider mx-6 md:mx-8 my-0"></div>

            {{-- Catatan --}}
            <div class="px-6 md:px-8 py-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500/15 to-orange-500/15 flex items-center justify-center">
                        <i class="bi bi-chat-left-text-fill text-amber-500 text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-base-content">Catatan Tambahan</h2>
                        <p class="text-xs text-base-content/40">Opsional — tambahkan informasi atau permintaan khusus</p>
                    </div>
                </div>
                <textarea wire:model="notes" rows="3" placeholder="Contoh: Butuh proyektor dan sound system..."
                          class="textarea textarea-bordered w-full rounded-xl focus:border-secondary focus:ring-1 focus:ring-secondary"></textarea>
            </div>

            {{-- Action Buttons --}}
            <div class="px-6 md:px-8 pb-8">
                <div class="mt-4 pt-6 border-t border-base-200/80 flex flex-col sm:flex-row items-center justify-end gap-3">
                    <a href="{{ route('pnbp.properti.detail', ['id' => $property->id, 'slug' => Str::slug($property->name)]) }}"
                       wire:navigate
                       class="btn btn-ghost btn-lg rounded-xl w-full sm:w-auto order-2 sm:order-1 text-base-content/60 hover:text-base-content">
                        Batal
                    </a>
                    <button type="submit"
                            class="btn btn-lg bg-secondary hover:bg-secondary/90 border-0 text-white rounded-xl px-10 w-full sm:w-auto order-1 sm:order-2 shadow-lg hover:shadow-xl transition-all duration-200"
                            wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="submit" class="flex items-center gap-2">
                            <i class="bi bi-check-lg text-lg"></i>
                            Kirim Pemesanan
                        </span>
                        <span wire:loading wire:target="submit" class="flex items-center gap-2">
                            <span class="loading loading-spinner loading-sm"></span>
                            Memproses...
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
