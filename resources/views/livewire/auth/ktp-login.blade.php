@push('styles')
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
    <link href="https://unpkg.com/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.css" rel="stylesheet">
    <style>
        .filepond--root { font-family: inherit; }
        .filepond--panel-root { background-color: #f3f4f6; border: 2px dashed #d1d5db; }
        .filepond--drop-label { color: #4b5563; }
    </style>
@endpush

<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        <h2 class="text-2xl font-bold text-center mb-6">Masuk / Daftar dengan KTP</h2>
        <p class="text-center text-gray-600 mb-6">
            Upload foto KTP Anda untuk masuk atau mendaftar secara otomatis.
        </p>

        @if (session()->has('error'))
            <div class="mb-4 text-red-600 font-bold text-center">
                {{ session('error') }}
            </div>
        @endif

        <form wire:submit.prevent="scan">
            <div class="mb-4">
                <label class="block font-medium text-sm text-gray-700 mb-2" for="ktp_image">
                    Upload Foto KTP
                </label>
                
                <div wire:ignore
                    x-data="{
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
                                        @this.upload('ktp_image', file, load, error, progress)
                                    },
                                    revert: (filename, load) => {
                                        @this.removeUpload('ktp_image', filename, load)
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
                                labelIdle: 'Upload KTP <br/> <span class=\"text-xs text-gray-500\">Tarik & Letakkan atau Klik</span>'
                            });
                        }
                    }"
                >
                    <input type="file" x-ref="input" accept="image/jpeg, image/png, image/jpg" />
                </div>
                @error('ktp_image') <span class="text-red-600 text-sm block mt-2">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 mr-4" href="{{ route('participant.login') }}">
                    Login Manual (Tanpa KTP)
                </a>

                <button type="submit" wire:loading.attr="disabled" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <span wire:loading.remove wire:target="scan">Proses KTP</span>
                    <span wire:loading wire:target="scan">Memproses...</span>
                </button>
            </div>
        </form>
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
@endpush
