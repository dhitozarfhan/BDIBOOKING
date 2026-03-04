<div class="min-h-screen flex items-center justify-center bg-base-200/30 px-4 py-12">
    <div class="w-full max-w-md">
        {{-- Logo & Header --}}
        <div class="text-center mb-8">
            <a href="{{ url('/') }}" class="inline-block mb-6">
                <img src="{{ asset('images/kemenperin.svg') }}" alt="Kementerian Perindustrian" class="h-16 mx-auto" />
            </a>

            @if($step === 1)
                <h1 class="text-2xl font-bold text-base-content tracking-tight">Pendaftaran Peserta</h1>
                <p class="text-sm text-base-content/50 mt-1">Buat akun untuk mendaftar diklat dan layanan.</p>
            @else
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-base-content tracking-tight">Verifikasi Email</h1>
                <p class="text-sm text-base-content/50 mt-1">
                    Kode OTP telah dikirim ke<br>
                    <span class="font-semibold text-base-content/70">{{ $email }}</span>
                </p>
            @endif
        </div>

        {{-- Card --}}
        <div class="bg-base-100 rounded-3xl border border-base-200/80 shadow-sm p-8">

            @if($step === 1)
                {{-- ══════════════════════════════════════ --}}
                {{-- STEP 1: Registration Form             --}}
                {{-- ══════════════════════════════════════ --}}

                {{-- Flash Messages --}}
                @if (session()->has('error'))
                    <div class="rounded-2xl bg-red-50/80 border border-red-100 p-4 flex items-start gap-3 mb-6">
                        <div class="w-8 h-8 rounded-xl bg-red-100 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                    </div>
                @endif

                <form wire:submit.prevent="sendOtp" class="space-y-5">

                    {{-- Tipe Pendaftar --}}
                    <div class="form-control">
                        <label class="label pb-1">
                            <span class="label-text text-xs font-semibold uppercase tracking-wider text-base-content/50">Mendaftar Sebagai</span>
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="relative cursor-pointer group" wire:click="$set('party_type', 'individual')">
                                <input type="radio" wire:model.live="party_type" value="individual" class="sr-only" />
                                <div class="flex items-center gap-3 p-3 rounded-xl border-2 transition-all duration-200
                                    {{ $party_type === 'individual'
                                        ? 'border-indigo-500 bg-indigo-50 ring-2 ring-indigo-200 shadow-md hover:bg-indigo-100 hover:shadow-lg'
                                        : 'border-base-200/80 hover:border-indigo-300 hover:bg-indigo-50/50 hover:shadow-sm' }}">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-all duration-200 group-hover:scale-110
                                        {{ $party_type === 'individual' ? 'bg-indigo-200' : 'bg-blue-100' }}">
                                        <svg class="w-4 h-4 transition-colors duration-200 {{ $party_type === 'individual' ? 'text-indigo-600' : 'text-blue-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium transition-colors duration-200 {{ $party_type === 'individual' ? 'text-indigo-700 font-semibold' : 'text-base-content/70' }}">Individu</span>
                                </div>
                            </label>
                            <label class="relative cursor-pointer group" wire:click="$set('party_type', 'company')">
                                <input type="radio" wire:model.live="party_type" value="company" class="sr-only" />
                                <div class="flex items-center gap-3 p-3 rounded-xl border-2 transition-all duration-200
                                    {{ $party_type === 'company'
                                        ? 'border-indigo-500 bg-indigo-50 ring-2 ring-indigo-200 shadow-md hover:bg-indigo-100 hover:shadow-lg'
                                        : 'border-base-200/80 hover:border-indigo-300 hover:bg-indigo-50/50 hover:shadow-sm' }}">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-all duration-200 group-hover:scale-110
                                        {{ $party_type === 'company' ? 'bg-indigo-200' : 'bg-purple-100' }}">
                                        <svg class="w-4 h-4 transition-colors duration-200 {{ $party_type === 'company' ? 'text-indigo-600' : 'text-purple-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium transition-colors duration-200 {{ $party_type === 'company' ? 'text-indigo-700 font-semibold' : 'text-base-content/70' }}">Organisasi</span>
                                </div>
                            </label>
                        </div>
                        @error('party_type')
                            <p class="flex items-center gap-1 mt-2 text-xs text-red-500">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z" /></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Nama Organisasi (conditional) --}}
                    @if($party_type === 'company')
                        <div class="form-control">
                            <label class="label pb-1">
                                <span class="label-text text-xs font-semibold uppercase tracking-wider text-base-content/50">Nama Organisasi</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-base-content/30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                </div>
                                <input wire:model="company_name" id="company_name" type="text" placeholder="Misal: PT / CV / Yayasan / Universitas" class="input input-bordered w-full rounded-xl pl-11 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                            </div>
                            @error('company_name')
                                <p class="flex items-center gap-1 mt-2 text-xs text-red-500">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z" /></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    @endif

                    {{-- Nama Lengkap --}}
                    <div class="form-control">
                        <label class="label pb-1">
                            <span class="label-text text-xs font-semibold uppercase tracking-wider text-base-content/50">Nama Lengkap</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-base-content/30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </div>
                            <input wire:model="name" id="name" type="text" placeholder="Masukkan nama lengkap" class="input input-bordered w-full rounded-xl pl-11 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" required />
                        </div>
                        @error('name')
                            <p class="flex items-center gap-1 mt-2 text-xs text-red-500">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z" /></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="form-control">
                        <label class="label pb-1">
                            <span class="label-text text-xs font-semibold uppercase tracking-wider text-base-content/50">Email</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-base-content/30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            </div>
                            <input wire:model="email" id="email" type="email" placeholder="nama@email.com" class="input input-bordered w-full rounded-xl pl-11 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" required />
                        </div>
                        @error('email')
                            <p class="flex items-center gap-1 mt-2 text-xs text-red-500">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z" /></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- No. Telepon --}}
                    <div class="form-control">
                        <label class="label pb-1">
                            <span class="label-text text-xs font-semibold uppercase tracking-wider text-base-content/50">No. Telepon <span class="font-normal normal-case text-base-content/30">(opsional)</span></span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-base-content/30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                            </div>
                            <input wire:model="phone" id="phone" type="tel" placeholder="08xxxxxxxxxx" class="input input-bordered w-full rounded-xl pl-11 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                        </div>
                        @error('phone')
                            <p class="flex items-center gap-1 mt-2 text-xs text-red-500">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z" /></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Password with Strength Indicator --}}
                    <div class="form-control" x-data="{
                        password: '',
                        get strength() {
                            let score = 0;
                            if (this.password.length >= 8) score++;
                            if (/[A-Z]/.test(this.password)) score++;
                            if (/[a-z]/.test(this.password)) score++;
                            if (/[0-9]/.test(this.password)) score++;
                            if (/[^A-Za-z0-9]/.test(this.password)) score++;
                            return score;
                        },
                        get label() {
                            if (!this.password) return '';
                            const labels = ['Sangat Lemah', 'Lemah', 'Cukup', 'Kuat', 'Sangat Kuat'];
                            return labels[Math.max(0, this.strength - 1)] || 'Sangat Lemah';
                        },
                        get color() {
                            const colors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-blue-500', 'bg-emerald-500'];
                            return colors[Math.max(0, this.strength - 1)] || 'bg-red-500';
                        },
                        get textColor() {
                            const colors = ['text-red-600', 'text-orange-600', 'text-yellow-600', 'text-blue-600', 'text-emerald-600'];
                            return colors[Math.max(0, this.strength - 1)] || 'text-red-600';
                        }
                    }">
                        <label class="label pb-1">
                            <span class="label-text text-xs font-semibold uppercase tracking-wider text-base-content/50">Password</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-base-content/30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            </div>
                            <input wire:model="password" id="password" type="password" x-model="password" placeholder="••••••••" class="input input-bordered w-full rounded-xl pl-11 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" required />
                        </div>
                        @error('password')
                            <p class="flex items-center gap-1 mt-2 text-xs text-red-500">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z" /></svg>
                                {{ $message }}
                            </p>
                        @enderror

                        {{-- Strength bar --}}
                        <div x-show="password.length > 0" x-transition class="mt-3">
                            <div class="flex gap-1 mb-1.5">
                                <template x-for="(_, i) in Array(5)">
                                    <div class="h-1.5 flex-1 rounded-full transition-all duration-300"
                                        :class="i < strength ? color : 'bg-base-200'"></div>
                                </template>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-medium transition-colors" :class="textColor" x-text="label"></span>
                                <span class="text-xs text-base-content/30" x-text="strength + '/5'"></span>
                            </div>
                        </div>

                        <ul x-show="password.length > 0" x-transition class="mt-2.5 space-y-1">
                            <li class="flex items-center gap-1.5 text-xs transition-colors" :class="password.length >= 8 ? 'text-emerald-600' : 'text-base-content/30'">
                                <svg x-show="password.length >= 8" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                <svg x-show="password.length < 8" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>
                                Minimal 8 karakter
                            </li>
                            <li class="flex items-center gap-1.5 text-xs transition-colors" :class="/[A-Z]/.test(password) ? 'text-emerald-600' : 'text-base-content/30'">
                                <svg x-show="/[A-Z]/.test(password)" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                <svg x-show="!/[A-Z]/.test(password)" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>
                                Huruf besar (A-Z)
                            </li>
                            <li class="flex items-center gap-1.5 text-xs transition-colors" :class="/[a-z]/.test(password) ? 'text-emerald-600' : 'text-base-content/30'">
                                <svg x-show="/[a-z]/.test(password)" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                <svg x-show="!/[a-z]/.test(password)" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>
                                Huruf kecil (a-z)
                            </li>
                            <li class="flex items-center gap-1.5 text-xs transition-colors" :class="/[0-9]/.test(password) ? 'text-emerald-600' : 'text-base-content/30'">
                                <svg x-show="/[0-9]/.test(password)" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                <svg x-show="!/[0-9]/.test(password)" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>
                                Angka (0-9)
                            </li>
                            <li class="flex items-center gap-1.5 text-xs transition-colors" :class="/[^A-Za-z0-9]/.test(password) ? 'text-emerald-600' : 'text-base-content/30'">
                                <svg x-show="/[^A-Za-z0-9]/.test(password)" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                <svg x-show="!/[^A-Za-z0-9]/.test(password)" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>
                                Simbol (!@#$%^&*)
                            </li>
                        </ul>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="btn w-full bg-indigo-600 hover:bg-indigo-700 border-0 text-white rounded-xl shadow-md hover:shadow-lg transition-all duration-200 mt-2" wire:loading.attr="disabled" wire:target="sendOtp">
                        <span wire:loading.remove wire:target="sendOtp" class="flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            Kirim Kode OTP
                        </span>
                        <span wire:loading wire:target="sendOtp" class="flex items-center justify-center gap-2">
                            <span class="loading loading-spinner loading-sm"></span>
                            Mengirim...
                        </span>
                    </button>
                </form>

                {{-- Divider --}}
                <div class="divider text-xs text-base-content/30 my-6">ATAU</div>

                {{-- Login Link --}}
                <div class="text-center">
                    <p class="text-sm text-base-content/50">
                        Sudah punya akun?
                        <a href="{{ route('participant.login') }}" wire:navigate class="font-semibold text-indigo-600 hover:text-indigo-700 hover:underline transition-colors">
                            Login sekarang
                        </a>
                    </p>
                </div>

            @else
                {{-- ══════════════════════════════════════ --}}
                {{-- STEP 2: OTP Verification              --}}
                {{-- ══════════════════════════════════════ --}}

                <form wire:submit.prevent="verifyOtp" class="space-y-5">
                    <div class="form-control">
                        <label class="label pb-1 justify-center">
                            <span class="label-text text-xs font-semibold uppercase tracking-wider text-base-content/50">Masukkan Kode 6 Digit</span>
                        </label>
                        <input
                            wire:model="otp_input"
                            id="otp_input"
                            type="text"
                            maxlength="6"
                            inputmode="numeric"
                            pattern="[0-9]*"
                            placeholder="000000"
                            autofocus
                            class="input input-bordered w-full text-center text-3xl font-bold tracking-[0.5em] rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 py-4 placeholder-base-content/20"
                        />
                        @error('otp_input')
                            <p class="flex items-center justify-center gap-1 mt-2 text-xs text-red-500">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z" /></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <button type="submit" class="btn w-full bg-indigo-600 hover:bg-indigo-700 border-0 text-white rounded-xl shadow-md hover:shadow-lg transition-all duration-200" wire:loading.attr="disabled" wire:target="verifyOtp">
                        <span wire:loading.remove wire:target="verifyOtp" class="flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            Verifikasi & Daftar
                        </span>
                        <span wire:loading wire:target="verifyOtp" class="flex items-center justify-center gap-2">
                            <span class="loading loading-spinner loading-sm"></span>
                            Memverifikasi...
                        </span>
                    </button>

                    <div class="flex items-center justify-between pt-4 border-t border-base-200/80">
                        <button type="button" wire:click="backToForm" class="text-sm text-base-content/40 hover:text-base-content/70 transition-colors inline-flex items-center gap-1.5 group">
                            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                            Kembali
                        </button>
                        <button type="button" wire:click="resendOtp" class="text-sm text-indigo-600 hover:text-indigo-700 font-semibold transition-colors" wire:loading.attr="disabled" wire:target="resendOtp">
                            <span wire:loading.remove wire:target="resendOtp">Kirim Ulang OTP</span>
                            <span wire:loading wire:target="resendOtp">Mengirim...</span>
                        </button>
                    </div>

                    <p class="text-xs text-base-content/30 text-center">Kode berlaku selama 5 menit.</p>
                </form>
            @endif

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
