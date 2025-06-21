<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Enums\CategoryType;
use App\Filament\Resources\CategoryResource;
use App\Models\Category;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListCategories extends ListRecords
{
    use ListRecords\Concerns\Translatable;
    
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make()
        ];
    }

    public function getTabs(): array
    {
        return [
            'news'      => Tab::make(__('News'))->badge(Category::where('category_type_id', CategoryType::News->value)->count())->modifyQueryUsing(fn (Builder $query) => $query->where('category_type_id', CategoryType::News->value)),
            'blog'      => Tab::make(__('Blog'))->badge(Category::where('category_type_id', CategoryType::Blog->value)->count())->modifyQueryUsing(fn (Builder $query) => $query->where('category_type_id', CategoryType::Blog->value)),
            // 'gallery'   => Tab::make(__('Gallery'))->badge(Category::where('category_type_id', CategoryType::Gallery->value)->count())->modifyQueryUsing(fn (Builder $query) => $query->where('category_type_id', CategoryType::Gallery->value)),
            // 'event'     => Tab::make(__('Event'))->badge(Category::where('category_type_id', CategoryType::Event->value)->count())->modifyQueryUsing(fn (Builder $query) => $query->where('category_type_id', CategoryType::Event->value)),
            // 'information' => Tab::make(__('Information'))->badge(Category::where('category_type_id', CategoryType::Information->value)->count())->modifyQueryUsing(fn (Builder $query) => $query->where('category_type_id', CategoryType::Information->value)),
            // 'question'  => Tab::make(__('Question'))->badge(Category::where('category_type_id', CategoryType::Question->value)->count())->modifyQueryUsing(fn (Builder $query) => $query->where('category_type_id', CategoryType::Question->value)),
            // 'request'   => Tab::make(__('Request'))->badge(Category::where('category_type_id', CategoryType::Request->value)->count())->modifyQueryUsing(fn (Builder $query) => $query->where('category_type_id', CategoryType::Request->value)),
            // 'wbs'       => Tab::make(__('WBS'))->badge(Category::where('category_type_id', CategoryType::Wbs->value)->count())->modifyQueryUsing(fn (Builder $query) => $query->where('category_type_id', CategoryType::Wbs->value)),
            // 'service'   => Tab::make(__('Service'))->badge(Category::where('category_type_id', CategoryType::Service->value)->count())->modifyQueryUsing(fn (Builder $query) => $query->where('category_type_id', CategoryType::Service->value)),
        ];
    }
}
