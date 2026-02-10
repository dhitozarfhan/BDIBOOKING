<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard Peserta</h1>
        <form method="POST" action="{{ route('logout') }}" x-data>
            @csrf
            {{-- Logout logic might need adjustment if using custom guard logout, but for now assuming standard form or custom action --}}
            {{-- Since we don't have a direct logout route for participant, we might need one. Let's assume there's a global logout or we use a livewire action.
               But usually logout is a POST route.
               Let's add a logout button that calls a method in component or a route.
               Since I didn't create a specific logout route for participant, I should probably add one or handle it here.
               Actually, I can add a logout method to the component.
            --}}
        </form>
         <button wire:click="$dispatch('logout')" class="btn btn-outline btn-error btn-sm">
            Logout
        </button>
    </div>
    
    @script
    <script>
        $wire.on('logout', () => {
             // We can't easily logout from Livewire without a full page reload or form submit.
             // Best to just redirect to a logout route. I'll create a logout method in the component.
             @this.logout();
        });
    </script>
    @endscript

    @if (session()->has('success'))
        <div class="alert alert-success shadow-lg mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    
     @if (session()->has('error'))
        <div class="alert alert-error shadow-lg mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid gap-6">
        @forelse($bookings as $booking)
            <div class="card bg-base-100 shadow-xl border border-gray-200">
                <div class="card-body">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div>
                            <h2 class="card-title text-xl font-bold">{{ $booking->bookable->title ?? 'Diklat Tidak Dikenal' }}</h2>
                            <p class="text-sm text-gray-500">
                                Tanggal: {{ optional($booking->bookable->start_date)->format('d M Y') }} - {{ optional($booking->bookable->end_date)->format('d M Y') }}
                            </p>
                            <div class="mt-2">
                                <span class="badge 
                                    @switch($booking->status)
                                        @case('approved') badge-success @break
                                        @case('rejected') badge-error @break
                                        @case('completed') badge-info @break
                                        @default badge-ghost @endswitch
                                ">
                                    Status Pendaftaran: {{ ucfirst($booking->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="text-right">
                            @if($booking->bookable->type === 'pnbp')
                                <a href="{{ route('pnbp.detail', ['id_diklat' => $booking->bookable->id, 'slug' => Str::slug($booking->bookable->title)]) }}" class="btn btn-sm btn-ghost">Lihat Detail PNBP</a>
                            @else
                                <a href="{{ route('training.detail', ['id_diklat' => $booking->bookable->id, 'slug' => Str::slug($booking->bookable->title)]) }}" class="btn btn-sm btn-ghost">Lihat Detail Diklat</a>
                            @endif
                        </div>
                    </div>

                    <div class="divider">Tagihan & Pembayaran</div>

                    @if($booking->invoices->isEmpty())
                        <div class="text-gray-500 text-sm italic">Belum ada tagihan yang diterbitkan.</div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="table w-full">
                                <thead>
                                    <tr>
                                        <th>Kode Billing</th>
                                        <th>Jatuh Tempo</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($booking->invoices as $invoice)
                                        <tr wire:key="invoice-{{ $invoice->id }}">
                                            <td class="font-mono font-bold">{{ $invoice->billing_code }}</td>
                                            <td>{{ $invoice->due_date->format('d M Y H:i') }}</td>
                                            <td>Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                                            <td>
                                                <span class="badge 
                                                    @switch($invoice->status)
                                                        @case('paid') badge-success @break
                                                        @case('expired') badge-error @break
                                                        @case('cancelled') badge-warning @break
                                                        @default badge-ghost @endswitch
                                                ">
                                                    {{ ucfirst($invoice->status) }}
                                                </span>
                                                @if($invoice->payment_proof && $invoice->status == 'unpaid')
                                                    <div class="badge badge-info badge-outline mt-1 text-xs">Menunggu Verifikasi</div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="flex flex-col gap-2">
                                                    @if($invoice->invoice_file)
                                                        <button wire:click="downloadInvoice({{ $invoice->id }})" class="btn btn-xs btn-outline btn-primary">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                                            Download Tagihan
                                                        </button>
                                                    @endif
                                                    
                                                    @if($invoice->status == 'unpaid')
                                                        @if($invoice->payment_proof)
                                                            <span class="text-xs text-success flex items-center">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                                                Bukti Terupload
                                                            </span>
                                                        @else
                                                            <div class="form-control w-full max-w-xs">
                                                                <input type="file" wire:model="payment_proofs.{{ $invoice->id }}" class="file-input file-input-bordered file-input-xs w-full max-w-xs" accept="image/*,application/pdf" />
                                                                @error("payment_proofs.{$invoice->id}") <span class="text-error text-xs">{{ $message }}</span> @enderror
                                                                
                                                                @if(isset($payment_proofs[$invoice->id]))
                                                                    <button wire:click="uploadProof({{ $invoice->id }})" class="btn btn-xs btn-primary mt-1">
                                                                        Upload Bukti
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    @endif

                                                    @if($invoice->status == 'expired')
                                                        <button class="btn btn-xs btn-warning" disabled>Expired - Hubungi Admin</button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-white rounded-lg shadow">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                <h3 class="text-lg font-medium text-gray-900">Belum ada pendaftaran</h3>
                <p class="mt-1 text-gray-500">Anda belum mendaftar di diklat manapun.</p>
                <div class="mt-6">
                    <a href="{{ route('training.index') }}" class="btn btn-primary">Lihat Daftar Diklat</a>
                </div>
            </div>
        @endforelse
    </div>
</div>
