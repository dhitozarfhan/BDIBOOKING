<?php

namespace App\Filament\Resources\UserAPKResource\Pages;

use App\Filament\Resources\UserAPKResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserAPKS extends ListRecords
{
    protected static string $resource = UserAPKResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
