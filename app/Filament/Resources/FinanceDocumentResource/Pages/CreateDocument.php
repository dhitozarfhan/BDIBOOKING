<?php

namespace App\Filament\Resources\FinanceDocumentResource\Pages;

use App\Filament\Resources\FinanceDocumentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDocument extends CreateRecord
{
    protected static string $resource = FinanceDocumentResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}