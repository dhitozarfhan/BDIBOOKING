<?php

namespace App\Filament\Resources\InformationResource\Pages;

use App\Enums\ArticleType;
use App\Enums\CategoryType;
use App\Filament\Resources\InformationResource;
use App\Models\Article;
use App\Models\Category;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListInformation extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = InformationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $tabs = [];

        $parents = Category::where('category_type_id', CategoryType::InformationType->value)->get();

        foreach($parents as $parent){
            $tabs[] = Tab::make($parent->name)
            ->badge(Article::join('categories', 'categories.id', '=', 'articles.category_id')->where('categories.parent_id', $parent->id)->where('article_type_id', ArticleType::Information->value)->count())
            ->modifyQueryUsing(function ($query) use ($parent) {
                return $query->selectRaw('articles.*')->join('categories', 'categories.id', '=', 'articles.category_id')->where('categories.parent_id', $parent->id)
                ->where('article_type_id', ArticleType::Information->value);
            });
        }

        return $tabs;
    }
}
