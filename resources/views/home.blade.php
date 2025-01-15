<x-app-layout>
    @section('carousel')
        <div class="w-3/4 text-center mx-auto py-5">
            <x-carousel :slideshows="$slideshows" />
        </div>
    @endsection
    @section('hero')
        <div class="py-8">
            <div class="flex items-center justify-center md:px-40">
                <img src="{{ asset('images/illustrasi-pelatihan.png') }}" alt="Pelatihan" class="w-32 md:w-72 mb-4 md:mb-0">
                <div class="ml-0 md:ml-6 mt-4 md:mt-10">
                    <h2 class="text-xl md:text-3xl font-bold mb-2">{{ __('home.competency_based_industrial_training') }}</h2>
                    <p class="text-gray-700 text-sm md:text-lg mb-4 md:mb-10">
                        {{ __('home.competency_based_industrial_training_desc') }}
                    </p>
                    <a wire:navigate href="/training">
                        <button class="btn bg-blue-500 text-white hover:bg-blue-700">{{ __('home.more') }}</button>
                    </a>
                </div>
            </div>
            <div class="flex items-center justify-center md:px-40">
                <div class="mr-0 md:ml-6 mt-4 md:mt-10 text-right">
                    <h2 class="text-xl md:text-3xl font-bold mb-2">{{ __('home.business_incubator') }}</h2>
                    <p class="text-gray-700 text-sm md:text-lg mb-4 md:mb-10">
                        {{ __('home.business_incubator_desc') }}
                    </p>
                    <a wire:navigate href="/ibiza">
                        <button class="btn bg-green-500 text-white hover:bg-green-700">{{ __('home.more') }}</button>
                    </a>
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
                    <a wire:navigate href="/competency">
                        <button class="btn bg-red-500 text-white hover:bg-red-700">{{ __('home.more') }}</button>
                    </a>
                </div>
            </div>
            <div class="flex items-center justify-center md:px-40">
                <div class="mr-0 md:ml-6 mt-4 md:mt-10 text-right">
                    <h2 class="text-xl md:text-3xl font-bold mb-2">{{ __('home.ppid_public_information') }}</h2>
                    <p class="text-gray-700 text-sm md:text-lg mb-4 md:mb-10">
                        {{ __('home.ppid_public_information_desc') }}
                    </p>
                    <a wire:navigate href="/information">
                        <button class="btn bg-purple-500 text-white hover:bg-purple-700">{{ __('home.more') }}</button>
                    </a>
                </div>
                <img src="{{ asset('images/illustrasi-ibiza.png') }}" alt="Pelatihan" class="w-32 md:w-72 mb-4 md:mb-0">
            </div>
        </div>
    @endsection

    <section class="bg-gray-100">
        <div class="container mx-auto">
            <h2 class="my-5 ml-40 text-5xl font-bold">{{ __('home.news') }} & <span class="text-red-600">{{ __('home.blog') }}</span></h2>
            <div class="columns-1 sm:columns-2 md:columns-4 space-y-3 mx-40">
                @foreach ($posts as $item)
                <div class="break-inside-avoid">
                    <x-article-card
                        :post="$item"
                        :title="$item->id_title"
                        :summary="$item->id_summary"
                        :category="$item->category->id_name"
                        :date="$item->formatted_date"
                        :type="$item->type"/>
                </div>
                @endforeach
            </div>
        </div>
    </section>
</x-app-layout>
