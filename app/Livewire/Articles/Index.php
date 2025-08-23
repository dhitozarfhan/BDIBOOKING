<?php

namespace App\Livewire\Articles;

use App\Enums\ArticleType as EnumsArticleType;
use App\Models\Article;
use App\Models\ArticleType;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    public string $view = 'grid'; // default grid

    protected $queryString = [
        'page' => ['except' => 1],
        'view' => ['except' => 'grid'],
    ];

    public function setView(string $v)
    {
        $this->view = in_array($v, ['grid','list']) ? $v : 'grid';
        $this->resetPage();

        // beri tahu browser untuk simpan ke localStorage
        // $this->dispatch('articles-view-changed', view: $this->view);
    }

    protected function baseQuery()
    {
        $uri = request()->segment(1);
        if (!in_array($uri, ['news', 'blog', 'gallery', 'page', 'information'])) {
            // redirect to home in livewire
            // $this->redirect('/');
        }
        $articleTypeId = $uri == 'blog' ? EnumsArticleType::Blog->value : (
            $uri == 'news' ? EnumsArticleType::News->value : (
                $uri == 'gallery' ? EnumsArticleType::Gallery->value : (
                    $uri == 'page' ? EnumsArticleType::Page->value : EnumsArticleType::Information->value
                )
            )
        );
        return Article::query()
            ->where('is_active', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where('article_type_id', $articleTypeId);
    }

    public function render()
    {
        $base = $this->baseQuery();

        // MAIN: daftar terbaru dengan paginasi penuh
        $articles = (clone $base)
            ->orderByDesc('published_at')->orderByDesc('id')
            ->paginate(12);

        // SIDEBAR: populer & terbaru (ringkas)
        $popular = (clone $base)
            ->orderByDesc('hit')->orderByDesc('published_at')
            ->limit(5)->get();

        $latest = (clone $base)
            ->orderByDesc('published_at')->orderByDesc('id')
            ->limit(5)->get();

        // SIDEBAR: kategori & arsip
        $categories = (clone $base)
            ->select('category_id', DB::raw('COUNT(*) as total'))
            ->whereNotNull('category_id')
            ->groupBy('category_id')
            ->orderBy('category_id')
            ->get();

        $archives = (clone $base)
            ->selectRaw('EXTRACT(YEAR FROM published_at) as y, EXTRACT(MONTH FROM published_at) as m, COUNT(*) as total')
            ->groupBy('y','m')
            ->orderBy('y','desc')->orderBy('m','desc')
            ->limit(12)
            ->get();

        return view('livewire.articles.index', compact(
            'articles', 'popular', 'latest', 'categories', 'archives'
        ));
    }
}
