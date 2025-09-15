<?php

namespace App\Filament\Resources\AccountDocumentResource\Pages;

use App\Filament\Resources\AccountDocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAccountDocuments extends ListRecords
{
    protected static string $resource = AccountDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}