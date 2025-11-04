<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center mb-10">
        <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">Pendaftaran via Intranet Kemenperin</h1>
        <p class="mt-4 text-lg leading-8 text-gray-600">Akses cepat untuk pegawai Kemenperin.</p>
    </div>

    <div class="grid md:grid-cols-2 gap-12 items-start">
        <div class="prose prose-lg text-gray-700">
            <div role="alert" class="alert alert-info bg-blue-50 border-l-4 border-blue-400 text-blue-700 p-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <h3 class="font-bold">Informasi Penting!</h3>
                    <ol class="list-decimal list-inside text-sm space-y-2 mt-2">
                        <li>Masukkan username dan password Intranet Anda.</li>
                        <li>Setelah login berhasil, Anda akan diarahkan ke formulir pendaftaran yang sebagian datanya sudah terisi.</li>
                        <li>Harap periksa kembali semua data sebelum mengirimkan pendaftaran.</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-2xl border border-gray-200/80">
            <div class="card-body p-8">
                <h2 class="card-title text-2xl font-bold mb-4">Login Intranet</h2>
                <form wire:submit.prevent="submit" class="space-y-6">
                    @if($error)
                        <div role="alert" class="alert alert-error text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span>{{ $error }}</span>
                        </div>
                    @endif

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Username</span>
                        </label>
                        <input type="text" wire:model.lazy="username" class="input input-bordered w-full" />
                        @error('username') <span class="text-error text-sm mt-2">{{ $message }}</span> @enderror
                    </div>

                    <div x-data="{ show: false }" class="form-control">
                        <label class="label">
                            <span class="label-text">Password</span>
                        </label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" wire:model.lazy="password" class="input input-bordered w-full pr-10" />
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                <svg x-show="!show" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.022 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                </svg>
                                <svg x-show="show" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2 2 0 012.828 2.828l1.515 1.515A4 4 0 0014 10a4 4 0 10-5.932-3.732zM10 15a5 5 0 005-5c0-.463-.063-.91-.179-1.335L10.82 12.82A3 3 0 017.18 9.18L5.665 7.665A5.003 5.003 0 005 10c0 2.761 2.239 5 5 5z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                        @error('password') <span class="text-error text-sm mt-2">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-control mt-8">
                        <button type="submit" class="btn btn-primary btn-lg w-full">
                            <span wire:loading.remove wire:target="submit">Login & Lanjutkan</span>
                            <span wire:loading wire:target="submit" class="loading loading-spinner"></span>
                            <span wire:loading wire:target="submit">Memproses...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
