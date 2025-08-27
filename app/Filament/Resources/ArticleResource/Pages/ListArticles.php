<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Enums\ArticleType;
use App\Enums\PermissionType;
use App\Enums\RoleDefault;
use App\Filament\Resources\ArticleResource;
use App\Models\Article;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

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
        $typeIds = [];
        if(Auth::user()->hasPermissionTo(PermissionType::News->value)) {
            $typeIds[] = ArticleType::News->value;
        }
        if(Auth::user()->hasPermissionTo(PermissionType::Gallery->value)) {
            $typeIds[] = ArticleType::Gallery->value;
        }
        if(Auth::user()->hasPermissionTo(PermissionType::Page->value)) {
            $typeIds[] = ArticleType::Page->value;
        }

        $tabs = [];
        $tabs[] = Tab::make(__('All Articles'))
        ->badge(
            Article::whereIn('article_type_id', $typeIds)
            ->when(!Auth::user()->hasRole(RoleDefault::PublicationAdministrator->value), fn ($query) => $query->where('author_id', Auth::user()->id))->count()
        )
        ->modifyQueryUsing(function ($query) use ($typeIds) {
            return $query->whereIn('article_type_id', $typeIds)->when(!Auth::user()->hasRole(RoleDefault::PublicationAdministrator->value), fn ($query) => $query->where('author_id', Auth::user()->id));
        })
        ->icon('heroicon-o-document-text');

        if(Auth::user()->hasPermissionTo(PermissionType::News->value)) {
            $tabs[] = Tab::make(__('News'))
            ->badge(
                Article::where('article_type_id', ArticleType::News->value)
                ->when(!Auth::user()->hasRole(RoleDefault::PublicationAdministrator->value), fn ($query) => $query->where('author_id', Auth::user()->id))->count()
            )
            ->modifyQueryUsing(function ($query) {
                return $query->where('article_type_id', ArticleType::News->value)->when(!Auth::user()->hasRole(RoleDefault::PublicationAdministrator->value), fn ($query) => $query->where('author_id', Auth::user()->id));
            })
            ->icon('heroicon-o-newspaper')
            ->badgeColor('info');
        }

        if(Auth::user()->hasPermissionTo(PermissionType::Gallery->value)) {
            $tabs[] = Tab::make(__('Gallery'))
            ->badge(
                Article::where('article_type_id', ArticleType::Gallery->value)
                ->when(!Auth::user()->hasRole(RoleDefault::PublicationAdministrator->value), fn ($query) => $query->where('author_id', Auth::user()->id))->count()
            )
            ->modifyQueryUsing(function ($query) {
                return $query->where('article_type_id', ArticleType::Gallery->value)->when(!Auth::user()->hasRole(RoleDefault::PublicationAdministrator->value), fn ($query) => $query->where('author_id', Auth::user()->id));
            })
            ->icon('heroicon-o-camera')
            ->badgeColor('success');
        }

        if(Auth::user()->hasPermissionTo(PermissionType::Page->value)) {
            $tabs[] = Tab::make(__('Page'))
            ->badge(
                Article::where('article_type_id', ArticleType::Page->value)
                ->when(!Auth::user()->hasRole(RoleDefault::PublicationAdministrator->value), fn ($query) => $query->where('author_id', Auth::user()->id))->count()
            )
            ->modifyQueryUsing(function ($query) {
                return $query->where('article_type_id', ArticleType::Page->value)->when(!Auth::user()->hasRole(RoleDefault::PublicationAdministrator->value), fn ($query) => $query->where('author_id', Auth::user()->id));
            })
            ->icon('heroicon-o-document-text')
            ->badgeColor('gray');
        }

        $tabs[] = Tab::make(__('Not Active'))
        ->badge(
            Article::where('is_active', false)->whereIn('article_type_id', $typeIds)
            ->when(!Auth::user()->hasRole(RoleDefault::PublicationAdministrator->value), fn ($query) => $query->where('author_id', Auth::user()->id))->count()
        )
        ->modifyQueryUsing(function ($query) use ($typeIds) {
            return $query->whereIn('article_type_id', $typeIds)->where('is_active', false)->when(!Auth::user()->hasRole(RoleDefault::PublicationAdministrator->value), fn ($query) => $query->where('author_id', Auth::user()->id));
        })
        ->icon('heroicon-o-document-text')
        ->badgeColor('danger');

        return $tabs;
    }
}
