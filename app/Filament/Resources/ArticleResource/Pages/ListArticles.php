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
        ->badge(Article::count())
        ->modifyQueryUsing(function ($query) {
            return $query->where('is_active', true);
        })
        ->icon('heroicon-o-document-text');

        $tabs[] = Tab::make(__('News'))
        ->badge(Article::where('article_type_id', ArticleType::News->value)->count())
        ->modifyQueryUsing(function ($query) {
            return $query->where('article_type_id', ArticleType::News->value);
        })
        ->icon('heroicon-o-newspaper')
        ->badgeColor('primary');

        $tabs[] = Tab::make(__('Blog'))
        ->badge(Article::where('article_type_id', ArticleType::Blog->value)->count())
        ->modifyQueryUsing(function ($query) {
            return $query->where('article_type_id', ArticleType::Blog->value);
        })
        ->icon('heroicon-o-document-text')
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
        ->badgeColor('warning');

        $tabs[] = Tab::make(__('Not Active'))
        ->badge(Article::where('is_active', false)->count())
        ->modifyQueryUsing(function ($query) {
            return $query->where('is_active', false);
        })
        ->icon('heroicon-o-document-text')
        ->badgeColor('danger');

        return $tabs;
    }
}
