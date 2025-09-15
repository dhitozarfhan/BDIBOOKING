<?php

namespace App\Filament\Resources\AccountDocumentResource\Pages;

use App\Filament\Resources\AccountDocumentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAccountDocument extends CreateRecord
{
    protected static string $resource = AccountDocumentResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}