<?php

namespace App\Filament\Resources\AccountDocumentResource\Pages;

use App\Filament\Resources\AccountDocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAccountDocument extends EditRecord
{
    protected static string $resource = AccountDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}