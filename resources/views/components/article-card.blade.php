<div class="card bg-base-100 w-72 shadow-xl mx-auto mt-auto">
    <figure>
        <img src="{{ $post->getThumbnailImage() }}">
        <div class="absolute top-2 left-2 bg-blue-500 text-white px-2 py-1 rounded text-sm">
            {{ $category }}
        </div>
    </figure>
    <div class="card-body">
        <a href="">
            <h2 class="card-title hover:text-blue-500">{{ $title }}</h2>
        </a>
        <p class="line-clamp-2">{{ strip_tags($summary) }}</p>
        <div class="flex justify-between">
            {{-- <p class="text-sm">{{ $post->author->name }}</p> --}}
            <p class="text-sm text-gray-500">{{ $date }}</p>
        </div>
    </div>
</div>
