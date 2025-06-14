<x-app-layout>
    <section class="bg-gray-100">
        <div class="container mx-auto md:px-20 py-8">
            <h2 class="mb-12 text-5xl text-gray-800 font-bold">Seminar</h2>
            <div class="max-w-6xl w-full grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach ($seminars as $seminar)
                    <a wire:navigate href="{{ route('booking.post', ['id' => $seminar->seminar_id, 'title' => Str::slug($seminar->title)]) }}">
                        <div class="relative max-w-sm w-full bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer transition-shadow duration-300 hover:shadow-2xl group">
                            <img
                                src="{{ $seminar->getThumbnailImage() }}"
                                class="w-full h-auto object-cover"
                            />
                            <div class="p-6">
                                <h2 class="text-2xl font-semibold text-gray-900 leading-tight mb-2">
                                    {{ $seminar->title }}
                                </h2>
                            </div>
                            <div class="pointer-events-none absolute inset-0 bg-black opacity-0 rounded-xl transition-opacity duration-300 group-hover:opacity-30"></div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
</x-app-layout>
