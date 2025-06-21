<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{

    use CreateRecord\Concerns\Translatable;

    protected static string $resource = CategoryResource::class;
 
    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make()
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // dd($data);
        // $data['sort'] = CategoryResource::getModel()::where('category_type_id', $data['category_type_id'])->max('sort') + 1;
        $data['is_root'] = false; // Default to root category
        $data['is_active'] = true; // Default to active category

        return $data;
    }
    
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index').'?activeTab='.$this->record->categoryType->slug;
    }
}
