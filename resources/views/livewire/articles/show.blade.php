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
                       href="{{ url('/'.$articleType . '?category='.urlencode($article->category->slug)) }}">
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
                @if($article->author)
                <div class="mt-2 text-sm text-base-content/70">
                    <span class="font-medium">
                        <a wire:navigate class="hover:link" href="{{ url('/'.$articleType) . '?author='.urlencode($article->author?->slug) }}"><i class="bi bi-person-fill"></i> {{ $article->author?->name }}</a>
                    </span>
                </div>
                @endif
                <div class="mt-2 text-sm text-base-content/60 flex flex-wrap items-center gap-x-3 gap-y-1">
                    <time datetime="{{ Carbon\Carbon::parse($article->published_at)->toDateString() }}">
                        <a wire:navigate class="hover:link" href="{{ url('/'.$articleType) . '?' . \Illuminate\Support\Arr::query(['year' => substr($article->published_at, 0, 4), 'month' => substr($article->published_at, 5, 2)]) }}">
                            <i class="bi bi-calendar"></i> {{ Carbon\Carbon::parse($article->published_at)->translatedFormat('d F Y') }}
                        </a>
                    </time>
                    @if($article->category)
                        <a wire:navigate class="hover:link" href="{{ url('/'.$articleType . '?category='.urlencode($article->category->slug)) }}">
                            <i class="bi bi-folder2-open"></i> {{ $article->category->name }}
                        </a>
                    @endif
                    <span>👁️ {{ number_format($article->hit) }}</span>
                </div>
            </header>

            {{-- Hero image --}}
            @if($article->image && $article->article_type_id != \App\Enums\ArticleType::Gallery->value)
                <figure class="mt-5 rounded-2xl overflow-hidden bg-base-200">
                    <img src="{{ Storage::url($article->image) }}"
                         alt="{{ $article->title }}"
                         class="w-full h-auto object-cover">
                </figure>
            @endif

            {{-- CAROUSEL gambar tambahan (images hasMany) --}}
            @if(($article->images ?? collect())->isNotEmpty())
                <section class="mt-6" x-data>
                    {{-- DaisyUI carousel --}}
                    <div class="carousel w-full rounded-2xl bg-base-200 overflow-hidden">
        @foreach($article->images as $idx => $img)
            @php
                $src  = $img->image ?? $img->path ?? null;
                $desc = $img->description ?? $img->caption ?? '';
                $anchor = 'slide-'.($idx+1);
            @endphp
            @if($src)
                <div id="{{ $anchor }}" class="carousel-item w-full relative">
                    <button
                        type="button"
                        class="w-full"
                        data-lightbox-idx="{{ $idx }}"
                        data-src="{{ asset('storage/'.$src) }}"
                        data-alt="{{ $article->title }} - {{ $idx+1 }}"
                        data-caption="{{ e($desc) }}"
                        onclick="window.lightboxOpen({{ $idx }})"
                        title="Klik untuk perbesar"
                    >
                        <img
                            src="{{ asset('storage/'.$src) }}"
                            alt="{{ $article->title }} - {{ $idx+1 }}"
                            class="w-full h-auto object-cover hover:opacity-95 transition"
                        >
                    </button>

                    {{-- Optional overlay caption kecil di slide --}}
                    @if($desc)
                        <div class="absolute bottom-0 left-0 right-0 bg-base-100/80 backdrop-blur p-3 text-sm">
                            {{ $desc }}
                        </div>
                    @endif
                </div>
            @endif
        @endforeach
    </div>

    {{-- Nav dots --}}
    <div class="flex items-center justify-center gap-2 mt-3">
        @foreach($article->images as $idx => $img)
            @php $anchor = 'slide-'.($idx+1); @endphp
            <a href="#{{ $anchor }}" class="btn btn-xs">{{ $idx+1 }}</a>
        @endforeach
    </div>

    {{-- ===== Lightbox Modal (DaisyUI) ===== --}}
    <input type="checkbox" id="lightbox-modal" class="modal-toggle" />
    <div class="modal" role="dialog" aria-modal="true">
        <div class="modal-box max-w-5xl p-2 sm:p-3 md:p-4">
            <div class="flex items-center justify-between px-2">
                <div id="lb-counter" class="text-xs opacity-70"></div>
                <label for="lightbox-modal" class="btn btn-sm btn-ghost">✕</label>
            </div>

            <figure class="mt-1">
                <img id="lb-img" src="" alt="" class="w-full h-auto object-contain max-h-[70vh] rounded-xl" />
                <figcaption id="lb-caption" class="mt-2 text-sm opacity-80"></figcaption>
            </figure>

            <div class="mt-3 flex items-center justify-between">
                <button type="button" class="btn" onclick="window.lightboxPrev()">❮ {{ __('Previous') }}</button>
                <button type="button" class="btn" onclick="window.lightboxNext()">{{ __('Next') }} ❯</button>
            </div>
        </div>
        <label class="modal-backdrop" for="lightbox-modal">Close</label>
    </div>

    {{-- ===== Lightbox Script ===== --}}
    <script>
    (function(){
      // Kumpulan data gambar di galeri
      function collectItems() {
        const btns = document.querySelectorAll('[data-lightbox-idx]');
        const items = [];
        btns.forEach((b) => {
          items.push({
            src: b.getAttribute('data-src'),
            alt: b.getAttribute('data-alt') || '',
            caption: b.getAttribute('data-caption') || ''
          });
        });
        return items;
      }

      let items = collectItems();
      let current = 0;

      function render(idx){
        if (!items.length) return;
        idx = ((idx % items.length) + items.length) % items.length;
        current = idx;

        const modalToggle = document.getElementById('lightbox-modal');
        const img = document.getElementById('lb-img');
        const cap = document.getElementById('lb-caption');
        const ctr = document.getElementById('lb-counter');

        const it = items[current];
        img.src = it.src;
        img.alt = it.alt;
        cap.textContent = it.caption || '';
        ctr.textContent = (current+1) + ' / ' + items.length;

        // buka modal jika belum terbuka
        if (modalToggle && !modalToggle.checked) modalToggle.checked = true;

        // fokuskan tombol close agar aksesibel
        setTimeout(() => {
          const closeBtn = document.querySelector('label[for="lightbox-modal"].btn');
          if (closeBtn) closeBtn.focus({preventScroll:true});
        }, 0);
      }

      function openAt(i){ render(i); }
      function prev(){ render(current - 1); }
      function next(){ render(current + 1); }

      // Ekspor ke window untuk dipanggil onclick
      window.lightboxOpen = openAt;
      window.lightboxPrev = prev;
      window.lightboxNext = next;

      // Keyboard nav: ← / → / Esc
      function onKey(e){
        const modalToggle = document.getElementById('lightbox-modal');
        if (!modalToggle || !modalToggle.checked) return;
        if (e.key === 'ArrowLeft') { e.preventDefault(); prev(); }
        else if (e.key === 'ArrowRight') { e.preventDefault(); next(); }
        else if (e.key === 'Escape') { modalToggle.checked = false; }
      }
      document.addEventListener('keydown', onKey);

      // Re-koleksi setelah navigasi SPA / re-render
      document.addEventListener('livewire:navigated', () => { items = collectItems(); });
      document.addEventListener('livewire:update', () => { items = collectItems(); });
    })();
    </script>
                </section>
            @endif

            {{-- Konten --}}
            <div class="prose max-w-none mt-6 wrap-break-word">
                {!! $article->content !!}
            </div>

            {{-- TAGS --}}
            @if(($article->tags ?? collect())->isNotEmpty())
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach($article->tags as $tag)
                        @php
                            // Prefer slug kalau tersedia, fallback ke id
                            $tagParam = $tag->slug;
                        @endphp
                        <a wire:navigate
                        href="{{ url('/'.$articleType.'?tag='.urlencode($tagParam)) }}"
                        class="badge badge-outline border-current {{ $this->tagColor($tag->id) }}">
                            <i class="bi bi-tag"></i> {{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            @endif

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