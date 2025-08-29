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
            @if($article?->category && $article->article_type_id !== \App\Enums\ArticleType::Information->value)
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
                @if($article->article_type_id !== \App\Enums\ArticleType::Information->value)
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
                @else
                <div class="mt-2 text-sm text-base-content/70">
                    <span class="font-medium">
                        <a wire:navigate class="hover:link" href="{{ url('/'.$articleType) . '?parent='.$article->category->parent_id }}"><i class="bi bi-folder2-open"></i> {{ $article->category->name }}</a>
                    </span>
                </div>
                @endif
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

            {{-- Jika artikel information memiliki lampiran di detailnya, beri tombol juga --}}
            @php
                // Siapkan payload lampiran untuk detail (bukan list di sidebar)
                $filesArr = [];
                if (is_string($article->files) && trim($article->files) !== '') {
                    try { $filesArr = json_decode($article->files, true) ?? []; } catch (\Throwable $e) { $filesArr = []; }
                } elseif (is_array($article->files)) {
                    $filesArr = $article->files;
                }

                // Parse original_files (dukung object flat dan array of maps)
                $origMap = [];
                if (is_string($article->original_files) && trim($article->original_files) !== '') {
                    try {
                        $tmp = json_decode($article->original_files, true);
                        if (is_array($tmp)) {
                            $isAssoc = array_keys($tmp) !== range(0, count($tmp) - 1);
                            if ($isAssoc) {
                                $origMap = $tmp;
                            } else {
                                foreach ($tmp as $entry) if (is_array($entry)) {
                                    foreach ($entry as $k => $v) $origMap[(string)$k] = (string)$v;
                                }
                            }
                        }
                    } catch (\Throwable $e) { $origMap = []; }
                } elseif (is_array($article->original_files)) {
                    $isAssoc = array_keys($article->original_files) !== range(0, count($article->original_files) - 1);
                    if ($isAssoc) {
                        foreach ($article->original_files as $k => $v) $origMap[(string)$k] = (string)$v;
                    } else {
                        foreach ($article->original_files as $entry) if (is_array($entry)) {
                            foreach ($entry as $k => $v) $origMap[(string)$k] = (string)$v;
                        }
                    }
                }

                $detailPayload = [];
                foreach ($filesArr as $rel) {
                    $rel = (string) $rel;
                    $detailPayload[] = [
                        'path'    => $rel,
                        'display' => $origMap[$rel] ?? basename($rel),
                    ];
                }
            @endphp

            @if(!empty($detailPayload))
                <div class="mt-6">
                    <button
                        type="button"
                        class="btn btn-primary"
                        data-article-title="{{ $article->title }}"
                        data-files='@json($detailPayload)'
                        onclick="openAttachmentsModal(this)">
                        <i class="bi bi-paperclip"></i>  {{ __('Attachments') }} ({{ count($detailPayload) }})
                    </button>
                </div>
            @endif

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
                                            <div class="w-full h-full grid place-items-center bg-primary-content text-neutral">{{ __('No Image') }}</div>
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
        {{-- ============ SIDEBAR ============ --}}
        <aside class="lg:col-span-4">
            @if($article->article_type_id == \App\Enums\ArticleType::Information->value)
                {{-- ===== Sidebar Khusus: Informasi Publik ===== --}}
                <section class="card bg-base-300 shadow-sm mb-6">
                    <div class="card-body p-4">
                        <h3 class="font-semibold">{{ __('Public Information Type') }}</h3>
                        {{-- Parent picker --}}
                        @isset($parents, $parentCategory)
                            <div class="mt-3 flex flex-wrap gap-2">
                                @foreach($parents as $parent)
                                    <button
                                        class="btn btn-xs {{ (int)$parentCategory === (int)$parent->id ? 'btn-primary' : 'btn-outline' }}"
                                        wire:click="$set('parentCategory', {{ $parent->id }})">
                                        {{ $parent->name }}
                                    </button>
                                @endforeach
                            </div>
                        @endisset
                    </div>
                </section>

                {{-- Accordion listing per kategori anak --}}
                @isset($children, $groups)
                    @if($children->isEmpty())
                        <div class="alert alert-soft">{{ __('No :name available.', ['name' => strtolower(__('Public Information'))]) }}</div>
                    @else
                        <div class="flex flex-col gap-3">
                            @foreach($children as $child)
                                @php
                                    $list  = $groups->get($child->id) ?? collect();
                                    $count = $list->count();
                                @endphp
                                @if($count > 0)
                                    <div class="collapse collapse-plus bg-base-100 border border-base-300 rounded-box">
                                        <input type="radio" name="information{{ $child->parent_id }}" />
                                        <div class="collapse-title text-base font-medium">
                                            {{ $child->name }}
                                        </div>
                                        <div class="collapse-content">
                                            <ul class="menu">
                                                @foreach($list as $a)
                                                    <li class="py-1">
                                                        <a wire:navigate href="{{ url('/information/'.$a->slug).'?parent='.$child->parent_id }}"
                                                            title="{{ $a->title }}"
                                                            class="hover:link">
                                                            <span class="line-clamp-1">{{ $a->title }}</span>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                @endisset

            @else
            <x-article.sidebar
                :latest="$latest"
                :popular="$popular"
                :categories="$categories"
                :archives="$archives"
                :articleType="$articleType"
            />
            @endif
        </aside>
    </div>
     {{-- =================== MODAL LAMPIRAN (1x per halaman) =================== --}}
    <div id="attachments-modal-host">
        <input type="checkbox" id="attachments-modal" class="modal-toggle" />
        <div class="modal" role="dialog" aria-modal="true">
            <div class="modal-box max-w-5xl p-0 md:p-0">
                <div class="px-4 py-3 border-b border-base-200 flex items-center justify-between">
                    <h3 id="att-title" class="font-semibold text-lg">{{ __('Attachments') }}</h3>
                    <label for="attachments-modal" class="btn btn-sm btn-ghost">✕</label>
                </div>
                <div class="p-3 md:p-4">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 md:gap-4">
                        <div class="md:col-span-5">
                            <ul id="att-list" class="menu"></ul>
                        </div>
                        <div class="md:col-span-7">
                            <div id="att-preview-wrap" class="border border-base-200 rounded-xl overflow-hidden min-h-[240px] grid place-items-center">
                                <div id="att-preview-empty" class="text-sm opacity-70 p-6 text-center">
                                    {{ __('Select a file to preview.') }}<br>
                                    (PDF, MP4 & {{ __('Images') }} supported; Office docs via Office Online if public URL)
                                </div>
                                <div id="att-preview" class="w-full h-full hidden"></div>
                            </div>
                            <div id="att-alt" class="mt-3 flex flex-wrap items-center gap-2 hidden">
                                <a id="att-open" href="#" target="_blank" rel="noopener" class="btn btn-sm btn-primary">{{ __('Open in New Tab') }}</a>
                                <a id="att-office" href="#" target="_blank" rel="noopener" class="btn btn-sm btn-outline">Preview via Office Online (beta)</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 border-t border-base-200 flex justify-end">
                    <label for="attachments-modal" class="btn">{{ __('Close') }}</label>
                </div>
            </div>
            <label class="modal-backdrop" for="attachments-modal">{{ __('Close') }}</label>
        </div>
    </div>
