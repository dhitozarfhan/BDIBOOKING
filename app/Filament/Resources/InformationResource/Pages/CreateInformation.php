<?php

namespace App\Filament\Resources\InformationResource\Pages;

use App\Enums\ArticleType;
use App\Filament\Resources\InformationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateInformation extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = InformationResource::class;
    protected static bool $canCreateAnother = false;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make()
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['article_type_id'] = ArticleType::Information->value;
        
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}