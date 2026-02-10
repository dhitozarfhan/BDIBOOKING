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
                <label class="block font-medium text-sm text-gray-700" for="ktp_image">
                    Upload Foto KTP
                </label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                    <div class="space-y-1 text-center">
                        @if ($ktp_image)
                            <img src="{{ $ktp_image->temporaryUrl() }}" class="mx-auto h-48 w-auto object-cover rounded-md mb-4" />
                        @else
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        @endif
                        
                        <div class="flex text-sm text-gray-600 justify-center">
                            <label for="ktp-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                <span>Upload a file</span>
                                <input id="ktp-upload" wire:model="ktp_image" type="file" class="sr-only" accept="image/*">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">
                            PNG, JPG, GIF up to 10MB
                        </p>
                    </div>
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
