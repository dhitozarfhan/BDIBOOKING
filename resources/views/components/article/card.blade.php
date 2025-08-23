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
                {{ Carbon\Carbon::parse($article->published_at)->translatedFormat('d M Y') }}
            </span>
            <span class="bg-secondary text-base-300 font-bold px-2 py-1 rounded-lg"><i class="bi bi-folder"></i> {{ $article->category->name }}</span>
        </div>

        <h2 class="card-title mt-1">
            <a wire:navigate href="{{ route('articles.show', ['slug' => Str::kebab($article->title).'-'.$article->id, 'article_type' => $article->articleType->slug]) }}" class="link link-hover">
                {{ $article->title }}
            </a>
        </h2>

        <p class="text-base-content/80">
            {{ Str::limit(strip_tags($article->summary ?? $article->content), 160) }}
        </p>

        <div class="card-actions justify-end">
            <a wire:navigate href="{{ route('articles.show', ['slug' => Str::kebab($article->title).'-'.$article->id, 'article_type' => $article->articleType->slug]) }}" class="link link-primary text-3xl">
                <i class="bi bi-arrow-right-square-fill"></i>
            </a>
        </div>
    </div>
</article>