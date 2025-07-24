<?php

namespace App\Filament\Resources\InformationCategoryResource\Pages;

use App\Filament\Resources\InformationCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInformationCategory extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = InformationCategoryResource::class;
 
    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make()
        ];
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}