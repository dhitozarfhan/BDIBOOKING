@push('styles')
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
    <link href="https://unpkg.com/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.css" rel="stylesheet">
    <style>
        .filepond--root { font-family: inherit; margin-bottom: 0; }
        .filepond--panel-root { background-color: transparent; border: 2px dashed rgba(99, 102, 241, 0.3); border-radius: 1rem; transition: all 0.2s; }
        .filepond--panel-root:hover { border-color: rgba(99, 102, 241, 0.6); background-color: rgba(99, 102, 241, 0.03); }
        .filepond--drop-label { color: #6b7280; min-height: 120px; padding: 1rem; }
        .filepond--drop-label label { font-size: 0.875rem; }
        .filepond--label-action { color: #6366f1; font-weight: 600; text-decoration: none; }
        .filepond--label-action:hover { text-decoration: underline; }
        .filepond--credits { display: none !important; }
    </style>
@endpush

<div class="min-h-screen flex items-center justify-center bg-base-200/30 px-4 py-12">
    <div class="w-full max-w-md">
        {{-- Logo & Header --}}
        <div class="text-center mb-8">
            <a href="{{ url('/') }}" class="inline-block mb-6">
                <img src="{{ asset('images/kemenperin.svg') }}" alt="Kementerian Perindustrian" class="h-16 mx-auto" />
            </a>
            <h1 class="text-2xl font-bold text-base-content tracking-tight">Masuk / Daftar dengan KTP</h1>
            <p class="text-sm text-base-content/50 mt-2 leading-relaxed">
                Upload foto KTP Anda untuk masuk atau mendaftar secara otomatis.
            </p>
        </div>

        {{-- Card --}}
        <div class="bg-base-100 rounded-3xl border border-base-200/80 shadow-sm p-8">
            {{-- Flash Messages --}}
            @if (session()->has('error'))
                <div class="rounded-2xl bg-red-50/80 border border-red-100 p-4 flex items-start gap-3 mb-6">
                    <div class="w-8 h-8 rounded-xl bg-red-100 flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            @endif

            @if (session()->has('success'))
                <div class="rounded-2xl bg-emerald-50/80 border border-emerald-100 p-4 flex items-start gap-3 mb-6">
                    <div class="w-8 h-8 rounded-xl bg-emerald-100 flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <p class="text-sm text-emerald-700 font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <form wire:submit.prevent="scan">
                {{-- KTP Upload --}}
                <div class="mb-6">
                    <label class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500/15 to-purple-500/15 flex items-center justify-center">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" /></svg>
                        </div>
                        <span class="text-sm font-semibold text-base-content">Upload Foto KTP</span>
                    </label>

                    {{-- Info Tip --}}
                    <div class="rounded-xl bg-indigo-50/60 border border-indigo-100 p-3.5 mb-4">
                        <div class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-indigo-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <p class="text-xs text-indigo-700/80 leading-relaxed">
                                Pastikan foto KTP jelas, tidak buram, dan semua informasi terbaca dengan baik.
                            </p>
                        </div>
                    </div>

                    <div wire:ignore x-data="filepondKtp()">
                        <input type="file" x-ref="input" accept="image/jpeg, image/png, image/jpg" />
                    </div>
                    @error('ktp_image')
                        <p class="flex items-center gap-1 mt-2 text-xs text-red-500">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z" /></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <button type="submit" wire:loading.attr="disabled" class="btn w-full bg-indigo-600 hover:bg-indigo-700 border-0 text-white rounded-xl shadow-md hover:shadow-lg transition-all duration-200">
                    <span wire:loading.remove wire:target="scan" class="flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                        Proses KTP
                    </span>
                    <span wire:loading wire:target="scan" class="flex items-center justify-center gap-2">
                        <span class="loading loading-spinner loading-sm"></span>
                        Memproses KTP...
                    </span>
                </button>
            </form>

            {{-- Divider --}}
            <div class="divider text-xs text-base-content/30 my-6">ATAU</div>

            {{-- Manual Login Link --}}
            <div class="text-center">
                <a href="{{ route('participant.login') }}" wire:navigate class="btn btn-ghost btn-block rounded-xl text-base-content/60 hover:text-base-content gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    Login Manual (Tanpa KTP)
                </a>
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

@push('scripts')
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-crop/dist/filepond-plugin-image-crop.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-transform/dist/filepond-plugin-image-transform.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('filepondKtp', () => ({
                pond: null,
                init() {
                    FilePond.registerPlugin(
                        FilePondPluginImagePreview,
                        FilePondPluginImageExifOrientation,
                        FilePondPluginFileValidateSize,
                        FilePondPluginFileValidateType,
                        FilePondPluginImageCrop,
                        FilePondPluginImageEdit,
                        FilePondPluginImageTransform
                    );
                    
                    this.pond = FilePond.create(this.$refs.input, {
                        allowMultiple: false,
                        server: {
                            process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                                this.$wire.upload('ktp_image', file, load, error, progress)
                            },
                            revert: (filename, load) => {
                                this.$wire.removeUpload('ktp_image', filename, load)
                            },
                        },
                        allowImagePreview: true,
                        imagePreviewMinHeight: 150,
                        imagePreviewMaxHeight: 250,
                        allowImageCrop: true,
                        imageCropAspectRatio: '856:539',
                        allowImageTransform: true,
                        acceptedFileTypes: ['image/jpeg', 'image/png', 'image/jpg'],
                        maxFileSize: '10MB',
                        labelIdle: '<div class="flex flex-col items-center gap-2 py-2"><svg class="w-8 h-8 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg><span class="text-sm font-medium text-gray-600">Tarik foto KTP ke sini</span><span class="text-xs text-gray-400">atau <span class="filepond--label-action">klik untuk memilih</span></span></div>'
                    });
                }
            }));
        });
    </script>
@endpush
