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
                    {{ __('home.competency_based_industrial_training_desc')}}
                </p>
                <button class="btn bg-blue-500 text-white">{{__('home.more')}}</button>
            </div>
        </div>

        <div class="flex items-center justify-center md:px-40">
            <div class="mr-0 md:ml-6 mt-4 md:mt-10 text-right">
                <h2 class="text-xl md:text-3xl font-bold mb-2">{{ __('home.business_incubator') }}</h2>
                <p class="text-gray-700 text-sm md:text-lg mb-4 md:mb-10">
                    {{__('home.business_incubator_desc')}}
                </p>
                <button class="btn bg-green-500 text-white">{{__('home.more')}}</button>
            </div>
            <img src="{{ asset('images/illustrasi-ibiza.png') }}" alt="Pelatihan" class="w-32 md:w-72 mb-4 md:mb-0">
        </div>

        <div class="flex items-center justify-center md:px-40">
            <img src="{{ asset('images/illustrasi-pelatihan.png') }}" alt="Pelatihan" class="w-32 md:w-72 mb-4 md:mb-0">
            <div class="ml-0 md:ml-6 mt-4 md:mt-10">
                <h2 class="text-xl md:text-3xl font-bold mb-2">{{__('home.competency_infrastructure')}}</h2>
                <p class="text-gray-700 text-sm md:text-lg mb-4 md:mb-10">
                    {{__('home.competency_infrastructure_desc')}}
                </p>
                <button class="btn bg-red-500 text-white">{{__('home.more')}}</button>
            </div>
        </div>

        <div class="flex items-center justify-center md:px-40">
            <div class="mr-0 md:ml-6 mt-4 md:mt-10 text-right">
                <h2 class="text-xl md:text-3xl font-bold mb-2">{{__('home.ppid_public_information')}}</h2>
                <p class="text-gray-700 text-sm md:text-lg mb-4 md:mb-10">
                    {{__('home.ppid_public_information_desc')}}
                </p>
                <button class="btn bg-purple-500 text-white">{{__('home.more')}}</button>
            </div>
            <img src="{{ asset('images/illustrasi-ibiza.png') }}" alt="Pelatihan" class="w-32 md:w-72 mb-4 md:mb-0">
        </div>
    </div>
    <hr class="mb-12">

    <div class="mb-10">
        <h2 class="mb-5 px-10 text-4xl font-bold">{{__('home.news')}} & <span class="text-red-600">{{__('home.blog')}}</span></h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-10 px-10">
            @foreach ($featuredPosts as $post)
                <div class="col-span-1">
                    <x-posts.post-card :post="$post" />
                </div>
            @endforeach
        </div>
    </div>

    @include('components.testimonial')
</x-app-layout>
