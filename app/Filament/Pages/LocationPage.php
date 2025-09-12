<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Kalnoy\Nestedset\QueryBuilder;
use Studio15\FilamentTree\Components\TreePage;

class LocationPage extends TreePage
{
    public static function getModel(): string|QueryBuilder
    {
        return \App\Models\Location::class;
    }

    public static function getCreateForm(): array
    {
        return [
            TextInput::make('code')
                ->label('Kode Lokasi')
                ->maxLength(255)
                ->required(),


            TextInput::make('name')
                ->label('Nama Lokasi')
                ->maxLength(255)
                ->required()
        ];
    }

    public static function getEditForm(): array
    {
        return [
            //
        ];
    }

    public static function getInfolistColumns(): array
    {
        return [
            //
        ];
    }
}
