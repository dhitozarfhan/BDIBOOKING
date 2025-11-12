<?php

namespace App\Filament\Resources\WbsResource\Pages;

use App\Filament\Resources\WbsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWbs extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = WbsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}