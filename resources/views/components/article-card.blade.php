<div class="card bg-base-100 w-72 shadow-xl mx-auto mt-auto outline-1">
    <figure>
        <img src="{{ $post->getThumbnailImage() }}">
        <div class="absolute top-2 left-2 bg-blue-500 text-white px-2 py-1 rounded text-sm">
            {{ $category }}
        </div>
    </figure>
    <div class="card-body">
        @if ($type === 'news')
            <a href="{{ route('news.post', [
                    'year' => $post->time_stamp->format('Y'),
                    'month' => $post->time_stamp->format('m'),
                    'category' => $post->category->category_id,
                    'news' => $post->news_id,
                    'title' => Str::slug($post->id_title)]) }}">
                <h2 class="text-xl font-bold hover:text-blue-600 transition">{{ $title }}</h2>
            </a>
        @elseif ($type === 'blog')
            <a href="{{ route('blog.post', [
                    'year' => $post->time_stamp->format('Y'),
                    'month' => $post->time_stamp->format('m'),
                    'category' => $post->category->category_id,
                    'blog' => $post->blog_id,
                    'title' => Str::slug($post->id_title)]) }}">
                <h2 class="text-xl font-bold hover:text-blue-600 transition">{{ $title }}</h2>
            </a>
        @endif
        <p class="line-clamp-2">{{ strip_tags($summary) }}</p>
        <div class="flex justify-between">
            <a href="" class="font-bold hover:text-blue-600">Ade Aulia Ramadhan</a>
            <a href="">
                <p class="text-sm text-blue-600">{{ $date }}</p>
            </a>
        </div>
    </div>
</div>
