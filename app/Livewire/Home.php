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
            'posts' => Article::where('is_active', true)->whereIn('article_type_id', [ArticleType::Blog->value, ArticleType::News->value])
                        ->orderBy('published_at', 'desc')->take(10)->get()
        ]);
    }
}
