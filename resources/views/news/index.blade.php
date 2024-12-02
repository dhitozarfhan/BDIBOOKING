<x-app-layout>
    <section class="bg-gray-100 py-10">
        <div class="container mx-auto md:px-20">
            <h2 class="mb-12 text-5xl font-bold">{{ __('home.news') }} @isset($category_name) : {{ $category_name }} @endisset</h2>
            <div class="w-full grid grid-cols-4 gap-10">
                <div class="md:col-span-3 col-span-4">
                    @foreach ($news as $item)
                    <x-news-item
                        :post="$item"
                        :title="$item->id_title"
                        :summary="$item->id_summary"
                        :category="$item->category->id_name"
                        :date="$item->formatted_date"
                        :type="'news'"
                        :image="$item->image" />
                    @endforeach
                </div>
                <div class="md:col-span-1 col-span-4">
                    <x-archive-widget :archive="$archive"/>
                    <x-news-widget :category="$category"/>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
