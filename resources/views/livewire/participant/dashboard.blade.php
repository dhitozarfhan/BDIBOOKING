<div class="min-h-screen bg-base-200/30">
    <div class="flex container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Sidebar --}}
        @include('layouts.partials.participant-sidebar')

        {{-- Main Content --}}
        <div class="flex-1 min-w-0 p-8 lg:p-10">
            {{-- Flash Messages --}}
            @if (session()->has('success'))
                <div class="alert alert-success mb-6 rounded-xl shadow-sm">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if (session()->has('error'))
                <div class="alert alert-error mb-6 rounded-xl shadow-sm">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- Page Header --}}
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-base-content tracking-tight">Dashboard</h1>
                <p class="text-sm text-base-content/50 mt-1">Ringkasan aktivitas pendaftaran diklat Anda.</p>
            </div>

            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-10">
                {{-- Total Diklat --}}
                <div class="bg-base-100 rounded-2xl border border-base-200/80 p-5 flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500/15 to-purple-500/15 flex items-center justify-center">
                        <svg class="w-6 h-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-base-content/40">Total Diklat</p>
                        <p class="text-2xl font-bold text-base-content mt-0.5">{{ $bookings->count() }}</p>
                    </div>
                </div>

                {{-- Disetujui --}}
                <div class="bg-base-100 rounded-2xl border border-base-200/80 p-5 flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500/15 to-green-500/15 flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-base-content/40">Disetujui</p>
                        <p class="text-2xl font-bold text-base-content mt-0.5">{{ $bookings->whereIn('status', ['approved', 'completed'])->count() }}</p>
                    </div>
                </div>

                {{-- Menunggu --}}
                <div class="bg-base-100 rounded-2xl border border-base-200/80 p-5 flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500/15 to-orange-500/15 flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-base-content/40">Menunggu</p>
                        <p class="text-2xl font-bold text-base-content mt-0.5">{{ $bookings->where('status', 'pending')->count() }}</p>
                    </div>
                </div>
            </div>

            {{-- Invoices & Status --}}
            <div>
                <h2 class="text-base font-bold text-base-content mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-base-content/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" /></svg>
                    Tagihan & Pembayaran
                </h2>

                <div class="space-y-4">
                    @forelse($bookings as $booking)
                        @if(!$booking->invoices->isEmpty())
                            <div wire:key="booking-{{ $booking->id }}" class="bg-base-100 rounded-2xl border border-base-200/80 shadow-sm overflow-hidden">
                                {{-- Booking Header --}}
                                <div class="px-5 py-4 border-b border-base-200/60 flex flex-wrap items-center justify-between gap-2">
                                    <div class="flex items-center gap-2.5">
                                        <h3 class="font-semibold text-sm text-base-content">{{ $booking->bookable->title ?? 'Diklat' }}</h3>
                                        <span class="badge badge-xs font-semibold
                                            @switch($booking->status)
                                                @case('approved') badge-success @break
                                                @case('rejected') badge-error @break
                                                @case('completed') badge-info @break
                                                @default badge-ghost @endswitch
                                        ">{{ ucfirst($booking->status) }}</span>
                                    </div>
                                    <span class="text-xs text-base-content/40">{{ $booking->created_at->format('d M Y') }}</span>
                                </div>

                                {{-- Invoice Items --}}
                                <div class="divide-y divide-base-200/50">
                                    @foreach($booking->invoices as $invoice)
                                        <div wire:key="invoice-{{ $invoice->id }}" class="px-5 py-4">
                                            <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                                                <div class="flex-1 grid grid-cols-2 sm:grid-cols-4 gap-y-3 gap-x-4">
                                                    <div>
                                                        <p class="text-[10px] font-semibold uppercase tracking-wider text-base-content/35">Kode Billing</p>
                                                        <p class="font-mono font-bold text-sm mt-0.5 text-base-content">{{ $invoice->billing_code }}</p>
                                                    </div>
                                                    <div>
                                                        <p class="text-[10px] font-semibold uppercase tracking-wider text-base-content/35">Jatuh Tempo</p>
                                                        <p class="text-sm font-medium mt-0.5 text-base-content/80">{{ $invoice->due_date->format('d M Y H:i') }}</p>
                                                    </div>
                                                    <div>
                                                        <p class="text-[10px] font-semibold uppercase tracking-wider text-base-content/35">Jumlah</p>
                                                        <p class="text-sm font-bold text-indigo-600 mt-0.5">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</p>
                                                    </div>
                                                    <div>
                                                        <p class="text-[10px] font-semibold uppercase tracking-wider text-base-content/35">Status</p>
                                                        <span class="inline-flex items-center gap-1 mt-1 text-xs font-semibold
                                                            @if($invoice->effective_status === 'paid') text-emerald-600
                                                            @elseif($invoice->effective_status === 'expired') text-red-500
                                                            @else text-amber-500 @endif">
                                                            <span class="w-1.5 h-1.5 rounded-full 
                                                                @if($invoice->effective_status === 'paid') bg-emerald-500
                                                                @elseif($invoice->effective_status === 'expired') bg-red-500
                                                                @else bg-amber-500 @endif"></span>
                                                            {{ ucfirst($invoice->effective_status) }}
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="flex flex-wrap items-center gap-2 shrink-0 lg:border-l lg:border-base-200/60 lg:pl-4">
                                                    @if($invoice->invoice_file)
                                                        <button wire:click="downloadInvoice({{ $invoice->id }})" class="btn btn-xs btn-ghost rounded-lg gap-1 text-indigo-600 hover:bg-indigo-50">
                                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                                            Download
                                                        </button>
                                                    @endif

                                                    @if($invoice->effective_status === 'unpaid' && !$invoice->payment_proof)
                                                        <div class="flex items-center gap-2" x-data="{ fileName: '' }">
                                                            <label class="btn btn-sm btn-outline btn-primary gap-2 cursor-pointer normal-case font-medium rounded-lg hover:bg-primary hover:text-white transition-all">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                                                                <span x-text="fileName ? (fileName.length > 20 ? fileName.substring(0, 17) + '...' : fileName) : 'Upload Bukti'">Upload Bukti</span>
                                                                <input type="file" wire:model="payment_proofs.{{ $invoice->id }}" class="hidden" accept=".jpg,.jpeg,.png,.pdf" @change="fileName = $event.target.files[0].name" />
                                                            </label>
                                                            
                                                            @if(isset($payment_proofs[$invoice->id]) && $payment_proofs[$invoice->id])
                                                                <button wire:click="uploadProof({{ $invoice->id }})" class="btn btn-sm btn-primary rounded-lg shadow-sm animate-pulse">
                                                                    Simpan
                                                                </button>
                                                            @endif
                                                        </div>
                                                    @elseif($invoice->payment_proof)
                                                        <a href="{{ Storage::url($invoice->payment_proof) }}" target="_blank" class="btn btn-xs btn-ghost rounded-lg gap-1 text-base-content/60 hover:bg-base-200/60">
                                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                            Lihat Bukti
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="bg-base-100 rounded-2xl border border-base-200/80 px-12 pt-10 pb-12 text-center">
                            <div class="w-14 h-14 rounded-2xl bg-base-200/60 flex items-center justify-center mx-auto mb-4">
                                <svg class="w-7 h-7 text-base-content/25" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" /></svg>
                            </div>
                            <p class="text-sm text-base-content/40 font-medium">Belum ada tagihan.</p>
                            <a href="{{ route('pnbp.index') }}" wire:navigate class="btn btn-sm btn-primary rounded-xl mt-4 gap-1.5">Lihat Daftar Diklat</a>
                        </div>
                    @endforelse

                    {{-- Show message if bookings exist but none have invoices --}}
                    @if($bookings->isNotEmpty() && $bookings->every(fn($b) => $b->invoices->isEmpty()))
                        <div class="bg-base-100 rounded-2xl border border-base-200/80 px-12 pt-10 pb-12 text-center">
                            <div class="w-14 h-14 rounded-2xl bg-base-200/60 flex items-center justify-center mx-auto mb-4">
                                <svg class="w-7 h-7 text-base-content/25" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" /></svg>
                            </div>
                            <p class="text-sm text-base-content/40 font-medium">Belum ada tagihan untuk diklat yang terdaftar.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
