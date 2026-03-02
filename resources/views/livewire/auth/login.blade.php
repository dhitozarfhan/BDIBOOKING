<div class="min-h-screen flex items-center justify-center bg-base-200/30 px-4 py-12">
    <div class="w-full max-w-md">
        {{-- Logo & Header --}}
        <div class="text-center mb-8">
            <a href="{{ url('/') }}" class="inline-block mb-6">
                <img src="{{ asset('images/kemenperin.svg') }}" alt="Kementerian Perindustrian" class="h-16 mx-auto" />
            </a>
            <h1 class="text-2xl font-bold text-base-content tracking-tight">Login Peserta</h1>
            <p class="text-sm text-base-content/50 mt-1">Masuk ke akun Anda untuk melihat diklat yang diikuti.</p>
        </div>

        {{-- Card --}}
        <div class="bg-base-100 rounded-3xl border border-base-200/80 shadow-sm p-8">
            {{-- Flash Messages --}}
            @if (session()->has('success'))
                <div class="rounded-2xl bg-emerald-50/80 border border-emerald-100 p-4 flex items-start gap-3 mb-6">
                    <div class="w-8 h-8 rounded-xl bg-emerald-100 flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <p class="text-sm text-emerald-700 font-medium">{{ session('success') }}</p>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="rounded-2xl bg-red-50/80 border border-red-100 p-4 flex items-start gap-3 mb-6">
                    <div class="w-8 h-8 rounded-xl bg-red-100 flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            @endif

            <form wire:submit.prevent="login" class="space-y-5">
                {{-- Email --}}
                <div class="form-control">
                    <label class="label pb-1">
                        <span class="label-text text-xs font-semibold uppercase tracking-wider text-base-content/50">Email</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-base-content/30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        </div>
                        <input wire:model="email" id="email" type="email" placeholder="nama@email.com" class="input input-bordered w-full rounded-xl pl-11 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" required autofocus />
                    </div>
                    @error('email')
                        <p class="flex items-center gap-1 mt-2 text-xs text-red-500">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z" /></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="form-control">
                    <label class="label pb-1">
                        <span class="label-text text-xs font-semibold uppercase tracking-wider text-base-content/50">Password</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-base-content/30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        </div>
                        <input wire:model="password" id="password" type="password" placeholder="••••••••" class="input input-bordered w-full rounded-xl pl-11 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" required autocomplete="current-password" />
                    </div>
                    @error('password')
                        <p class="flex items-center gap-1 mt-2 text-xs text-red-500">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z" /></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Login Button --}}
                <button type="submit" class="btn w-full bg-indigo-600 hover:bg-indigo-700 border-0 text-white rounded-xl shadow-md hover:shadow-lg transition-all duration-200 mt-2" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="login" class="flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                        Masuk
                    </span>
                    <span wire:loading wire:target="login" class="flex items-center justify-center gap-2">
                        <span class="loading loading-spinner loading-sm"></span>
                        Memproses...
                    </span>
                </button>
            </form>

            {{-- Divider --}}
            <div class="divider text-xs text-base-content/30 my-6">ATAU</div>

            {{-- Register Link --}}
            <div class="text-center">
                <p class="text-sm text-base-content/50">
                    Belum punya akun?
                    <a href="{{ route('participant.register') }}" wire:navigate class="font-semibold text-indigo-600 hover:text-indigo-700 hover:underline transition-colors">
                        Daftar sekarang
                    </a>
                </p>
            </div>
        </div>

        {{-- Back to Home --}}
        <div class="text-center mt-6">
            <a href="{{ url('/') }}" wire:navigate class="inline-flex items-center gap-1.5 text-sm text-base-content/40 hover:text-base-content/70 transition-colors group">
                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
