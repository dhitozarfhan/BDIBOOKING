<x-app-layout>
    <section class="bg-gray-100 py-10">
        <div class="container mx-auto md:px-20">
            <h2 class="mb-12 text-5xl font-bold">Archive {{ $currentDate->translatedFormat('F Y') }}</h2>
            <div class="w-full grid grid-cols-4 gap-10">
                <div class="md:col-span-3 col-span-4">
                    <div class="mb-6 flex flex-row gap-2">
                        @if (isset($prevDate))
                            <a href="{{ url('archive/posted/' . $prevDate->year . '/' . $prevDate->month) }}" class="block bg-white border p-3 rounded-lg shadow hover:border-blue-500">
                                <div class="flex items-center justify-between">
                                    <h6 class="text-gray-500">&laquo; {{ $prevDate->translatedFormat('F Y') }}</h6>
                                </div>
                            </a>
                        @endif
                        @if (isset($nextDate) && !$nextDisabled)
                            <a href="{{ url('archive/posted/' . $nextDate->year . '/' . $nextDate->month) }}" class="block bg-white border p-3 rounded-lg shadow hover:border-blue-500">
                                <div class="flex items-center justify-between">
                                    <h6 class="text-gray-500">{{ $nextDate->translatedFormat('F Y') }} &raquo;</h6>
                                </div>
                            </a>
                        @endif
                    </div>
                    @foreach ($posts as $item)
                        <x-cards-item
                            :post="$item"
                            :title="$item->id_title"
                            :summary="$item->id_summary"
                            :category="$item->category->id_name"
                            :date="$item->formatted_date"
                            :type="$item->type"/>
                    @endforeach
                    <div class="mt-6 flex flex-row gap-2">
                        @if (isset($prevDate))
                            <a href="{{ url('archive/posted/' . $prevDate->year . '/' . $prevDate->month) }}" class="block bg-white border p-3 rounded-lg shadow hover:border-blue-500">
                                <div class="flex items-center justify-between">
                                    <h6 class="text-gray-500">&laquo; {{ $prevDate->translatedFormat('F Y') }}</h6>
                                </div>
                            </a>
                        @endif
                        @if (isset($nextDate) && !$nextDisabled)
                            <a href="{{ url('archive/posted/' . $nextDate->year . '/' . $nextDate->month) }}" class="block bg-white border p-3 rounded-lg shadow hover:border-blue-500">
                                <div class="flex items-center justify-between">
                                    <h6 class="text-gray-500">{{ $nextDate->translatedFormat('F Y') }} &raquo;</h6>
                                </div>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="col-lg-3">
                    <x-archive-widget :archive="$archive"/>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
