<?php

namespace App\Livewire\Articles;

use App\Models\Category;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class Index extends BaseWithSidebar
{
    use WithPagination;

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

        $q->orderByDesc('published_at')->orderByDesc('id');

        return $q->paginate($this->perPage);
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
        if ($this->year) {
            $extra[] = __('Archive') . ": " . \Carbon\Carbon::createFromDate($this->year, $this->month, 1)->translatedFormat('F Y');
        }

        return trim($base.(empty($extra) ? '' : ' – '.implode(' • ',$extra)));
    }
}
