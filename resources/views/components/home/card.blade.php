@props(['article'])

<article class="card bg-base-200 shadow-sm hover:shadow-md transition">
    <figure>
        @if(Storage::exists($article->image))
            <img src="{{ Storage::url($article->image) }}" alt="{{ $article->title }}" />
        @else
            <img class="p-4" src="{{ asset('images/bdi-yogyakarta.svg') }}" alt="{{ $article->title }}" />
        @endif
    </figure>
    <div class="card-body">
        <div class="flex items-center gap-2 text-base-content/60">
            <span class="px-2 py-1 border rounded-lg">
                <a wire:navigate href="{{ url('/'.$article->articleType->slug) . '?' . \Illuminate\Support\Arr::query(['year' => substr($article->published_at, 0, 4), 'month' => substr($article->published_at, 5, 2)]) }}">
                    {{ Carbon\Carbon::parse($article->published_at)->translatedFormat('d M Y') }}
                </a>
            </span>
            <span class="bg-secondary text-base-300 font-bold px-2 py-1 rounded-lg">
                <a wire:navigate href="{{ url('/'.$article->articleType->slug . '?category='.urlencode($article->category->slug)) }}">
                    <i class="bi bi-folder"></i> {{ $article->category->name }}</a>
            </span>
        </div>

        <h2 class="card-title mt-1 line-clamp-2">
            <a wire:navigate href="{{ route('articles.show', ['slug' => $article->slug, 'article_type' => $article->articleType->slug]) }}" class="link link-hover">
                {{ $article->title }}
            </a>
        </h2>

        <p class="text-base-content/80 line-clamp-4">
            {{ Str::limit(strip_tags($article->summary ?? $article->content), 160) }}
        </p>

        <div class="card-actions justify-end">
            <a wire:navigate href="{{ route('articles.show', ['slug' => $article->slug, 'article_type' => $article->articleType->slug]) }}" class="link link-primary text-3xl">
                <i class="bi bi-arrow-right-square-fill"></i>
            </a>
        </div>
    </div>
</article>