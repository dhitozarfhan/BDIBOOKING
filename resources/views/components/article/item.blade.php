@props([
    'article',
    'articleType'
])
<article class="py-4 flex gap-4">
    @if($article->image)
    <a wire:navigate href="{{ url('/'.$articleType.'/'.$article->slug) }}" class="shrink-0 w-40 aspect-video overflow-hidden rounded-xl">
        <img src="{{ Storage::url($article->image) }}" alt="{{ $article->title }}" class="w-full h-full object-cover" loading="lazy">
    </a>
    @endif
    <div class="min-w-0">
        <a wire:navigate href="{{ url('/'.$articleType.'/'.$article->slug) }}" class="hover:link">
            <h2 class="text-lg font-semibold line-clamp-2">{{ $article->title }}</h2>
        </a>
        <div class="mt-1 text-xs text-base-content/60 flex flex-wrap items-center gap-x-3 gap-y-1">
            <span>{{ optional($article->published_at)->translatedFormat('d M Y') }}</span>
            @if($article->category)
            <a wire:navigate class="hover:link"
                href="{{ url('/'.$articleType . '?category='.urlencode($article->category->slug)) }}">
                <i class="bi bi-folder2-open"></i> {{ $article->category->name }}
            </a>
            @endif
            <span>👁️ {{ number_format($article->hit) }}</span>
        </div>
        @if($article->summary)
        <p class="mt-2 text-sm line-clamp-3">{{ $article->summary }}</p>
        @endif
    </div>
</article>