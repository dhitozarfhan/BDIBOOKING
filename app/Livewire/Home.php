<?php

namespace App\Livewire;

use App\Enums\ArticleType;
use App\Models\Article;
use App\Models\Slideshow;
use Livewire\Component;
use Livewire\Attributes\Computed;

class Home extends Component
{

    protected function baseArticleQuery()
    {
        return Article::query()
            ->with(['category:id,name'])
            ->select(['id','title','summary','image','category_id','article_type_id','published_at','is_active','hit'])
            ->whereIn('article_type_id', [ArticleType::News->value, ArticleType::Gallery->value])
            ->published();
    }

    #[Computed]
    public function slides()
    {
        return Slideshow::where('is_active', true)
            ->orderBy('sort')
            ->get();
    }

    #[Computed]
    public function latest()
    {
        // Landing: tampilkan 12 terbaru (tanpa paginasi agar simpel)
        return $this->baseArticleQuery()
            ->orderByDesc('published_at')
            ->limit(12)->get();
    }

    public function render()
    {
        return view('livewire.home.landing', [
            'slides'  => $this->slides,
            'latest'  => $this->latest,
        ])->title(config('app.name'));
    }
}
