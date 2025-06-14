<x-app-layout>
    <section class="bg-gray-100 py-10">
        <div class="container mx-auto md:px-20">
            <div class="w-full grid grid-cols-2 gap-10">
                <div class="col-span-2 md:col-span-1">
                    <img
                        src="{{ $seminar->getThumbnailImage() }}"
                        class="w-full h-auto"
                    >
                </div>
                <div class="col-span-2 md:col-span-1">
                    <h2 class="text-3xl font-bold mb-2 text-gray-800">{{ $seminar->title }}</h2>
                    <p class="text-2xl font-semibold mb-8 text-gray-700">Rp {{ number_format($seminar->price, 0, ',', '.') }}</p>
                    <p class="text-lg font-medium text-black">{!! $seminar->description !!}</p>
                    <a wire:navigate href="{{ route('booking.detail', ['id' => $seminar->seminar_id, 'title' => Str::slug($seminar->title)]) }}"
                        class="btn w-full items-center justify-center mt-10 bg-green-700 text-white hover:bg-green-800">
                        <i class="fas fa-shopping-cart"></i><p class="text-xl">Beli</p>
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
