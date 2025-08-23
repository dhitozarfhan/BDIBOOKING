{{-- Livewire v3 --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Breadcrumbs sederhana --}}
    <nav class="text-sm breadcrumbs mb-4">
        <ul>
            <li><a wire:navigate href="{{ url('/') }}"><i class="bi bi-house-fill"></i></a></li>
            <li>
                <a wire:navigate href="{{ url('/'.$articleType) }}">
                    {{ __($article->articleType->nameTranslation) }}
                </a>
            </li>
            @if($article?->category)
                <li>
                    <a wire:navigate
                       href="{{ url('/'.$articleType) . '?category='.$article->category->slug }}">
                        {{ $article->category->name }}
                    </a>
                </li>
            @endif
            <li class="flex-1 min-w-0 max-w-[60vw]">
                <span class="block truncate">
                    {{ $article->title }}
                </span>
            </li>
        </ul>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        {{-- MAIN --}}
        <article class="lg:col-span-8">
            {{-- Judul --}}
            <header>
                <h1 class="text-2xl md:text-3xl font-bold leading-tight">{{ $article->title }}</h1>

                <div class="mt-2 text-sm text-base-content/60 flex flex-wrap items-center gap-x-3 gap-y-1">
                    <time datetime="{{ optional($article->published_at)->toDateString() }}">
                        {{ optional($article->published_at)->translatedFormat('d M Y') }}
                    </time>
                    @if($article->category)
                        <a wire:navigate
                           class="hover:link"
                           href="{{ url('/'.$articleType) . '?category='.$article->category->slug }}">
                            #{{ $article->category->name }}
                        </a>
                    @endif
                    <span>👁️ {{ number_format($article->hit) }}</span>
                </div>
            </header>

            {{-- Hero image --}}
            @if($article->image)
                <figure class="mt-5 rounded-2xl overflow-hidden bg-base-200">
                    <img src="{{ Storage::url($article->image) }}"
                         alt="{{ $article->title }}"
                         class="w-full h-auto object-cover">
                </figure>
            @endif

            {{-- Konten --}}
            <div class="prose max-w-none mt-6">
                {!! $article->content !!}
            </div>

            {{-- Related --}}
            @if(($related ?? collect())->isNotEmpty())
                <section class="mt-10">
                    <h2 class="text-lg font-semibold mb-4">{{ __('Related') }}</h2>
                    <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($related as $a)
                            <article class="card bg-base-100 shadow-sm hover:shadow-md transition-shadow">
                                <figure class="aspect-video overflow-hidden">
                                    <a wire:navigate href="{{ url('/'.$articleType.'/'.$a->slug) }}" class="w-full h-full block">
                                        @if($a->image)
                                            <img src="{{ Storage::url($a->image) }}" alt="{{ $a->title }}"
                                                 class="w-full h-full object-cover" loading="lazy">
                                        @else
                                            <div class="w-full h-full grid place-items-center bg-neutral-content">{{ __('No Image') }}</div>
                                        @endif
                                    </a>
                                </figure>
                                <div class="card-body p-4">
                                    <a wire:navigate href="{{ url('/'.$articleType.'/'.$a->slug) }}" class="hover:link">
                                        <h3 class="card-title text-base line-clamp-2">{{ $a->title }}</h3>
                                    </a>
                                    <div class="text-xs text-base-content/60">
                                        {{ optional($a->published_at)->translatedFormat('d M Y') }}
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif
        </article>

        {{-- SIDEBAR --}}
        <aside class="lg:col-span-4">
            <x-article.sidebar
                :latest="$latest"
                :popular="$popular"
                :categories="$categories"
                :archives="$archives"
                :articleType="$articleType"
            />
        </aside>
    </div>
</div>