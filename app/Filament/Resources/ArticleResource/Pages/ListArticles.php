<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Enums\ArticleType;
use App\Filament\Resources\ArticleResource;
use App\Models\Article;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListArticles extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = ArticleResource::class;

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
        $tabs[] = Tab::make(__('All Articles'))
        ->badge(Article::whereIn('article_type_id', [ArticleType::News->value, ArticleType::Gallery->value, ArticleType::Page->value])->count())
        ->icon('heroicon-o-document-text');

        $tabs[] = Tab::make(__('News'))
        ->badge(Article::where('article_type_id', ArticleType::News->value)->count())
        ->modifyQueryUsing(function ($query) {
            return $query->where('article_type_id', ArticleType::News->value);
        })
        ->icon('heroicon-o-newspaper')
        ->badgeColor('info');

        $tabs[] = Tab::make(__('Gallery'))
        ->badge(Article::where('article_type_id', ArticleType::Gallery->value)->count())
        ->modifyQueryUsing(function ($query) {
            return $query->where('article_type_id', ArticleType::Gallery->value);
        })
        ->icon('heroicon-o-camera')
        ->badgeColor('success');

        $tabs[] = Tab::make(__('Page'))
        ->badge(Article::where('article_type_id', ArticleType::Page->value)->count())
        ->modifyQueryUsing(function ($query) {
            return $query->where('article_type_id', ArticleType::Page->value);
        })
        ->icon('heroicon-o-document-text')
        ->badgeColor('gray');

        $tabs[] = Tab::make(__('Not Active'))
        ->badge(Article::where('is_active', false)->whereIn('article_type_id', [ArticleType::News->value, ArticleType::Gallery->value, ArticleType::Page->value])->count())
        ->modifyQueryUsing(function ($query) {
            return $query->where('is_active', false);
        })
        ->icon('heroicon-o-document-text')
        ->badgeColor('danger');

        return $tabs;
    }
}
