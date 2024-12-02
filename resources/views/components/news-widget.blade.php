@if (!@empty($category))
    <div class="mb-8">
        <div class="mb-4">
            <h3 class="text-3xl font-bold">{{ __('home.categories') }}</h3>
        </div>
        <div class="space-y-2">
            @foreach ($category as $data)
                <a href="{{  route('news.index', ['categoryId' => $data->category_id, 'categorySlug' => Str::slug($data->id_name)]) }}" class="block bg-white border border-gray-300 p-3 rounded-lg hover:border-blue-500">
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
