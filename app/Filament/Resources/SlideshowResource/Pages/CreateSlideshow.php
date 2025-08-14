<?php

namespace App\Filament\Resources\SlideshowResource\Pages;

use App\Filament\Resources\SlideshowResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSlideshow extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = SlideshowResource::class;
    protected static bool $canCreateAnother = false;

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
