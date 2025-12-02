<?php

namespace App\Filament\Resources\InformationRequestResource\Pages;

use App\Filament\Resources\InformationRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewInformationRequest extends ViewRecord
{
    protected static string $resource = InformationRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
