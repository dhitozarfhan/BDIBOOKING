<?php

namespace App\Filament\Resources\WbsResource\Pages;

use App\Filament\Resources\WbsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWbs extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = WbsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}