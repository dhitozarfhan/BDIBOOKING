@props([
    'article',
    'articleType'
])
<article class="card bg-base-100 shadow-sm hover:shadow-md transition-shadow">
    <figure class="aspect-video overflow-hidden">
        <a wire:navigate href="{{ url('/'.$articleType.'/'.$article->slug) }}" class="w-full h-full block">
            @if($article->image)
            <img src="{{ Storage::url($article->image) }}" alt="{{ $article->title }}" class="w-full h-full object-cover" loading="lazy">
            @else
            <div class="w-full h-full grid place-items-center bg-neutral-content">{{ __('No Image') }}</div>
            @endif
        </a>
    </figure>
    <div class="card-body p-4">
        <a wire:navigate href="{{ url('/'.$articleType.'/'.$article->slug) }}" class="hover:link">
            <h2 class="card-title text-base line-clamp-2">{{ $article->title }}</h2>
        </a>
        <p class="text-base-content/80 line-clamp-6">
            {{ Str::limit(strip_tags($article->summary ?? $article->content), 400) }}
        </p>
        <div class="text-xs text-base-content/60 flex flex-wrap items-center gap-2">
            <span>{{ optional($article->published_at)->translatedFormat('d M Y') }}</span>
            @if($article->category)
            <a wire:navigate class="hover:link"
                href="{{ url('/'.$articleType . '?category='.urlencode($article->category->slug)) }}">
                <i class="bi bi-folder2-open"></i> {{ $article->category->name }}
            </a>
            @endif
            <span>👁️ {{ number_format($article->hit) }}</span>
        </div>
    </div>
</article>