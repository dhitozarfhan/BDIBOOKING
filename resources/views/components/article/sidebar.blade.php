@php
use Illuminate\Support\Arr;
// helper bikin query string bersih
function qs($params) { return \Illuminate\Support\Arr::query($params); }
@endphp

@props([
'latest' => collect(),
'popular' => collect(),
'categories' => collect(),
'archives' => collect(),
'articleType' => 'news',
])

<div class="flex flex-col gap-6">
    {{-- 🔎 Pencarian --}}
    <section class="card bg-base-200 shadow-sm">
        <div class="card-body p-4">
            <h3 class="font-semibold">{{ __('Searching') }}</h3>

            <div class="join w-full mt-3">
                <input
                    type="search"
                    class="input input-bordered join-item w-full"
                    placeholder="{{ __('Search title or content of post') }}"
                    wire:model.live.debounce.500ms="search"
                    aria-label="{{ __('Search Posts') }}" />
                <button
                    class="btn btn-soft join-item"
                    type="button"
                    title="Bersihkan"
                    wire:click="$set('search','')"
                    wire:loading.attr="disabled">
                    {{ __('Reset') }}
                </button>
            </div>

            @if(isset($this->search) && strlen(trim($this->search))>=2)
            <p class="mt-2 text-xs text-base-content/60">
                {{ __('Showing results for') }}: <span class="font-medium">“{{ $this->search }}”</span>
            </p>
            @endif
        </div>
    </section>

    {{-- Terbaru --}}
    <section class="card bg-base-200 shadow-sm">
        <div class="card-body p-4">
            <h3 class="font-semibold">{{ __('Recent Posts') }}</h3>
            <ul class="mt-3 space-y-3">
                @forelse($latest as $a)
                <li class="flex gap-3">
                    @if($a->image)
                    <a wire:navigate href="{{ url('/'.$articleType.'/'.$a->slug) }}" class="w-16 h-16 shrink-0 overflow-hidden rounded-md">
                        <img src="{{ asset('storage/'.$a->image) }}" alt="{{ $a->title }}" class="w-full h-full object-cover" loading="lazy">
                    </a>
                    @endif
                    <div class="min-w-0">
                        <a wire:navigate href="{{ url('/'.$articleType.'/'.$a->slug) }}" class="hover:link line-clamp-2 text-sm font-medium">{{ $a->title }}</a>
                        <div class="text-xs text-base-content/60 mt-1">{{ optional($a->published_at)->translatedFormat('d M Y') }}</div>
                    </div>
                </li>
                @empty
                <li class="text-sm text-base-content/60">{{ __('No :name available.', ['name' => strtolower(__('Recent Posts'))]) }}</li>
                @endforelse
            </ul>
        </div>
    </section>

    {{-- Terpopuler --}}
    <section class="card bg-base-200 shadow-sm">
        <div class="card-body p-4">
            <h3 class="font-semibold">{{ __('Popular Posts') }}</h3>
            <ul class="mt-3 space-y-3">
                @forelse($popular as $a)
                <li class="flex items-start gap-3">
                    <span class="badge badge-sm">{{ number_format($a->hit) }}x</span>
                    <div class="min-w-0">
                        <a href="{{ url('/'.$articleType.'/'.$a->slug) }}" class="hover:link line-clamp-2 text-sm font-medium">{{ $a->title }}</a>
                        <div class="text-xs text-base-content/60 mt-1">{{ optional($a->published_at)->translatedFormat('d M Y') }}</div>
                    </div>
                </li>
                @empty
                <li class="text-sm text-base-content/60">{{ __('No :name available.', ['name' => strtolower(__('Popular Posts'))]) }}</li>
                @endforelse
            </ul>
        </div>
    </section>

    {{-- Kategori --}}
    <section class="card bg-base-200 shadow-sm">
        <div class="card-body p-4">
            <h3 class="font-semibold">{{ __('Categories') }}</h3>
            <div class="mt-3 flex flex-wrap gap-2">
                @forelse($categories as $c)
                <a wire:navigate class="badge badge-outline"
                    href="{{ url('/'.$articleType) . '?' . qs(['category' => $c->slug ?? $c->category_id]) }}">
                    {{ $c->name }} <span class="ml-1 opacity-60">({{ $c->articles_count }})</span>
                </a>
                @empty
                <span class="text-sm text-base-content/60">{{ __('No :name available.', ['name' => strtolower(__('Categories'))]) }}</span>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Arsip --}}
    <section class="card bg-base-200 shadow-sm">
        <div class="card-body p-4">
            <h3 class="font-semibold">{{ __('Archives') }}</h3>
            <ul class="mt-3 space-y-2">
                @forelse($archives as $ar)
                @php
                $y = (int) $ar->y;
                $m = (int) $ar->m;
                $label = \Carbon\Carbon::createFromDate($y, $m, 1)->translatedFormat('F Y');
                @endphp
                <li>
                    <a wire:navigate class="hover:link text-sm"
                        href="{{ url('/'.$articleType) . '?' . qs(['year' => $ar->y, 'month' => $ar->m]) }}">
                        {{ $label }} <span class="opacity-60">({{ $ar->total }})</span>
                    </a>
                </li>
                @empty
                <li class="text-sm text-base-content/60">{{ __('No :name available.', ['name' => strtolower(__('Archives'))]) }}</li>
                @endforelse
            </ul>
        </div>
    </section>

    {{-- Tag Cloud --}}
    <section class="card bg-base-300 shadow-sm">
        <div class="card-body p-4">
            <h3 class="font-semibold">Tag Cloud</h3>

            @php
                $size = fn($w) => match($w){1=>'text-xs',2=>'text-sm',3=>'text-base',4=>'text-lg',5=>'text-xl',default=>'text-sm'};
                $shade = fn($w) => match($w){1=>'opacity-60',2=>'opacity-70',3=>'opacity-80',4=>'opacity-90',5=>'opacity-100',default=>'opacity-80'};
            @endphp

            <div class="mt-3 flex flex-wrap gap-x-3 gap-y-2">
                @forelse($this->tagsCloud as $t)
                <a
                    wire:navigate
                    href="{{ url('/'.$articleType.'?tag='.urlencode($t->slug)) }}"
                    class="hover:underline transition-colors {{ $size($t->weight) }} {{ $shade($t->weight) }} badge badge-outline border-current {{ $t->color }}"
                    title="{{ $t->total }} {{ __('Posts') }}">
                    <i class="bi bi-tag"></i> {{ $t->name }}
                </a>
                @empty
                <span class="text-sm text-base-content/60">{{ __('No :name available.', ['name' => strtolower(__('Tag'))]) }}</span>
                @endforelse
            </div>
        </div>
    </section>
</div>