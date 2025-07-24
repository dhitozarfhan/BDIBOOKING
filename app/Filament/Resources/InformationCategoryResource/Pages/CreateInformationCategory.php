<?php

namespace App\Filament\Resources\InformationCategoryResource\Pages;

use App\Enums\CategoryType;
use App\Filament\Resources\InformationCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateInformationCategory extends CreateRecord
{

    use CreateRecord\Concerns\Translatable;

    protected static string $resource = InformationCategoryResource::class;
    protected static bool $canCreateAnother = false;
 
    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make()
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['category_type_id'] = CategoryType::Information->value;
        $data['is_root'] = false; // Default to root category
        $data['is_active'] = true; // Default to active category

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
