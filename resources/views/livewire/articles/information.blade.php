<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Header & picker parent category --}}
    <header class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold">{{ __('Public Information List') }}</h1>
        <p class="text-sm text-base-content/70 mt-1">
            {{ __('Select an public information type to view a list of documents/articles.') }}
        </p>

        <div class="mt-4 flex flex-wrap gap-2">
            @foreach($parents as $parent)
            <button
                class="btn btn-sm {{ $parentCategory === $parent->id ? 'btn-primary' : 'btn-outline' }}"
                wire:click="$set('parentCategory', {{ $parent->id }})">
                {{ $parent->name }}
            </button>
            @endforeach
        </div>
    </header>

    {{-- Accordion per kategori anak --}}
    @if($children->isEmpty())
    <div class="alert alert-error alert-soft">{{ __('No :name available.', ['name' => strtolower(__('Public Information'))]) }}</div>
    @else

    <div class="flex flex-col gap-3">
        @foreach($children as $child)
        @php
        $list = $groups->get($child->id) ?? collect();
        $count = $list->count();
        @endphp
        @if($count > 0)
        <div class="collapse collapse-plus bg-base-100 border border-base-300 rounded-box">
            <input type="radio" name="information{{ $child->parent_id }}" />
            <div class="collapse-title text-base font-medium">
                {{ $child->name }}
                <span class="badge badge-sm ml-5 badge-warning">{{ $count }}</span>
            </div>
            <div class="collapse-content">
                @if($count === 0)
                <p class="text-sm text-base-content/60">{{ __('No :name available.', ['name' => strtolower(__('Public Information'))]) }}</p>
                @else
                <ul class="w-full menu">
                    @foreach($list as $a)
                    @php
                    // Parse files & original_files
                    $filesArr = [];
                    if (is_string($a->files) && trim($a->files) !== '') {
                        try { $filesArr = json_decode($a->files, true) ?? []; } catch (\Throwable $e) { $filesArr = []; }
                    } elseif (is_array($a->files)) {
                        $filesArr = $a->files;
                    }

                    $origMap = [];
                    if (is_string($a->original_files) && trim($a->original_files) !== '') {
                        try {
                            $tmp = json_decode($a->original_files, true);

                            if (is_array($tmp)) {
                                // CASE 1: object flat => ['stored.ext' => 'Nama Manusiawi.ext', ...]
                                $isAssoc = array_keys($tmp) !== range(0, count($tmp) - 1);
                                if ($isAssoc) {
                                    $origMap = $tmp;
                                } else {
                                    // CASE 2: array of maps => [ {'stored.ext':'Nama'}, {'stored2.ext':'Nama2'} ]
                                    foreach ($tmp as $entry) {
                                        if (is_array($entry)) {
                                            foreach ($entry as $k => $v) {
                                                $origMap[(string)$k] = (string)$v;
                                            }
                                        }
                                    }
                                }
                            }
                        } catch (\Throwable $e) {
                            $origMap = [];
                        }
                    } elseif (is_array($a->original_files)) {
                        // Bisa object flat (assoc) atau array of maps
                        $isAssoc = array_keys($a->original_files) !== range(0, count($a->original_files) - 1);
                        if ($isAssoc) {
                            foreach ($a->original_files as $k => $v) {
                                $origMap[(string)$k] = (string)$v;
                            }
                        } else {
                            foreach ($a->original_files as $entry) {
                                if (is_array($entry)) {
                                    foreach ($entry as $k => $v) {
                                        $origMap[(string)$k] = (string)$v;
                                    }
                                }
                            }
                        }
                    }


                    $payload = [];
                    foreach ($filesArr as $rel) {
                        $rel = (string) $rel;
                        $payload[] = [
                            'path'    => $rel,
                            'display' => $origMap[$rel] ?? basename($rel), // ← sekarang benar-benar “human readable”
                        ];
                    }
                    $hasFiles = count($payload) > 0;
                    $yearVal = $a->year ?: optional($a->published_at)->format('Y');
                    @endphp

                    <li class="py-1">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            {{-- Kolom kiri: Judul --}}
                            <a
                                wire:navigate
                                href="{{ url('/information/'.$a->slug).'?parent='.$child->parent_id }}"
                                title="{{ $a->title }}"
                                class="hover:link text-left flex-1">
                                <span class="line-clamp-1">{{ $a->title }}</span>
                            </a>

                            {{-- Kolom kanan: Tahun + Lampiran --}}
                            <div class="flex items-center gap-10 justify-end">
                                <div class="text-xs sm:text-sm text-base-content/70">
                                    {{ $yearVal ?: '' }}
                                </div>

                                @if($hasFiles)
                                    <button
                                        type="button"
                                        class="btn btn-soft btn-primary sm:btn-sm"
                                        data-article-title="{{ $a->title }}"
                                        data-files='@json($payload)'
                                        onclick="openAttachmentsModal(this)"
                                        title="{{ __('View :name', ['name' => __('Files')]) }}">
                                        <i class="bi bi-paperclip"></i> {{ __('Attachments')}} ({{ count($payload) }})
                                    </button>
                                @endif
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @endif
            </div>
        </div>
        @endif
        @endforeach
    </div>
    @endif

    {{-- =================== MODAL LAMPIRAN (dengan preview & lightbox) =================== --}}
    <div id="attachments-modal-host">
        <input type="checkbox" id="attachments-modal" class="modal-toggle" />
        <div class="modal" role="dialog" aria-modal="true">
            <div class="modal-box max-w-5xl p-0 md:p-0">
                {{-- Header --}}
                <div class="px-4 py-3 border-b border-base-200 flex items-center justify-between">
                    <h3 id="att-title" class="font-semibold text-lg">{{ __('Attachments') }}</h3>
                    <label for="attachments-modal" class="btn btn-sm btn-ghost">✕</label>
                </div>

                {{-- Body: List kiri + Preview kanan --}}
                <div class="p-3 md:p-4">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 md:gap-4">
                        {{-- List file --}}
                        <div class="md:col-span-5">
                            <ul id="att-list" class="menu"></ul>
                        </div>

                        {{-- Preview --}}
                        <div class="md:col-span-7">
                            <div id="att-preview-wrap" class="border border-base-200 rounded-xl overflow-hidden min-h-[240px] grid place-items-center">
                                <div id="att-preview-empty" class="text-sm opacity-70 p-6 text-center">
                                    {{ __('Select a file from the list to preview here.') }}
                                </div>
                                <div id="att-preview" class="w-full h-full hidden"></div>
                            </div>

                            {{-- Link alternatif --}}
                            <div id="att-alt" class="mt-3 flex flex-wrap items-center gap-2">
                                <a id="att-open" href="#" target="_blank" rel="noopener" class="btn btn-sm btn-primary">{{ __('Open in New Tab') }}</a>
                                <a id="att-office" href="#" target="_blank" rel="noopener" class="btn btn-sm btn-outline">Preview via Office Online (beta)</a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
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
    (function() {
        const STORAGE_BASE = "{{ rtrim(asset('storage'), '/') }}/";

        function toUrl(path) {
            if (!path) return '';
            if (/^https?:\/\//i.test(path)) return path;
            return STORAGE_BASE + path.replace(/^\/+/, '');
        }

        function fileType(path) {
            const ext = (path.split('.').pop() || '').toLowerCase();
            if (['pdf'].includes(ext)) return 'pdf';
            if (['mp4', 'webm', 'mov', 'm4v', 'avi'].includes(ext)) return 'video';
            if (['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'].includes(ext)) return 'image';
            if (['doc', 'docx', 'odt', 'rtf'].includes(ext)) return 'word';
            if (['xls', 'xlsx', 'ods', 'csv'].includes(ext)) return 'sheet';
            if (['ppt', 'pptx', 'odp', 'key'].includes(ext)) return 'ppt';
            if (['zip', 'rar', '7z', 'tar', 'gz'].includes(ext)) return 'zip';
            return 'file';
        }

        function fileBadge(ft) {
            switch (ft) {
                case 'pdf':
                    return {
                        label: 'PDF', icon: '<i class="bi bi-file-pdf-fill"></i>'
                    };
                case 'video':
                    return {
                        label: 'VIDEO', icon: '<i class="bi bi-file-play-fill"></i>'
                    };
                case 'image':
                    return {
                        label: 'IMAGE', icon: '<i class="bi bi-file-image"></i>'
                    };
                case 'word':
                    return {
                        label: 'WORD', icon: '<i class="bi bi-file-word-fill"></i>'
                    };
                case 'sheet':
                    return {
                        label: 'SHEET', icon: '<i class="bi bi-file-spreadsheet-fill"></i>'
                    };
                case 'ppt':
                    return {
                        label: 'PPT', icon: '<i class="bi bi-file-easel-fill"></i>'
                    };
                case 'zip':
                    return {
                        label: 'ZIP', icon: '<i class="bi bi-file-zip-fill"></i>'
                    };
                default:
                    return {
                        label: 'FILE', icon: '<i class="bi bi-file"></i>'
                    };
            }
        }

        function buildOfficeViewer(url) {
            try {
                const u = new URL(url);
                if (u.protocol !== 'https:') return null;
            } catch (e) {
                return null;
            }
            return 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURIComponent(url);
        }

        function clearPreview() {
            const wrap = document.getElementById('att-preview');
            const empty = document.getElementById('att-preview-empty');
            const alt = document.getElementById('att-alt');
            wrap.innerHTML = '';
            wrap.classList.add('hidden');
            empty.classList.remove('hidden');
            alt.classList.add('hidden');
            currentIdx = -1;
            currentFiles = [];
        }

        function renderIframe(url) {
            const node = document.createElement('iframe');
            node.src = url;
            node.className = 'w-full h-[60vh]';
            node.loading = 'lazy';
            node.allow = 'fullscreen';
            return node;
        }

        let currentFiles = [];
        let currentIdx = -1;

        function showPreview(url, ft, idx) {
            const wrap = document.getElementById('att-preview');
            const empty = document.getElementById('att-preview-empty');
            const alt = document.getElementById('att-alt');
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
                node = holder;
            } else {
                // tidak didukung preview
                wrap.classList.add('grid', 'place-items-center', 'p-6');
                wrap.innerHTML = '<div class="text-sm opacity-70 text-center">{{ __("Preview is not available. Please download or use Office Online.") }}</div>';
                const officeUrl = buildOfficeViewer(url);
                if (officeUrl) {
                    office.href = officeUrl;
                    office.classList.remove('hidden');
                }
            }

            if (node) {
                wrap.classList.remove('grid', 'place-items-center', 'p-6');
                wrap.appendChild(node);
            }

            alt.classList.remove('hidden');
        }

        window.lightboxPrev = function() {
            if (!currentFiles.length || currentIdx < 0) return;
            // loncat ke image sebelumnya (skip non-image jika ada image di daftar)
            do {
                currentIdx = (currentIdx - 1 + currentFiles.length) % currentFiles.length;
            }
            while (fileType(currentFiles[currentIdx].path) !== 'image' && currentFiles.some(f => fileType(f.path) === 'image'));
            const f = currentFiles[currentIdx];
            showPreview(toUrl(f.path), fileType(f.path), currentIdx);
        };
        window.lightboxNext = function() {
            if (!currentFiles.length || currentIdx < 0) return;
            // loncat ke image berikutnya
            do {
                currentIdx = (currentIdx + 1) % currentFiles.length;
            }
            while (fileType(currentFiles[currentIdx].path) !== 'image' && currentFiles.some(f => fileType(f.path) === 'image'));
            const f = currentFiles[currentIdx];
            showPreview(toUrl(f.path), fileType(f.path), currentIdx);
        };

        function renderList(files) {
            currentFiles = files;
            const list = document.getElementById('att-list');
            list.innerHTML = '';

            files.forEach((f, idx) => {
                const abs = toUrl(f.path || '');
                const ft = fileType(abs);
                const b = fileBadge(ft);

                const li = document.createElement('li');
                li.innerHTML = `
        <a href="#" class="flex items-center gap-3">
          <span class="badge badge-ghost">${b.icon} ${b.label}</span>
          <span class="line-clamp-1" title="${(f.display || '').replace(/"/g,'&quot;')}">${f.display || f.path || 'file'}</span>
        </a>
      `;
                li.querySelector('a').addEventListener('click', (e) => {
                    e.preventDefault();
                    showPreview(abs, ft, idx);
                });
                list.appendChild(li);
            });
        }

        window.openAttachmentsModal = function(btn) {
            const title = btn.getAttribute('data-article-title') || "{{ __('Attachments') }}";
            let files = [];
            try {
                files = JSON.parse(btn.getAttribute('data-files') || '[]');
            } catch (e) {
                files = [];
            }

            const titleEl = document.getElementById('att-title');
            const modal = document.getElementById('attachments-modal');

            titleEl.textContent = title;
            renderList(files);
            clearPreview();

            if (modal && !modal.checked) modal.checked = true;

            setTimeout(() => {
                const closeBtn = document.querySelector('label[for="attachments-modal"].btn');
                if (closeBtn) closeBtn.focus({
                    preventScroll: true
                });
            }, 0);
        };

        // Keyboard nav
        document.addEventListener('keydown', (e) => {
            const modal = document.getElementById('attachments-modal');
            if (!modal || !modal.checked) return;
            if (e.key === 'ArrowLeft') {
                e.preventDefault();
                window.lightboxPrev();
            }
            if (e.key === 'ArrowRight') {
                e.preventDefault();
                window.lightboxNext();
            }
            if (e.key === 'Escape') modal.checked = false;
        });

        document.addEventListener('livewire:navigated', () => {
            /* no-op */ });
    })();
</script>
@endpush