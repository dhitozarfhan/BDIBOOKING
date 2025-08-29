<?php

namespace App\Livewire\Articles;

use App\Enums\ArticleType;
use App\Enums\CategoryType;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;

class Information extends Component
{
    /** Parent category id: -1|-2|-3|-4 (default: -1 “Informasi Berkala”) kunci hanya -1|-2|-3|-4 */
    #[Url(as: 'parent', keep: true)]
    public string|int|null $parentCategory = -1;

    public function mount(): void
    {
        // dd($this->parentCategory);
        $this->parentCategory = in_array((int) request()->query('parent', -1), [-1, -2, -3, -4]) ? (int) request()->query('parent', -1) : -1;
    }

    /** id type artikel untuk “information” — sesuaikan jika proyekmu berbeda */
    protected function informationTypeId(): int
    {
        return ArticleType::Information->value;
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
            //where has count of children more than 0
            ->whereIn('id', function ($query) {
                $query->select('category_id')
                    ->from('articles')
                    ->where('article_type_id', $this->informationTypeId())
                    ->groupBy('category_id');
            })
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
            ->where('article_type_id', $this->informationTypeId())
            ->whereIn('category_id', $childIds)
            ->orderBy('sort')
            ->orderByDesc('id')
            ->get();

        // groupBy category_id → [catId => Collection<Article>]
        return $items->groupBy('category_id');
    }

    protected function parentLabel(): string
    {
        return ($category = Category::where('id', $this->parentCategory)->first()) ? $category->name : __('Public Information');
    }

    public function render()
    {
        return view('livewire.articles.information', [
            'parents'  => Category::where('category_type_id', CategoryType::InformationType->value)->get(),
            'children' => $this->childCategories,
            'groups'   => $this->groupedArticles, // map[catId => Collection]
        ])->title(__('Public Information List').' – '.$this->parentLabel());
    }
}
