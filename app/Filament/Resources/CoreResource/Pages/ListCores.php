<?php

namespace App\Filament\Resources\CoreResource\Pages;

use App\Filament\Resources\CoreResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCores extends ListRecords
{
    protected static string $resource = CoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
