<?php

namespace App\Livewire\Articles;

use App\Models\Category;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class Index extends BaseWithSidebar
{
    use WithPagination;

    #[Url(as: 'author', keep: true)]
    public ?string $author = null;

    #[Url(as: 'tag', keep: true)]
    public ?string $tag = null;

    #[Url(as: 'category')]
    public ?string $categorySlug = null;

    #[Url(as: 'year')]
    public ?int $year = null;

    #[Url(as: 'month')]
    public ?int $month = null;

    #[Url(as: 'view', keep: true)]
    public string $viewMode = 'grid';

    #[Url(as: 'perPage', keep: true)]
    public int $perPage = 12;

    protected string $paginationTheme = 'tailwind';

    public function updatedAuthor(): void
    {
        $this->resetPage();
    }

    public function updatedCategorySlug(): void { $this->resetPage(); }
    public function updatedYear(): void { $this->resetPage(); }
    public function updatedMonth(): void { $this->resetPage(); }
    public function updatedPerPage(): void
    {
        $this->resetPage();
        session(["articles.{$this->articleType}.perPage" => $this->perPage]);
    }

    public function updatedViewMode(): void
    {
        session(["articles.{$this->articleType}.viewMode" => $this->viewMode]);
    }

    protected function onSidebarSearchUpdated(string $q): void
    {
        $this->resetPage();
    }

    public function mount(?string $article_type = null): void
    {
        parent::mount($article_type);

        // preferensi dari session
        $prefix = "articles.{$this->articleType}";
        if (session()->has("$prefix.viewMode")) {
            $this->viewMode = session("$prefix.viewMode");
        }
        if (session()->has("$prefix.perPage")) {
            $this->perPage = (int) session("$prefix.perPage");
        }

        // normalisasi
        $this->viewMode = in_array($this->viewMode, ['grid','list'], true) ? $this->viewMode : 'grid';
        $this->perPage  = in_array($this->perPage, [6,9,12,15,18,24], true) ? $this->perPage : 9;
    }

    /**
     * Ambil ID kategori dari slug "nama-kategori-123".
     */
    protected function categoryIdFromSlug(?string $slug): ?int
    {
        if (!$slug) return null;
        return Category::idFromSlug($slug);
    }

    #[Computed]
    public function articles()
    {
        $q = $this->baseArticleQuery();

        if ($typeId = $this->articleTypeId()) {
            $q->where('article_type_id', $typeId);
        }

        // Filter kategori berbasis slug → ambil ID terakhir
        if ($cid = $this->categoryIdFromSlug($this->categorySlug)) {
            $q->where('category_id', $cid);
        }

        // Filter arsip tahun/bulan (DB-agnostic)
        if ($this->year) {
            $q->whereYear('published_at', $this->year);
            if ($this->month) {
                $q->whereMonth('published_at', $this->month);
            }
        }

        // 🔎 Filter pencarian (title/summary)
        if (mb_strlen(trim($this->search)) >= 2) {
            $term = '%' . str_replace(['%', '_'], ['\%', '\_'], trim($this->search)) . '%';
            $q->where(function ($qq) use ($term) {
                $qq->where('title', 'ILIKE', $term)
                   ->orWhere('summary', 'ILIKE', $term)
                   ->orWhere('content', 'ILIKE', $term);
            });
        }

        //FILTER AUTHOR
        if ($this->author) {
            $authorId = method_exists(\App\Models\Employee::class, 'idFromSlug')
                ? \App\Models\Employee::idFromSlug($this->author)
                : (ctype_digit((string)$this->author) ? (int)$this->author : null);

            if ($authorId) {
                $q->where('author_id', $authorId);
            } else {
                $authorName = trim($this->author);
                if ($authorName !== '') {
                    // fallback by name (case-insensitive LIKE)
                    $term = '%' . str_replace(['%','_'], ['\%','\_'], $authorName) . '%';
                    $q->whereHas('author', fn($qq) => $qq->where('name', 'like', $term));
                }
            }
        }
        if ($this->tag) {
            // Jika kamu punya Tag::idFromSlug, pakai itu; kalau tidak, coba parse int
            $tagId = method_exists(\App\Models\Tag::class, 'idFromSlug')
                ? \App\Models\Tag::idFromSlug($this->tag)
                : (ctype_digit((string)$this->tag) ? (int)$this->tag : null);

            if ($tagId) {
                $q->whereHas('tags', fn($qq) => $qq->where('tags.id', $tagId));
            } else {
                // fallback jika param berisi nama tag
                $name = trim($this->tag);
                if ($name !== '') {
                    $q->whereHas('tags', fn($qq) => $qq->where('tags.name', $name));
                }
            }
        }

        // === buat paginator ===
        $paginator = $q->orderByDesc('published_at')->orderByDesc('id')->paginate($this->perPage);

        // 1) paksa base path ke route index artikel (BUKAN /livewire/update)
        $basePath = route('articles.index', ['article_type' => $this->articleType]);
        $paginator->withPath($basePath);

        // 2) tambahkan query yang perlu dipertahankan
        $paginator->appends($this->paginationQuery());

        return $paginator;
    }

    /** Query yang dipertahankan di pagination (tanpa 'page') */
    protected function paginationQuery(): array
    {
        $q = [
            'category' => $this->categorySlug ?: null,
            'year'     => $this->year ?: null,
            'month'    => $this->month ?: null,
            'tag'      => $this->tag ?: null,
            'author'   => $this->author ?: null,  
            'q'        => trim($this->search) ?: null,
            'view'     => in_array($this->viewMode, ['grid','list'], true) ? $this->viewMode : null,
            'perPage'  => in_array($this->perPage, [6,9,12,15,18,24], true) ? $this->perPage : null,
        ];

        // buang null/kosong agar URL rapi
        return array_filter($q, fn($v) => !is_null($v) && $v !== '');
    }
    
    public function render()
    {
        return view('livewire.articles.index', [
            'items'      => $this->articles,
            'latest'     => $this->latest,
            'popular'    => $this->popular,
            'categories' => $this->categories,
            'archives'   => $this->archives,
            'view'       => $this->viewMode,
        ])->title($this->pageTitle());
    }

    protected function pageTitle(): string
    {
        $base = $this->titleBase();

        // Tampilkan nama kategori yang sedang difilter (bukan slug mentah)
        $extra = [];
        if ($cid = $this->categoryIdFromSlug($this->categorySlug)) {
            $name = optional(Category::select('id','name')->find($cid))->name;
            if ($name) $extra[] = __('Category') . ": " . $name;
        }
        if ($this->author) {
            // tampilkan “Penulis: ...” (coba resolve nama ketika slug/id)
            $authorName = null;
            if (method_exists(\App\Models\Employee::class, 'idFromSlug')) {
                if ($id = \App\Models\Employee::idFromSlug($this->author)) {
                    $authorName = optional(\App\Models\Employee::select('id','name')->find($id))->name;
                }
            }
            $authorName = $authorName ?: $this->author;
            $extra[] = __('Author') . ": {$authorName}";
        }
        if ($this->year) {
            $extra[] = __('Archive') . ": " . \Carbon\Carbon::createFromDate($this->year, $this->month, 1)->translatedFormat('F Y');
        }

        return trim($base.(empty($extra) ? '' : ' – '.implode(' • ',$extra)));
    }
}
