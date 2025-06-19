<x-app-layout>
    <section class="bg-gray-100 py-10">
        <div class="container mx-auto md:px-20">
            <div class="w-full grid grid-cols-4 gap-10">
                <div class="md:col-span-3 col-span-4">
                    <h1 class="text-4xl font-bold mb-10">{{ $page->id_title }}</h1>
                    <div class="border-t border-b py-6 mb-10">
                        <div class="flex flex-col md:flex-row md:items-center">
                            <div class="md:w-7/12 md:mb-0 mb-5 ">
                                <div class="flex items-center">
                                    <div class="mr-3 rounded-full overflow-hidden w-12 h-12">
                                        <img src="{{ asset('storage/profile-photos/user_man.jpg') }}" alt="Default Avatar" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <span class="text-lg font-semibold">
                                            <a href="">Administrator</a>
                                        </span>
                                        <span class="block text-blue-600">
                                            <a href="">{{ \Carbon\Carbon::parse($page->time_stamp)->translatedFormat('d F Y') }}</a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="md:w-5/12">
                                <span class="block text-blue-600">
                                    <a href="">
                                        <i class="far fa-comments"></i> 0 Komentar
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <p>{!! $page->id_content !!}</p>
                </div>
                <div class="md:col-span-1 col-span-4">
                    <x-archive-widget :archive="$archive"/>
                    {{-- <x-news-widget :recent="$recent" :category="$category" :popular="$popular"/> --}}
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
