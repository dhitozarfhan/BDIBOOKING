@props(['article'])
<article class="py-4">
    <div class="flex gap-3">
        <div class="w-40 h-30 rounded overflow-hidden bg-base-200 shrink-0">
            <img src="{{ $article->image }}" alt="{{ $article->title }}" class="w-full h-full object-cover" loading="lazy">
        </div>
        <div class="flex-1">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2">
                <div>
                    <h3 class="text-lg font-semibold leading-snug">
                        <a wire:navigate href="{{ route('articles.show', ['slug' => Str::kebab($article->title).'-'.$article->id, 'article_type' => $article->articleType->slug]) }}" class="link link-hover">
                            {{ $article->title }}
                        </a>
                    </h3>
                    <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-base-content/60">
                        <span class="px-2 py-1 border rounded-lg">{{ Carbon\Carbon::parse($article->published_at)->translatedFormat('d M Y') }}</span>
                        <span class="bg-secondary text-base-300 font-bold px-2 py-1 rounded-lg">{{ $article->category->name }}</span>
                    </div>
                </div>
                <div class="mt-1 text-xs text-base-content/60">Dibaca: {{ number_format($article->hit) }}</div>
            </div>
            <p class="mt-2 text-base-content/80 text-sm">{{ Str::limit(strip_tags($article->summary ?? $article->content), 360) }}</p>
        </div>
    </div>
</article>