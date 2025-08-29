<?php

namespace App\Livewire\Articles;

use App\Enums\ArticleType;
use App\Enums\CategoryType;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;

class Show extends BaseWithSidebar
{
    public ?Article $article = null;
    
    /** Parent category id: -1|-2|-3|-4 (default: -1 “Informasi Berkala”) kunci hanya -1|-2|-3|-4 */
    #[Url(as: 'parent', keep: true)]
    public string|int|null $parentCategory = -1;

    public function mount(?string $article_type = null, ?string $slug = null): void
    {
        parent::mount($article_type);

        $slug = $slug ?? request()->route('slug') ?? request()->segment(2);
        $id   = Article::idFromSlug((string)$slug);
        abort_if(!$id, 404);

        $typeId = $this->articleTypeId();
        $this->article = Article::query()
            ->published()
            ->when($typeId, fn($q,$tid) => $q->where('article_type_id', $tid))
            ->with([
                'category:id,parent_id,name',
                'author:id,name',
                'tags:id,name',
                'images:id,article_id,path,description'
            ])
            ->findOrFail($id);

        $this->article->increment('hit');

        $this->parentCategory = in_array((int) request()->query('parent', -1), [-1, -2, -3, -4]) ? (int) request()->query('parent', -1) : -1;
    }

    // Search di Show → redirect ke Index dengan ?q= (SPA)
    protected function onSidebarSearchUpdated(string $q): void
    {
        $url = url("/{$this->articleType}".($q !== '' ? ('?q='.urlencode($q)) : ''));
        $this->redirect($url, navigate: true);
    }

    #[Computed]
    public function related()
    {
        if (!$this->article) return collect();
        if (!$this->articleTypeHasCategory()) return collect();
        
        $typeId = $this->articleTypeId();

        return $this->baseArticleQuery()
            ->when($typeId, fn($q,$id) => $q->where('article_type_id', $id))
            ->where('id','<>', $this->article->id)
            ->when($this->article->category_id, fn($q,$cid) => $q->where('category_id', $cid))
            ->orderByDesc('published_at')
            ->limit(6)
            ->get(['id','title','image','published_at']);
    }



    /** Daftar kategori anak di bawah parent terpilih */
    #[Computed]
    public function childCategories(): Collection
    {
        return Category::query()
            ->select(['id','name','parent_id','sort','is_active'])
            ->where('category_type_id', CategoryType::Information->value)
            ->where('parent_id', $this->parentCategory)
            ->where('is_active', true)
            ->orderBy('sort')
            ->orderBy('id')
            ->get();
    }

    /** Artikel bertipe information, dikelompokkan per category_id anak */
    #[Computed]
    public function groupedArticles(): Collection
    {
        $childIds = $this->childCategories->pluck('id');

        if ($childIds->isEmpty()) {
            return collect();
        }

        $items = Article::query()
            ->with(['category:id,name,parent_id']) // optional
            ->select([
                'id',
                'title',
                'summary',
                'image',
                'category_id',
                'article_type_id',
                'is_active',
                'published_at',
                'hit',
                'year',
                'files',
                'original_files',
            ])
            ->published()
            ->where('article_type_id', ArticleType::Information->value)
            ->whereIn('category_id', $childIds)
            ->orderBy('sort')
            ->orderByDesc('id')
            ->get();

        // groupBy category_id → [catId => Collection<Article>]
        return $items->groupBy('category_id');
    }

    protected function pageTitle(): string
    {
        return $this->article
            ? "{$this->article->title} – ".$this->titleBase()
            : $this->titleBase();
    }

    public function render()
    {
        if($this->articleTypeId() === ArticleType::Information->value) {
            return view('livewire.articles.show', [
                'article'   => $this->article,
                'parents'  => Category::where('category_type_id', CategoryType::InformationType->value)->get(),
                'children' => $this->childCategories,
                'groups'   => $this->groupedArticles, // map[catId => Collection]
            ])->title($this->pageTitle());
        }
        else {
            return view('livewire.articles.show', [
                'article'     => $this->article,
                'latest'      => $this->latest,
                'popular'     => $this->popular,
                'categories'  => $this->categories,
                'archives'    => $this->archives,
                'related'     => $this->related,
            ])->title($this->pageTitle());
        }
    }
}