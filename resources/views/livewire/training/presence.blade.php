@php
    $training = $diklat ?? [];

    $startDate = !empty($training['tgl_mulai']) ? \Carbon\Carbon::parse($training['tgl_mulai']) : null;
    $endDate = !empty($training['tgl_selesai']) ? \Carbon\Carbon::parse($training['tgl_selesai']) : null;

    if ($startDate) {
        $startDate->locale(app()->getLocale());
    }
    if ($endDate) {
        $endDate->locale(app()->getLocale());
    }

    $schedule = '-';
    if ($startDate && $endDate) {
        if ($startDate->isSameDay($endDate)) {
            $schedule = $startDate->translatedFormat('j F Y');
        } elseif ($startDate->isSameMonth($endDate) && $startDate->isSameYear($endDate)) {
            $schedule = $startDate->translatedFormat('j') . ' - ' . $endDate->translatedFormat('j F Y');
        } else {
            $schedule = $startDate->translatedFormat('j F Y') . ' - ' . $endDate->translatedFormat('j F Y');
        }
    } elseif ($startDate) {
        $schedule = $startDate->translatedFormat('j F Y');
    }

    $presenceTimestamp = null;
    $presenceLabel = null;
    if (!empty($localDatetime)) {
        try {
            $presenceTimestamp = \Carbon\Carbon::parse($localDatetime)->locale(app()->getLocale());
            $presenceLabel = $presenceTimestamp->translatedFormat('l, j F Y H:i');
        } catch (\Exception $e) {
            $presenceTimestamp = null;
        }
    }

    $locationParts = [];
    if (!empty($desa['desa'])) {
        $locationParts[] = $desa['desa'];
    }
    if (!empty($desa['kecamatan'])) {
        $locationParts[] = $desa['kecamatan'];
    }
    if (!empty($desa['kota'])) {
        $locationParts[] = $desa['kota'];
    }
    if (!empty($desa['provinsi'])) {
        $locationParts[] = $desa['provinsi'];
    }
    if (empty($locationParts) && !empty($training['tempat'])) {
        $locationParts[] = $training['tempat'];
    }
    if (empty($locationParts) && !empty($training['tempat_kota'])) {
        $locationParts[] = $training['tempat_kota'];
    }
    $location = !empty($locationParts) ? implode(', ', $locationParts) : '-';

    $presenceAvailable = $this->presenceAvailable;
@endphp

