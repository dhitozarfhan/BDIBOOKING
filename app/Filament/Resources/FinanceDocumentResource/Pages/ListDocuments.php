<?php

namespace App\Filament\Resources\FinanceDocumentResource\Pages;

use App\Filament\Resources\FinanceDocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDocuments extends ListRecords
{
    protected static string $resource = FinanceDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}