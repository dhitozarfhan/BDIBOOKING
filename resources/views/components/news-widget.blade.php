@props(['recent', 'category', 'popular'])
@if (!@empty($category))
    <div class="mb-8">
        <div class="mb-4">
            <h3 class="text-3xl font-bold">{{ __('home.categories') }}</h3>
        </div>
        <div class="space-y-2">
            @foreach ($category as $data)
                <a href="{{ route('news.index', ['categoryId' => $data->category_id, 'categorySlug' => Str::slug($data->id_name)]) }}"
                    class="block bg-white border border-gray-300 p-3 rounded-lg hover:border-blue-500">
                    <div class="flex items-center justify-between">
                        <h6 class="text-sm font-semibold">
                            <i class="far fa-folder-open"></i>
                            {{ $data->id_name }}
                            <small class="text-gray-500">({{ $data->news_count }})</small>
                        </h6>
                        <i class="fas fa-chevron-right text-blue-500"></i>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endif

<div x-data="{ activeTab: 'recent' }" class="space-y-4">
    <div class="flex justify-center py-2 space-x-2 bg-blue-100 rounded-lg">
        <button
            @click="activeTab = 'recent'"
            class="px-4 py-2 font-medium rounded-md"
            :class="activeTab === 'recent' ? 'text-white bg-blue-600' : 'text-blue-600'">
            Terkini
        </button>
        <button
            @click="activeTab = 'popular'"
            class="px-4 py-2 font-medium rounded-md"
            :class="activeTab === 'popular' ? 'text-white bg-blue-600' : 'text-blue-600'">
            Populer
        </button>
    </div>

    <div>
        <div x-show="activeTab === 'recent'" x-cloak>
            <h3 class="mb-4 text-xl font-bold">Berita Terkini</h3>
            @foreach ($recent as $item)
                <div class="flex flex-row mb-4 border-b border-gray-300 pb-4">
                    <div class="w-1/3 mr-2">
                        <img src="{{ $item->getThumbnailImage() }}" alt="Thumbnail" width="80"/>
                    </div>
                    <div class="w-2/3">
                        <a href="{{ route('news.post', [
                            'year' => date('Y', strtotime($item->time_stamp)),
                            'month' => date('m', strtotime($item->time_stamp)),
                            'category' => $item->category_id,
                            'news' => $item->news_id,
                            'title' => Str::slug($item->id_title)]) }}">
                            <h4 class="font-bold leading-tight hover:text-blue-600">{{ $item->id_title }}</h4>
                        </a>
                        <div class="text-gray-600 text-xs flex items-center">
                            <i class="far fa-calendar-alt mr-2"></i>
                            <span>{{ \Carbon\Carbon::parse($item->time_stamp)->translatedFormat('d F Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div x-show="activeTab === 'popular'" x-cloak>
            <h3 class="mb-4 text-xl font-bold">Berita Populer</h3>
            @foreach ($popular as $item)
                <div class="flex flex-row mb-4 border-b border-gray-300 pb-4">
                    <div class="w-1/3 mr-2">
                        <img src="{{ $item->getThumbnailImage() }}" alt="Thumbnail" width="80"/>
                    </div>
                    <div class="w-2/3">
                        <a href="{{ route('news.post', [
                            'year' => date('Y', strtotime($item->time_stamp)),
                            'month' => date('m', strtotime($item->time_stamp)),
                            'category' => $item->category_id,
                            'news' => $item->news_id,
                            'title' => Str::slug($item->id_title)]) }}">
                            <h4 class="font-bold leading-tight hover:text-blue-600">{{ $item->id_title }}</h2>
                        </a>
                        <div class="text-gray-600 text-xs flex items-center">
                            <i class="far fa-calendar-alt mr-2"></i>
                            <span>{{ \Carbon\Carbon::parse($item->time_stamp)->translatedFormat('d F Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
