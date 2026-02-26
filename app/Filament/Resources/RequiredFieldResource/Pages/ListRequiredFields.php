<?php

namespace App\Filament\Resources\RequiredFieldResource\Pages;

use App\Filament\Resources\RequiredFieldResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRequiredFields extends ListRecords
{
    protected static string $resource = RequiredFieldResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
