@props(['post'])
<div class="card bg-base-100 w-72 shadow-xl mx-auto mt-auto">
    <figure>
        <img src="{{ $post->getThumbnailImage() }}">
        <div class="absolute top-2 left-2 bg-blue-500 text-white px-2 py-1 rounded text-sm">
            @foreach ($post->categories as $category)
                {{ $category->title }}
            @endforeach
        </div>
    </figure>
    <div class="card-body">
        <a href="">
            <h2 class="card-title hover:text-blue-500">{{ $post->title }}</h2>
        </a>
        <p class="line-clamp-2">{{ strip_tags($post->body) }}</p>
        <div class="flex justify-between">
            <p class="text-sm">{{ $post->author->name }}</p>
            <p class="text-sm text-gray-500">{{ $post->published_at->translatedFormat('d F Y') }}</p>
        </div>
    </div>
</div>
