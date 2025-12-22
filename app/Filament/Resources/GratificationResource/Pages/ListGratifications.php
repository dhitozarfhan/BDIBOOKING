<?php

namespace App\Filament\Resources\GratificationResource\Pages;

use App\Filament\Resources\GratificationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGratifications extends ListRecords
{
    protected static string $resource = GratificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}