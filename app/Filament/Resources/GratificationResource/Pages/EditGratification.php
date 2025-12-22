<?php

namespace App\Filament\Resources\GratificationResource\Pages;

use App\Filament\Resources\GratificationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGratification extends EditRecord
{
    protected static string $resource = GratificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}