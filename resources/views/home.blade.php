<x-app-layout>
    @section('hero')
        <div class="w-3/4 text-center mx-auto py-10">
            <x-carousel />
        </div>
    @endsection
    <hr>

    <div class="flex items-center justify-center md:px-40" x-data="{ shown: false }" x-intersect="shown = true">
        <img src="{{ asset('images/illustrasi-pelatihan.png') }}" alt="Pelatihan" class="w-32 md:w-72 mb-4 md:mb-0"
            x-show="shown" x-transition.scale.duration.1500ms>
        <div class="ml-0 md:ml-6 mt-4 md:mt-10" x-show="shown" x-transition.duration.1500ms>
            <h2 class="text-xl md:text-3xl font-bold mb-2">Diklat Industri Berbasis Kompetensi</h2>
            <p class="text-gray-700 text-sm md:text-lg mb-4 md:mb-10">
                Peserta dilatih, disertifikasi, dan langsung ditempatkan bekerja di industri.
            </p>
            <button class="btn bg-blue-500 text-white">Selengkapnya</button>
        </div>
    </div>

    <div class="flex items-center justify-center md:px-40">
        <div class="mr-0 md:ml-6 mt-4 md:mt-10 text-right">
            <h2 class="text-xl md:text-3xl font-bold mb-2">Inkubator Bisnis</h2>
            <p class="text-gray-700 text-sm md:text-lg mb-4 md:mb-10">
                Inkubator Bisnis Pazti Bisa (IBIZA) menumbuhkan wirausaha infustri baru.
            </p>
            <button class="btn bg-green-500 text-white">Selengkapnya</button>
        </div>
        <img src="{{ asset('images/illustrasi-ibiza.png') }}" alt="Pelatihan" class="w-32 md:w-72 mb-4 md:mb-0">
    </div>

    <div class="flex items-center justify-center md:px-40">
        <img src="{{ asset('images/illustrasi-pelatihan.png') }}" alt="Pelatihan" class="w-32 md:w-72 mb-4 md:mb-0">
        <div class="ml-0 md:ml-6 mt-4 md:mt-10">
            <h2 class="text-xl md:text-3xl font-bold mb-2">Infrastruktur Kompetensi</h2>
            <p class="text-gray-700 text-sm md:text-lg mb-4 md:mb-10">
                Disusun oleh praktisi industri dan sesuai dengan kebutuhan saat ini.
            </p>
            <button class="btn bg-red-500 text-white">Selengkapnya</button>
        </div>
    </div>

    <div class="flex items-center justify-center md:px-40">
        <div class="mr-0 md:ml-6 mt-4 md:mt-10 text-right">
            <h2 class="text-xl md:text-3xl font-bold mb-2">Informasi Publik - PPID</h2>
            <p class="text-gray-700 text-sm md:text-lg mb-4 md:mb-10">
                Layanan dalam mendukung keterbukaan informasi publik oleh Pejabat Pengelola Informasi dan Dokumentasi
                (PPID).
            </p>
            <button class="btn bg-purple-500 text-white">Selengkapnya</button>
        </div>
        <img src="{{ asset('images/illustrasi-ibiza.png') }}" alt="Pelatihan" class="w-32 md:w-72 mb-4 md:mb-0">
    </div>
    <hr>

    <div class="mb-10">
        <h2 class="mt-16 mb-5 ml-5 px-10 text-4xl font-bold">Berita & Blog</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            @foreach ($featuredPosts as $post)
                <div class="col-span-1">
                    <x-posts.post-card :post="$post" />
                </div>
            @endforeach
        </div>
    </div>

    <div class="card lg:card-side md:w-full h-auto my-10 p-5 bg-base-100 shadow-xl">
        <figure>
            <img src="https://bdiyogyakarta.kemenperin.go.id/assets/images/joko-widodo.jpg" alt="Joko Widodo"
                class="w-32 md:w-60 h-36">
        </figure>
        <div class="card-body">
            <p class="text-lg md:text-2xl font-semibold">
                "Dalam periode kedua ini saya ingin fokus pada pembangunan SDM,
                <em>vocational training</em>, <em>up-skilling</em>, kemudian reformasi di
                bidang pendidikan. Sehingga muncul sebuah SDM yang memiliki kualifikasi dan
                kualitas yang baik."
            </p>
            <p class="text-sm md-text-base">
                Joko Widodo
                <span class="text-red-600">
                    - Presiden Indonesia
                </span>
            </p>
        </div>
    </div>
</x-app-layout>
