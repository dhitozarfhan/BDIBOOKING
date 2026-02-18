<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">


    {{-- Content --}}
    <div>
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

        {{-- Hero section for the training title --}}
        <div class="hero rounded-2xl bg-gradient-to-br from-secondary/10 via-primary/10 to-secondary/5 py-10 shadow-lg border border-base-200">
            <div class="hero-content flex-col w-full max-w-4xl">
                {{-- Back Button + Badge Row --}}
                <div class="flex items-center w-full mb-3">
                    <a href="{{ route('pnbp.index') }}" wire:navigate class="btn btn-ghost btn-sm hover:bg-base-200/50 gap-1 text-base-content/70">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                    <div class="flex-1 flex justify-center">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-secondary/20 text-secondary text-xs font-semibold uppercase tracking-wide">
                            <svg class="w-3 h-3 fill-current" viewBox="0 0 16 16" aria-hidden="true">
                                <path d="M12 4H9.5l-.7-1.4A1 1 0 0 0 7.9 2h-2a1 1 0 0 0-.9.6L4.3 4H2a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V5a1 1 0 0 0-1-1ZM7 11l-3-3h2V6h2v2h2l-3 3Z"/>
                            </svg>
                            Diklat PNBP
                        </div>
                    </div>
                    {{-- Spacer to balance the back button --}}
                    <div class="w-[88px]"></div>
                </div>

                {{-- Title & Description --}}
                <div class="text-center w-full">
                    <h1 class="text-3xl md:text-4xl font-bold leading-tight text-base-content">
                        {{ $training->title }}
                    </h1>
                    <div class="mt-4 text-base-content/70 prose mx-auto">
                        {!! $training->description !!}
                    </div>
                </div>

                {{-- Image Card (middle) --}}
                <div class="card bg-base-100 shadow-xl mt-6 w-full sm:w-96 shrink-0 border border-base-200">
                    <figure class="px-4 pt-4">
                        <img src="{{ $training->image ? Storage::url($training->image) : asset('images/default-training.jpg') }}" alt="{{ $training->title }}" class="rounded-xl w-full h-56 object-cover shadow-sm" />
                    </figure>
                    {{-- Details below image --}}
                    <div class="card-body px-5 py-4">
                        <div class="flex flex-col gap-1 mb-4">
                                <span class="text-lg font-bold text-secondary">{{ 'Rp ' . number_format($training->price, 0, ',', '.') }}</span>
                                <span class="text-sm text-gray-500">Kuota: {{ $training->quota == 0 ? 'Tidak Terbatas' : $training->quota . ' Peserta' }}</span>
                        </div>

                        @if(Auth::guard('participant')->check())
                            @if($isRegistered)
                                <div class="alert alert-success">
                                    <span class="font-bold">Sudah Terdaftar</span>
                                </div>
                                <a href="{{ route('participant.dashboard') }}" class="btn btn-outline btn-block mt-2">Lihat Dashboard</a>
                            @else
                                <button onclick="document.getElementById('confirmRegisterModal').showModal()" class="btn btn-secondary btn-block">
                                    Daftar Sekarang
                                </button>

                                {{-- Confirmation Modal --}}
                                <dialog id="confirmRegisterModal" class="modal modal-bottom sm:modal-middle">
                                    <div class="modal-box sm:rounded-2xl p-8">
                                        {{-- Close Button --}}
                                        <form method="dialog">
                                            <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4">✕</button>
                                        </form>

                                        {{-- Icon --}}
                                        <div class="flex justify-center mb-5">
                                            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center shadow-inner">
                                                <svg class="w-10 h-10 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                        </div>

                                        {{-- Content --}}
                                        <h3 class="text-xl font-bold text-center text-base-content mb-2">Konfirmasi Pendaftaran</h3>
                                        <p class="text-center text-base-content/60 text-sm leading-relaxed mb-8">
                                            Apakah Anda yakin ingin mendaftar diklat PNBP <strong class="text-base-content">{{ $training->title }}</strong>?
                                            <br>Tagihan sebesar <strong class="text-primary">Rp {{ number_format($training->price, 0, ',', '.') }}</strong> akan dibuat setelah pendaftaran.
                                        </p>

                                        {{-- Buttons --}}
                                        <div class="flex gap-3">
                                            <form method="dialog" class="flex-1">
                                                <button class="btn btn-outline btn-block rounded-xl">Batal</button>
                                            </form>
                                            <button wire:click="register" onclick="document.getElementById('confirmRegisterModal').close()" class="btn btn-primary btn-block flex-1 rounded-xl gap-2">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                                Ya, Daftar
                                            </button>
                                        </div>
                                    </div>
                                    <form method="dialog" class="modal-backdrop"><button>close</button></form>
                                </dialog>
                            @endif
                        @else
                            <a href="{{ route('participant.ktp.login') }}" class="btn btn-secondary btn-block">
                                Daftar Sekarang
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Details Card --}}
        <div class="card bg-base-100 shadow-xl mt-8 border border-base-200">
            <div class="card-body space-y-6">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h2 class="card-title text-2xl">Detail Pelaksanaan</h2>
                        <p class="text-sm text-base-content/60">Informasi jadwal dan lokasi.</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Schedule -->
                    <div class="p-4 rounded-xl bg-base-200/60 border border-base-200 flex flex-col gap-2">
                        <span class="text-xs font-semibold uppercase text-base-content/60">Jadwal</span>
                        <div class="flex items-start gap-2 text-sm font-medium">
                            <svg class="w-5 h-5 text-secondary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>{{ $training->start_date->format('d M Y') }} - {{ $training->end_date->format('d M Y') }}</span>
                        </div>
                    </div>
                    
                    <!-- Location -->
                    <div class="p-4 rounded-xl bg-base-200/60 border border-base-200 flex flex-col gap-2">
                        <span class="text-xs font-semibold uppercase text-base-content/60">Lokasi</span>
                        <div class="flex items-start gap-2 text-sm font-medium">
                            <svg class="w-5 h-5 text-secondary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>{{ $training->location }}</span>
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="p-4 rounded-xl bg-base-200/60 border border-base-200 flex flex-col gap-2">
                            <span class="text-xs font-semibold uppercase text-base-content/60">Investasi</span>
                            <div class="flex items-start gap-2 text-sm font-medium">
                            <svg class="w-5 h-5 text-secondary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v.01" />
                            </svg>
                            <span>{{ 'Rp ' . number_format($training->price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
