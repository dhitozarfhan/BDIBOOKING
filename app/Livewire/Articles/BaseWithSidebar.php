<?php

namespace App\Livewire\Articles;

use App\Enums\ArticleType;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;

abstract class BaseWithSidebar extends Component
{
    /** ===== Shared state ===== */
    public string $articleType = 'news';

    // Dipakai oleh form pencarian di sidebar.
    #[Url(as: 'q', keep: true)]
    public string $search = '';

    /** ===== Lifecycle ===== */
    public function mount(?string $article_type = null): void
    {
        // Normalisasi tipe dari route/segment
        $type = $article_type ?? request()->route('article_type') ?? request()->segment(1);
        $type = Str::of((string)$type)->lower()->toString();
        $allowed = ['news','gallery','page','information'];
        $this->articleType = in_array($type, $allowed, true) ? $type : 'news';
    }

    /** ===== Hooks untuk turunan ===== */
    // Jika pencarian di sidebar berubah.
    protected function onSidebarSearchUpdated(string $q): void
    {
        // Default: tidak melakukan apa-apa. Kelas turunan boleh override.
    }

    public function updatedSearch(): void
    {
        $this->onSidebarSearchUpdated(trim($this->search));
    }

    /** ===== Helpers bersama ===== */
    protected function articleTypeId(): ?int
    {
        return match ($this->articleType) {
            'news' => ArticleType::News->value,
            'gallery' => ArticleType::Gallery->value,
            'page' => ArticleType::Page->value,
            'information' => ArticleType::Information->value,
            default => null,
        };
    }

    protected function baseArticleQuery()
    {
        return Article::query()
            ->with(['category:id,name'])
            ->select([
                'id','title','summary','image',
                'category_id','article_type_id','is_active','published_at','hit'
            ])
            ->published();
    }

    protected function cacheKey(string $suffix): string
    {
        $type = $this->articleType ?: 'all';
        return "articles.sidebar.{$type}.{$suffix}";
    }

    protected function titleBase(): string
    {
        return match ($this->articleType) {
            'news' => __('News'),
            'gallery' => __('Gallery'),
            'page' => __('Page'),
            'information' => __('Public Information'),
            default => __('Article'),
        };
    }

    /** ====== Sidebar data (shared) ====== */

    #[Computed]
    public function latest()
    {
        $typeId = $this->articleTypeId();
        return Cache::remember($this->cacheKey('latest'), 600, function () use ($typeId) {
            return $this->baseArticleQuery()
                ->when($typeId, fn($q,$id) => $q->where('article_type_id', $id))
                ->orderByDesc('published_at')
                ->limit(5)
                ->get(['id','title','image','published_at']);
        });
    }

    #[Computed]
    public function popular()
    {
        $typeId = $this->articleTypeId();
        return Cache::remember($this->cacheKey('popular'), 600, function () use ($typeId) {
            return $this->baseArticleQuery()
                ->when($typeId, fn($q,$id) => $q->where('article_type_id', $id))
                //where previous 12 months until now
                ->whereBetween('published_at', [now()->copy()->subMonths(12), now()])
                ->orderByDesc('hit')->orderByDesc('published_at')
                ->limit(5)
                ->get(['id','title','image','hit','published_at']);
        });
    }

    #[Computed]
    public function categories()
    {
        $typeId = $this->articleTypeId();

        return Cache::remember($this->cacheKey('categories'), 600, function () use ($typeId) {
            $counts = Article::query()
                ->selectRaw('category_id, COUNT(*) as total')
                ->where('is_active', true)
                ->whereNotNull('published_at')
                ->where('published_at','<=', now())
                ->when($typeId, fn($q) => $q->where('article_type_id', $typeId))
                ->groupBy('category_id')
                ->pluck('total','category_id');

            // Tidak ada kolom slug; cukup id & name — slug disediakan accessor di model
            $cats = Category::query()
                ->select(['id','name'])
                ->whereIn('id', $counts->keys())
                ->orderBy('name->' . app()->getLocale())
                ->get();

            return $cats->map(function ($c) use ($counts) {
                $c->articles_count = (int) ($counts[$c->id] ?? 0);
                return $c;
            });
        });
    }

    #[Computed]
    public function archives()
    {
        $typeId = $this->articleTypeId();

        return Cache::remember($this->cacheKey('archives'), 600, function () use ($typeId) {
            $base = Article::query()
                ->where('is_active', true)
                ->whereNotNull('published_at')
                ->where('published_at','<=', now())
                ->when($typeId, fn($q) => $q->where('article_type_id', $typeId));

            return (clone $base)
                ->selectRaw('EXTRACT(YEAR FROM published_at) as y, EXTRACT(MONTH FROM published_at) as m, COUNT(*) as total')
                ->groupBy('y','m')->orderByDesc('y')->orderByDesc('m')->limit(12)->get();
        });
    }

    /** Palet warna DaisyUI untuk teks/badge (urutan bebas) */
    protected array $tagPalette = [
        'p-3 text-white bg-primary',
        'p-3 text-white bg-error',
        'p-3 text-white bg-info-content',
        'p-3 text-white bg-success-content',
        'p-3 text-white bg-warning-content',
    ];

    /** Warna stabil dari id (crc32 % palette) */
    protected function colorClassForId(int $id): string
    {
        $n = count($this->tagPalette) ?: 1;
        $idx = crc32((string) $id) % $n;
        return $this->tagPalette[$idx];
    }

    /** Boleh dipanggil dari Blade: {{ $this->tagColor($tag->id) }} */
    public function tagColor(int|string $id): string
    {
        return $this->colorClassForId((int) $id);
    }

    #[Computed]
    public function tagsCloud()
    {
        $typeId = $this->articleTypeId();

        // Ambil jumlah pemakaian tag di artikel yang published & active
        $rows = Tag::query()
            ->selectRaw('tags.id, tags.name, COUNT(*) as total')
            ->join('article_tag', 'tags.id', '=', 'article_tag.tag_id')
            ->join('articles', 'articles.id', '=', 'article_tag.article_id')
            ->where('articles.is_active', true)
            ->whereNotNull('articles.published_at')
            ->where('articles.published_at', '<=', now())
            ->when($typeId, fn($q,$id) => $q->where('articles.article_type_id', $id))
            ->groupBy('tags.id','tags.name->'.app()->getLocale())
            ->orderByDesc('total')
            ->limit(30)
            ->get();

        // Siapkan nilai min/max untuk skala
        $min = (int) ($rows->min('total') ?? 0);
        $max = (int) ($rows->max('total') ?? 0);

        return $rows->map(function ($t) use ($min, $max) {
            $t->weight = $this->normalizeTagWeight((int) $t->total, $min, $max); // 1..5
            $t->color  = $this->colorClassForId((int) $t->id);
            return $t;
        });
    }

    /**
     * Konversi count → bobot 1..5 (untuk ukuran font/opacity).
     */
    protected function normalizeTagWeight(int $count, int $min, int $max): int
    {
        if ($max <= $min) return 3; // semua sama
        $ratio = ($count - $min) / max(1, ($max - $min));
        // bucket 1..5
        return 1 + (int) floor($ratio * 4);
    }
}
