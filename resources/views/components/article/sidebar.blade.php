@props([
  'categories' => collect(),
  'archives'   => collect(),
  'popular'    => collect(),  // top by hit
  'latest'     => collect(),  // latest compact
])

@php
  use Illuminate\Support\Carbon;
  use Illuminate\Support\Str;
  $monthLabel = fn($y,$m) => Carbon::create($y, $m, 1)->translatedFormat('MMMM YYYY');
@endphp

<div class="space-y-6">

  {{-- Terbaru (ringkas) --}}
  <div class="card bg-base-100 border">
    <div class="card-body">
      <h3 class="card-title text-base">Artikel Terbaru</h3>
      <ul class="space-y-3">
        @forelse($latest as $a)
          <li class="text-sm">
            <a wire:navigate href="{{ route('articles.show', ['slug' => Str::kebab($a->title).'-'.$a->id, 'article_type' => $a->articleType->slug]) }}" class="link link-hover">
              {{ $a->title ?? 'Tanpa Judul' }}
            </a>
            <div class="text-xs text-base-content/60">
              {{ optional($a->published_at)->translatedFormat('d M Y') ?? 'Draft' }}
            </div>
          </li>
        @empty
          <li class="text-sm text-base-content/60">Belum ada.</li>
        @endforelse
      </ul>
    </div>
  </div>

  {{-- Terpopuler (ringkas) --}}
  <div class="card bg-base-100 border">
    <div class="card-body">
      <h3 class="card-title text-base">Artikel Terpopuler</h3>
      <ul class="space-y-3">
        @forelse($popular as $a)
          <li class="text-sm">
            <a wire:navigate href="{{ route('articles.show', ['slug' => Str::kebab($a->title).'-'.$a->id, 'article_type' => $a->articleType->slug]) }}" class="link link-hover">
              {{ $a->title ?? 'Tanpa Judul' }}
            </a>
            <div class="text-xs text-base-content/60">
              Dibaca: {{ number_format($a->hit) }}
            </div>
          </li>
        @empty
          <li class="text-sm text-base-content/60">Belum ada.</li>
        @endforelse
      </ul>
    </div>
  </div>

  {{-- Kategori --}}
  <div class="card bg-base-100 border">
    <div class="card-body">
      <h3 class="card-title text-base">Kategori</h3>
      <ul class="menu menu-sm">
        @forelse($categories as $c)
          <li>
            <a href="{{ route('articles.byCategory', $c->category_id) }}">
              Kategori #{{ $c->category_id }}
              <span class="badge badge-ghost">{{ $c->total }}</span>
            </a>
          </li>
        @empty
          <li><span class="disabled">Tidak ada kategori</span></li>
        @endforelse
      </ul>
    </div>
  </div>

  {{-- Arsip Bulan-Tahun --}}
  <div class="card bg-base-100 border">
    <div class="card-body">
      <h3 class="card-title text-base">Arsip Bulan</h3>
      <ul class="menu menu-sm">
        @forelse($archives as $a)
          <li>
            <a href="{{ route('articles.byArchive', ['year' => $a->y, 'month' => $a->m]) }}">
              {{ $monthLabel($a->y, $a->m) }}
              <span class="badge badge-ghost">{{ $a->total }}</span>
            </a>
          </li>
        @empty
          <li><span class="disabled">Belum ada arsip</span></li>
        @endforelse
      </ul>
    </div>
  </div>

</div>