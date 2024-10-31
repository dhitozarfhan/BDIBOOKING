<x-app-layout>
    @section('hero')
        <div class="w-3/4 text-center mx-auto py-5">
            <x-carousel :slideshows="$slideshows" />
        </div>
    @endsection
    <hr>

    <div class="bg-white">
        <div class="flex items-center justify-center md:px-40">
            <img src="{{ asset('images/illustrasi-pelatihan.png') }}" alt="Pelatihan" class="w-32 md:w-72 mb-4 md:mb-0">
            <div class="ml-0 md:ml-6 mt-4 md:mt-10">
                <h2 class="text-xl md:text-3xl font-bold mb-2">{{ __('home.competency_based_industrial_training') }}</h2>
                <p class="text-gray-700 text-sm md:text-lg mb-4 md:mb-10">
                    {{ __('home.competency_based_industrial_training_desc') }}
                </p>
                <button class="btn bg-blue-500 text-white">{{ __('home.more') }}</button>
            </div>
        </div>

        <div class="flex items-center justify-center md:px-40">
            <div class="mr-0 md:ml-6 mt-4 md:mt-10 text-right">
                <h2 class="text-xl md:text-3xl font-bold mb-2">{{ __('home.business_incubator') }}</h2>
                <p class="text-gray-700 text-sm md:text-lg mb-4 md:mb-10">
                    {{ __('home.business_incubator_desc') }}
                </p>
                <button class="btn bg-green-500 text-white">{{ __('home.more') }}</button>
            </div>
            <img src="{{ asset('images/illustrasi-ibiza.png') }}" alt="Pelatihan" class="w-32 md:w-72 mb-4 md:mb-0">
        </div>

        <div class="flex items-center justify-center md:px-40">
            <img src="{{ asset('images/illustrasi-pelatihan.png') }}" alt="Pelatihan" class="w-32 md:w-72 mb-4 md:mb-0">
            <div class="ml-0 md:ml-6 mt-4 md:mt-10">
                <h2 class="text-xl md:text-3xl font-bold mb-2">{{ __('home.competency_infrastructure') }}</h2>
                <p class="text-gray-700 text-sm md:text-lg mb-4 md:mb-10">
                    {{ __('home.competency_infrastructure_desc') }}
                </p>
                <button class="btn bg-red-500 text-white">{{ __('home.more') }}</button>
            </div>
        </div>

        <div class="flex items-center justify-center md:px-40">
            <div class="mr-0 md:ml-6 mt-4 md:mt-10 text-right">
                <h2 class="text-xl md:text-3xl font-bold mb-2">{{ __('home.ppid_public_information') }}</h2>
                <p class="text-gray-700 text-sm md:text-lg mb-4 md:mb-10">
                    {{ __('home.ppid_public_information_desc') }}
                </p>
                <button class="btn bg-purple-500 text-white">{{ __('home.more') }}</button>
            </div>
            <img src="{{ asset('images/illustrasi-ibiza.png') }}" alt="Pelatihan" class="w-32 md:w-72 mb-4 md:mb-0">
        </div>
    </div>
    <hr class="mb-12">

    <section class="bg-gray-100 py-10">
        <div class="container mx-auto">
            <h2 class="mb-5 px-10 text-4xl font-bold">{{ __('home.news') }} & <span class="text-red-600">{{ __('home.blog') }}</span></h2>
            <div class="columns-1 sm:columns-2 md:columns-4 space-y-3 mx-10">
                @foreach ($news as $item)
                <div class="break-inside-avoid">
                    <x-article-card
                        :post="$item"
                        :title="$item->id_title"
                        :summary="$item->id_summary"
                        :category="$item->category->id_name"
                        :date="$item->formatted_date"
                        :type="'news'"
                        :image="$item->image" />
                </div>
                @endforeach
                @foreach ($blog as $item)
                <div class="break-inside-avoid">
                    <x-article-card
                        :post="$item"
                        :title="$item->id_title"
                        :summary="$item->id_summary"
                        :category="$item->category->id_name"
                        :date="$item->formatted_date"
                        :type="'blog'"
                        :image="$item->image" />
                </div>
                @endforeach
            </div>
        </div>
    </section>

    @include('components.testimonial')
</x-app-layout>
