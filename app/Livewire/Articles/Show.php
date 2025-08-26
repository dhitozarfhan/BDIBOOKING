<?php

namespace App\Livewire\Articles;

use App\Models\Article;
use Livewire\Attributes\Computed;

class Show extends BaseWithSidebar
{
    public ?Article $article = null;

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
                'category:id,name',
                'author:id,name',
                'tags:id,name',
                'images:id,article_id,path,description'
            ])
            ->select(['id','title','summary','content','image','author_id','category_id','article_type_id','is_active','published_at','hit'])
            ->findOrFail($id);

        $this->article->increment('hit');
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
        $typeId = $this->articleTypeId();

        return $this->baseArticleQuery()
            ->when($typeId, fn($q,$id) => $q->where('article_type_id', $id))
            ->where('id','<>', $this->article->id)
            ->when($this->article->category_id, fn($q,$cid) => $q->where('category_id', $cid))
            ->orderByDesc('published_at')
            ->limit(6)
            ->get(['id','title','image','published_at']);
    }

    protected function pageTitle(): string
    {
        return $this->article
            ? "{$this->article->title} – ".$this->titleBase()
            : $this->titleBase();
    }

    public function render()
    {
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