<div class="space-y-10">
    <div class="flex flex-col items-center justify-between gap-6 text-center sm:flex-row sm:text-left">
        <div class="flex flex-col items-center gap-3 sm:flex-row">
            <img src="{{ asset('images/kemenperin.svg') }}" alt="Kementerian Perindustrian" class="h-14">
            <img src="{{ asset('images/bdi-yogyakarta-corpu.svg') }}" alt="Balai Diklat Industri Yogyakarta" class="h-14 sm:h-16">
        </div>
        <a href="{{ route('home') }}" class="btn btn-success btn-wide sm:btn-md">
            Kembali ke Beranda
        </a>
    </div>

    @if ($successMessage)
        <div role="alert" class="alert alert-success shadow-lg">
            <span>{{ $successMessage }}</span>
        </div>
    @endif

    @if ($error)
        <div role="alert" class="alert alert-error shadow-lg">
            <span>{{ $error }}</span>
        </div>
    @endif

    <div class="grid gap-8 lg:grid-cols-[minmax(0,1.2fr)_minmax(0,0.9fr)]">
        <div class="space-y-6">
            <div class="rounded-3xl border border-base-200 bg-base-100 shadow-xl">
                <div class="divide-y divide-base-200">
                    <div class="flex items-start gap-4 p-6">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 text-primary">
                            <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M4.5 5.5h6a2 2 0 0 1 2 2v11a2 2 0 0 0-2-2h-6z"></path>
                                <path d="M19.5 5.5h-6a2 2 0 0 0-2 2v11a2 2 0 0 1 2-2h6z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-base-content/60">Nama Pelatihan</p>
                            <p class="mt-1 text-lg font-semibold text-base-content">{{ $training['nama_jenis'] ?? $training['nama_lengkap'] ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 p-6">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-secondary/10 text-secondary">
                            <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5">
                                <rect x="4.5" y="4.5" width="15" height="15" rx="2"></rect>
                                <path d="M9 9h2v2H9zM13 9h2v2h-2zM9 13h2v2H9zM13 13h2v2h-2z"></path>
                                <path d="M12 19.5V15"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-base-content/60">Penyelenggara</p>
                            <p class="mt-1 text-lg font-semibold text-base-content">{{ $training['penyelenggara'] ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 p-6">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-warning/10 text-warning">
                            <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M7 5.5V3.5"></path>
                                <path d="M17 5.5V3.5"></path>
                                <path d="M4.5 8.5h15"></path>
                                <rect x="4.5" y="5.5" width="15" height="15" rx="2"></rect>
                                <path d="M9 12h3"></path>
                                <path d="M12 12v3"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-base-content/60">Tanggal Pelaksanaan</p>
                            <p class="mt-1 text-lg font-semibold text-base-content">{{ $schedule }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 p-6">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-info/10 text-info">
                            <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"></path>
                                <path d="M12 14c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z"></path>
                                <path d="M12 22c4.418-4.667 6-7.667 6-10.5A6 6 0 0 0 6 11.5C6 14.333 7.582 17.333 12 22z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-base-content/60">Tempat Pelaksanaan</p>
                            <p class="mt-1 text-lg font-semibold text-base-content">{{ $training['tempat'] }}</p>
                        </div>
                    </div>
                    @if(!empty($training['id_uraian_kompetensi']))
                        <div class="flex items-start gap-4 p-6">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-accent/10 text-accent">
                                <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M12 3l2.7 5.5 6.3.9-4.5 4.4 1 6.2L12 17.8 6.5 20l1-6.2-4.5-4.4 6.3-.9z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-base-content/60">Kompetensi</p>
                                <p class="mt-1 text-lg font-semibold text-base-content">{{ $training['id_uraian_kompetensi'] }}</p>
                            </div>
                        </div>
                    @endif
                    @if(!empty($training['uraian_skema']))
                        <div class="flex items-start gap-4 p-6">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-base-300/40 text-base-content">
                                <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M4.5 6.5h15v11h-15z"></path>
                                    <path d="M9.5 6.5v11"></path>
                                    <path d="M14.5 6.5v11"></path>
                                    <path d="M4.5 11.5h15"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-base-content/60">Skema</p>
                                <p class="mt-1 text-lg font-semibold text-base-content">{{ $training['uraian_skema'] }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-3xl border border-base-200 bg-base-100 shadow-xl">
                <div class="space-y-4 p-6">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-primary text-primary-content">
                            <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M12 8v4l3 3"></path>
                                <circle cx="12" cy="12" r="9"></circle>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-base-content">Presensi Diklat</h2>
                            <p class="text-sm text-base-content/70">
                                {{ $presenceLabel ?? 'Waktu lokal tidak tersedia' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 text-sm text-base-content/70">
                        <svg viewBox="0 0 24 24" class="mt-1 h-5 w-5 text-primary" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M12 2C7.029 2 3 6.029 3 11c0 6.25 6.441 10.815 8.602 11.964a1 1 0 0 0 .96 0C14.559 21.815 21 17.25 21 11 21 6.029 16.971 2 12 2z"></path>
                            <path d="M12 13a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"></path>
                        </svg>
                        <span>{{ $location }}</span>
                    </div>
                    @if(!$presenceAvailable)
                        <div role="alert" class="alert alert-warning shadow-sm">
                            @if ($dateEnablePresence !== 'Y')
                                Presensi hanya tersedia pada tanggal pelaksanaan diklat.
                            @elseif ($hourEnablePresence !== 'Y')
                                Presensi hanya dapat dilakukan antara jam 05.00 s.d. 22.59{{ $localTimezone ? ' (GMT +' . ltrim($localTimezone, '+') . ')' : '' }}.
                            @else
                                Presensi belum dapat dilakukan saat ini. Silakan coba lagi nanti.
                            @endif
                        </div>
                    @endif
                </div>
                <div class="rounded-b-3xl border-t border-base-200 bg-base-200/60 p-6">
                    <div class="rounded-2xl border border-error bg-error text-error-content">
                        <div class="rounded-t-2xl bg-error text-error-content">
                            <div class="flex items-center gap-2 px-5 py-3 font-semibold uppercase tracking-wide">
                                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M12 9v4"></path>
                                    <path d="M12 17h.01"></path>
                                    <path d="M10.29 3.86 1.82 18a1 1 0 0 0 .86 1.5h18.64a1 1 0 0 0 .86-1.5L13.71 3.86a1 1 0 0 0-1.72 0z"></path>
                                </svg>
                                Autentikasi Presensi
                            </div>
                        </div>
                        <div class="space-y-5 rounded-b-2xl bg-base-100 p-6 text-base-content">
                            <div wire:loading.flex wire:target="submit" class="flex flex-col items-center justify-center gap-3 rounded-xl border border-dashed border-base-300 bg-base-200/70 py-8 text-sm font-medium text-base-content/70">
                                <span class="loading loading-lg loading-spinner text-primary"></span>
                                <span>Mengirim presensi...</span>
                            </div>

                            <form
                                wire:submit.prevent="submit"
                                data-presence-signature-form
                                wire:loading.remove
                                wire:target="submit"
                                class="space-y-5"
                            >
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text text-base-content/80">ID User <span class="text-error">*</span></span>
                                    </label>
                                    <label class="input input-bordered flex items-center gap-3 w-full @error('username') input-error @enderror">
                                        <svg viewBox="0 0 24 24" class="h-5 w-5 text-base-content/70" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5z"></path>
                                            <path d="M3 21a9 9 0 0 1 18 0"></path>
                                        </svg>
                                        <input
                                            type="text"
                                            autocomplete="username"
                                            wire:model.defer="username"
                                            class="grow"
                                        />
                                    </label>
                                    @error('username')
                                        <label class="label">
                                            <span class="label-text-alt text-error">{{ $message }}</span>
                                        </label>
                                    @enderror
                                </div>

                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text text-base-content/80">Password <span class="text-error">*</span></span>
                                    </label>
                                    <label class="input input-bordered flex items-center gap-3 w-full @error('password') input-error @enderror">
                                        <svg viewBox="0 0 24 24" class="h-5 w-5 text-base-content/70" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path d="M12 17v-3"></path>
                                            <rect x="5" y="10" width="14" height="11" rx="2"></rect>
                                            <path d="M8 10V7a4 4 0 0 1 8 0v3"></path>
                                        </svg>
                                        <input
                                            type="password"
                                            autocomplete="current-password"
                                            wire:model.defer="password"
                                            class="grow"
                                        />
                                    </label>
                                    @error('password')
                                        <label class="label">
                                            <span class="label-text-alt text-error">{{ $message }}</span>
                                        </label>
                                    @enderror
                                </div>

                                <div class="space-y-3">
                                    <div class="flex flex-col gap-1">
                                        <span class="text-sm font-semibold text-base-content/80">Isi Tanda Tangan Anda di Bawah Ini <span class="text-error">*</span></span>
                                        <span class="text-xs text-base-content/60">Gunakan mouse, stylus, atau layar sentuh untuk menandatangani.</span>
                                    </div>
                                    <div class="rounded-2xl border border-dashed border-base-300 bg-base-200/70 p-4" wire:ignore x-data="presenceSignaturePadComponent()" x-init="init()">
                                        <div class="mb-3 flex items-center justify-between gap-3">
                                            <span class="text-xs text-base-content/60">Pastikan tanda tangan terlihat jelas sebelum mengirim presensi.</span>
                                            <div class="flex items-center gap-2">
                                                <button type="button" class="btn btn-warning btn-xs" x-on:click.prevent="undo()">
                                                    Undo
                                                </button>
                                                <button type="button" class="btn btn-xs btn-outline text-error" x-on:click.prevent="clear()">
                                                    Ulangi
                                                </button>
                                            </div>
                                        </div>
                                        <div class="relative">
                                            <canvas x-ref="canvas" class="h-44 w-full rounded-xl bg-base-100 shadow-sm" style="touch-action: none;"></canvas>
                                            <div class="pointer-events-none absolute inset-0 rounded-xl border-2 border-dashed border-base-300/80"></div>
                                        </div>
                                        <input type="hidden" x-ref="hidden" name="signature" wire:model.defer="signature" />
                                    </div>
                                    @error('signature')
                                        <span class="text-xs text-error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary w-full" @if(!$presenceAvailable) disabled @endif>
                                    Submit Presensi
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@pushOnce('scripts', 'presence-signature-scripts')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@5.1.1/dist/signature_pad.umd.min.js"></script>
<script>
    window.presenceSignaturePadComponent = window.presenceSignaturePadComponent || function () {
        return {
            signaturePad: null,
            canvas: null,
            hiddenInput: null,
            syncFn: null,
            init() {
                this.canvas = this.$refs.canvas;
                this.hiddenInput = this.$refs.hidden;

                this.resizeCanvas();

                this.signaturePad = new SignaturePad(this.canvas, {
                    penColor: '#1f2937',
                    backgroundColor: 'rgba(255,255,255,0)',
                    minWidth: 0.75,
                    maxWidth: 2.5,
                });

                if (this.hiddenInput.value) {
                    try {
                        this.signaturePad.fromDataURL(this.hiddenInput.value);
                    } catch (error) {
                        console.warn('Gagal memuat ulang tanda tangan tersimpan.', error);
                        this.signaturePad.clear();
                    }
                }

                this.signaturePad.onEnd = () => this.sync();
                window.addEventListener('resize', this.debounce(() => this.resizeCanvas()));

                this.registerSync();
                this.sync();
            },
            debounce(func, wait = 100) {
                let timeout;
                return (...args) => {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        func.apply(this, args);
                    }, wait);
                };
            },
            resizeCanvas() {
                if (!this.canvas) {
                    return;
                }

                const data = this.signaturePad ? this.signaturePad.toData() : null;

                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                const width = this.canvas.clientWidth;
                const height = this.canvas.clientHeight;

                this.canvas.width = width * ratio;
                this.canvas.height = height * ratio;
                this.canvas.getContext('2d').scale(ratio, ratio);

                if (this.signaturePad && data) {
                    this.signaturePad.fromData(data);
                }
            },
            undo() {
                if (!this.signaturePad) {
                    return;
                }
                const data = this.signaturePad.toData();
                if (data.length) {
                    data.pop();
                    this.signaturePad.fromData(data);
                    this.sync();
                }
            },
            clear() {
                if (!this.signaturePad) {
                    return;
                }
                this.signaturePad.clear();
                this.sync();
            },
            registerSync() {
                window.__presenceSignatureSyncers = window.__presenceSignatureSyncers || new Set();
                if (this.syncFn) {
                    window.__presenceSignatureSyncers.delete(this.syncFn);
                }
                this.syncFn = () => this.sync();
                window.__presenceSignatureSyncers.add(this.syncFn);
            },
            sync() {
                if (!this.hiddenInput) {
                    return;
                }
                const value = this.signaturePad && !this.signaturePad.isEmpty()
                    ? this.signaturePad.toDataURL('image/png')
                    : '';

                if (this.hiddenInput.value !== value) {
                    this.hiddenInput.value = value;
                    this.hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
                }
            },
        };
    };

    (function bindPresenceSignatureFormListener() {
        const attach = () => {
            const form = document.querySelector('[data-presence-signature-form]');
            if (!form || form.dataset.presenceSignatureBound) {
                return;
            }

            form.dataset.presenceSignatureBound = 'true';
            form.addEventListener('submit', () => {
                if (!window.__presenceSignatureSyncers) {
                    return;
                }
                window.__presenceSignatureSyncers.forEach((fn) => {
                    try {
                        fn();
                    } catch (error) {
                        console.warn('Gagal menyelaraskan tanda tangan sebelum submit.', error);
                    }
                });
            }, { capture: true });
        };

        document.addEventListener('DOMContentLoaded', attach);
        document.addEventListener('livewire:navigated', attach);
    })();
</script>
@endPushOnce
