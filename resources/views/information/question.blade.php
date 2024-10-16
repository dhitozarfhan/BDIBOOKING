<x-app-layout>
    <div class="container flex mx-auto px-4 mt-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
            <div class="lg:col-span-8">
                <div class=" bg-white p-6 rounded-lg shadow-md w-full mb-16">
                    <h1 class="text-4xl font-bold mb-4">Pertanyaan / Pengaduan Masyarakat</h1>
                    <div class="bg-blue-100 text-blue-800 p-4 rounded-md mb-6">
                        Jika anda mempunyai pertanyaan, atau saran dan kritik yang ingin disampaikan kepada kami,
                        silakan isi form berikut
                        kemudian kirim, maka kami akan berusaha menjawab keluhan Anda dengan segera.
                    </div>
                    <form action="{{ route('question.submit') }}" method="POST" class="my-8">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Subject --}}
                            <div class="form-group">
                                <label for="subject" class="block text-gray-700">
                                    <i class="fas fa-bullhorn"></i>
                                    Topik / Judul
                                </label>
                                <div class="flex items-center">
                                    <input type="text" name="subject" value="{{ old('subject') }}"
                                        class="form-control w-full p-2 border border-gray-300 rounded-md" />
                                </div>
                                @error('subject')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- Name --}}
                            <div class="form-group">
                                <label for="name" class="block text-gray-700">
                                    <i class="fas fa-user"></i>
                                    Nama
                                </label>
                                <div class="flex items-center">
                                    <input type="text" name="name" value="{{ old('name') }}"
                                        class="form-control w-full p-2 border border-gray-300 rounded-md" />
                                </div>
                                @error('name')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- Message --}}
                            <div class="form-group md:col-span-2">
                                <label for="content" class="block text-gray-700">
                                    <i class="fas fa-question"></i>
                                    Pertanyaan / Pesan
                                </label>
                                <div class="flex items-center">
                                    <textarea name="content" class="form-control w-full p-2 border border-gray-300 rounded-md h-32">
                                        {{ old('content') }}</textarea>
                                </div>
                                @error('content')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- Mobile --}}
                            <div class="form-group">
                                <label for="mobile" class="block text-gray-700">
                                    <i class="fas fa-phone"></i>
                                    Mobilephone
                                </label>
                                <div class="flex items-center">
                                    <input type="text" name="mobile" value="{{ old('mobile') }}"
                                        class="form-control w-full p-2 border border-gray-300 rounded-md" />
                                </div>
                                @error('mobile')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- Email --}}
                            <div class="form-group">
                                <label for="email" class="block text-gray-700">
                                    <i class="fas fa-envelope"></i>
                                    Email
                                </label>
                                <div class="flex items-center">
                                    <input type="email" name="email" value="{{ old('email') }}"
                                        class="form-control w-full p-2 border border-gray-300 rounded-md" />
                                </div>
                                @error('email')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- Captcha
                            <div class="row mb-4">
                                <div class="md:w-1/2">
                                    <div class="form-group">
                                        <label for="g-recaptcha-response" class="block text-gray-700">Ketik Kode Pengaman</label>
                                        <div class="flex items-center">
                                            <i class="fas fa-barcode mr-2"></i>
                                            <div class="g-recaptcha" data-sitekey="{{ env('NOCAPTCHA_SITEKEY') }}"></div>
                                        </div>
                                        @error('g-recaptcha-response')
                                            <span class="text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="md:w-1/2">
                                    <label class="block text-gray-700">
                                        <a id="other_captcha" href="javascript:void(0);" onclick="grecaptcha.reset()">
                                            <i class="fas fa-sync"></i> Minta gambar lain
                                        </a>
                                    </label>
                                    <div id="captcha-loading">
                                        <span id="captcha-image">{!! generate_captcha() !!}</span>
                                    </div>
                                </div>
                            </div> --}}
                            {{-- Submit Button --}}
                            <div class="w-full">
                                <button type="submit" class="btn btn-primary w-full md:w-auto">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="lg:col-span-4">
                @include('components.information-sidebar')
            </div>
        </div>
    </div>
@push('scripts')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endpush
</x-app-layout>
