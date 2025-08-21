<section class="bg-gray-100">
    <div class="container mx-auto">
        <h2 class="my-5 ml-40 text-5xl font-bold">{{ __('News') }} & <span class="text-red-600">{{ __('Blog') }}</span></h2>
        <div class="columns-1 sm:columns-2 md:columns-4 space-y-3 mx-40">
            @foreach ($posts as $item)
            
            @endforeach
        </div>
    </div>
</section>