</div>
@push('scripts')
<script>
(function(){
  const STORAGE_BASE = "{{ rtrim(asset('storage'), '/') }}/";

  function toUrl(path) {
    if (!path) return '';
    if (/^https?:\/\//i.test(path)) return path;
    return STORAGE_BASE + path.replace(/^\/+/, '');
  }

  function fileType(path) {
    const ext = (path.split('.').pop() || '').toLowerCase();
    if (['pdf'].includes(ext)) return 'pdf';
    if (['mp4','webm','mov','m4v','avi'].includes(ext)) return 'video';
    if (['jpg','jpeg','png','gif','webp','bmp','svg'].includes(ext)) return 'image';
    if (['doc','docx','odt','rtf'].includes(ext)) return 'word';
    if (['xls','xlsx','ods','csv'].includes(ext)) return 'sheet';
    if (['ppt','pptx','odp','key'].includes(ext)) return 'ppt';
    if (['zip','rar','7z','tar','gz'].includes(ext)) return 'zip';
    return 'file';
  }

  function fileBadge(ft) {
    switch(ft){
      case 'pdf':   return {label:'PDF', icon:'<i class="bi bi-file-pdf-fill"></i>'};
      case 'video': return {label:'VIDEO', icon:'<i class="bi bi-file-play-fill"></i>'};
      case 'image': return {label:'IMAGE', icon:'<i class="bi bi-file-image"></i>'};
      case 'word':  return {label:'WORD', icon:'<i class="bi bi-file-word-fill"></i>'};
      case 'sheet': return {label:'SHEET', icon:'<i class="bi bi-file-spreadsheet-fill"></i>'};
      case 'ppt':   return {label:'PPT', icon:'<i class="bi bi-file-easel-fill"></i>'};
      case 'zip':   return {label:'ZIP', icon:'<i class="bi bi-file-zip-fill"></i>'};
      default:      return {label:'FILE', icon:'<i class="bi bi-file"></i>'};
    }
  }

  function buildOfficeViewer(url){
    try { const u = new URL(url); if (u.protocol !== 'https:') return null; }
    catch(e) { return null; }
    return 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURIComponent(url);
  }

  function clearPreview(){
    const wrap = document.getElementById('att-preview');
    const empty= document.getElementById('att-preview-empty');
    const alt  = document.getElementById('att-alt');
    wrap.innerHTML = '';
    wrap.classList.add('hidden');
    empty.classList.remove('hidden');
    alt.classList.add('hidden');
    currentIdx = -1;
    currentFiles = [];
  }

  function renderIframe(url){
    const node = document.createElement('iframe');
    node.src = url;
    node.className = 'w-full h-[60vh]';
    node.loading = 'lazy';
    node.allow = 'fullscreen';
    return node;
  }

  let currentFiles = [];
  let currentIdx = -1;

  function showPreview(url, ft, idx){
    const wrap = document.getElementById('att-preview');
    const empty= document.getElementById('att-preview-empty');
    const alt  = document.getElementById('att-alt');
    const open = document.getElementById('att-open');
    const office = document.getElementById('att-office');

    wrap.innerHTML = '';
    wrap.classList.remove('hidden');
    empty.classList.add('hidden');
    office.classList.add('hidden');
    open.href = url;
    currentIdx = (typeof idx === 'number') ? idx : -1;

    let node = null;

    if (ft === 'pdf') {
      node = renderIframe(url + '#view=FitH');
    } else if (ft === 'video') {
      node = document.createElement('video');
      node.src = url;
      node.controls = true;
      node.className = 'w-full h-auto max-h-[60vh] bg-black';
    } else if (ft === 'image') {
      const holder = document.createElement('div');
      holder.className = 'relative w-full grid place-items-center p-2';
      const img = document.createElement('img');
      img.src = url;
      img.alt = 'Preview';
      img.className = 'max-h-[70vh] mx-auto rounded shadow';
      holder.appendChild(img);

      const nav = document.createElement('div');
      nav.className = 'absolute inset-y-0 left-0 right-0 flex justify-between items-center px-4';
      nav.innerHTML = `
        <button class="btn btn-circle btn-sm" onclick="lightboxPrev()">❮</button>
        <button class="btn btn-circle btn-sm" onclick="lightboxNext()">❯</button>
      `;
      holder.appendChild(nav);
      node = holder;
    } else {
      wrap.classList.add('grid','place-items-center','p-6');
      wrap.innerHTML = '<div class="text-sm opacity-70 text-center">Pratinjau tidak tersedia.<br>Silakan unduh atau gunakan Office Online.</div>';
      const officeUrl = buildOfficeViewer(url);
      if (officeUrl) { office.href = officeUrl; office.classList.remove('hidden'); }
    }

    if (node) {
      wrap.classList.remove('grid','place-items-center','p-6');
      wrap.appendChild(node);
    }

    alt.classList.remove('hidden');
  }

  window.lightboxPrev = function(){
    if (!currentFiles.length || currentIdx < 0) return;
    do { currentIdx = (currentIdx - 1 + currentFiles.length) % currentFiles.length; }
    while (fileType(currentFiles[currentIdx].path) !== 'image' && currentFiles.some(f => fileType(f.path) === 'image'));
    const f = currentFiles[currentIdx];
    showPreview(toUrl(f.path), fileType(f.path), currentIdx);
  };
  window.lightboxNext = function(){
    if (!currentFiles.length || currentIdx < 0) return;
    do { currentIdx = (currentIdx + 1) % currentFiles.length; }
    while (fileType(currentFiles[currentIdx].path) !== 'image' && currentFiles.some(f => fileType(f.path) === 'image'));
    const f = currentFiles[currentIdx];
    showPreview(toUrl(f.path), fileType(f.path), currentIdx);
  };

  function renderList(files){
    currentFiles = files;
    const list = document.getElementById('att-list');
    list.innerHTML = '';
    files.forEach((f, idx) => {
      const abs = toUrl(f.path || '');
      const ft  = fileType(abs);
      const b   = fileBadge(ft);
      const li = document.createElement('li');
      li.innerHTML = `
        <a href="#" class="flex items-center gap-3" title="${(f.display || '').replace(/"/g,'&quot;')}">
          <span class="badge badge-ghost">${b.icon} ${b.label}</span>
          <span class="line-clamp-1">${f.display || f.path || 'file'}</span>
        </a>
      `;
      li.querySelector('a').addEventListener('click', (e) => {
        e.preventDefault();
        showPreview(abs, ft, idx);
      });
      list.appendChild(li);
    });
  }

  window.openAttachmentsModal = function(btn){
    const title = btn.getAttribute('data-article-title') || "{{ __('Attachments') }}";
    let files = [];
    try { files = JSON.parse(btn.getAttribute('data-files') || '[]'); } catch(e) { files = []; }

    const titleEl = document.getElementById('att-title');
    const modal = document.getElementById('attachments-modal');

    titleEl.textContent = title;
    renderList(files);
    clearPreview();

    if (modal && !modal.checked) modal.checked = true;

    setTimeout(() => {
      const closeBtn = document.querySelector('label[for="attachments-modal"].btn');
      if (closeBtn) closeBtn.focus({preventScroll:true});
    }, 0);
  };

  document.addEventListener('keydown', (e) => {
    const modal = document.getElementById('attachments-modal');
    if (!modal || !modal.checked) return;
    if (e.key === 'ArrowLeft') { e.preventDefault(); window.lightboxPrev(); }
    if (e.key === 'ArrowRight'){ e.preventDefault(); window.lightboxNext(); }
    if (e.key === 'Escape') modal.checked = false;
  });

  document.addEventListener('livewire:navigated', () => { /* no-op */ });
})();
</script>
@endpush