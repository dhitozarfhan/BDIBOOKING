<?php

namespace App\Filament\Resources\InformationCategoryResource\Pages;

use App\Enums\CategoryType;
use App\Filament\Resources\InformationCategoryResource;
use App\Models\Category;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListInformationCategories extends ListRecords
{
    use ListRecords\Concerns\Translatable;
    
    protected static string $resource = InformationCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make()
        ];
    }

    public function getTabs(): array
    {
        $tabs = [];

        $parents = Category::where('category_type_id', CategoryType::InformationType->value)->get();

        foreach($parents as $parent){
            $tabs[] = Tab::make($parent->name)
            ->badge(Category::where('parent_id', $parent->id)->where('category_type_id', CategoryType::Information->value)->count())
            ->modifyQueryUsing(function ($query) use ($parent) {
                return $query->where('parent_id', $parent->id)
                ->where('category_type_id', CategoryType::Information->value);
            });
        }

        return $tabs;
    }


}
