<?php

namespace App\Livewire;

use App\Enums\ArticleType;
use App\Models\Article;
use Livewire\Component;

class Home extends Component
{

    public function render()
    {
        return view('livewire.home', [
            'articles' => Article::where('is_active', true)->whereIn('article_type_id', [ArticleType::News->value, ArticleType::Gallery->value])
                        ->where('published_at', '<=', now())
                        ->orderBy('published_at', 'desc')->take(12)->get(),
        ]);
    }
}
