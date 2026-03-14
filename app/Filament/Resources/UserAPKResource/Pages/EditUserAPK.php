<?php

namespace App\Filament\Resources\UserAPKResource\Pages;

use App\Filament\Resources\UserAPKResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserAPK extends EditRecord
{
    protected static string $resource = UserAPKResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
