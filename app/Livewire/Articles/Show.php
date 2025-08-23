<?php

namespace App\Livewire\Articles;

use App\Enums\ArticleType;
use Livewire\Component;

class Show extends Component
{
    public function render()
    {
        $uri = request()->segment(1);
        if (!in_array($uri, ['news', 'blog', 'gallery', 'page', 'information'])) {
            // redirect to home in livewire
            $this->redirect('/');
        }
        $articleTypeId = $uri == 'blog' ? ArticleType::Blog->value : (
            $uri == 'news' ? ArticleType::News->value : (
                $uri == 'gallery' ? ArticleType::Gallery->value : (
                    $uri == 'page' ? ArticleType::Page->value : ArticleType::Information->value
                )
            )
        );
        return view('livewire.articles.show');
    }
}
