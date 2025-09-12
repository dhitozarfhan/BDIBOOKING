<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Kalnoy\Nestedset\QueryBuilder;
use Studio15\FilamentTree\Components\TreePage;

class ClassificationPage extends TreePage
{
    public static function getModel(): string|QueryBuilder
    {
        // TODO: Set tree model
        return \App\Models\Classification::class;
    }

    public static function getCreateForm(): array
    {
        return [
            TextInput::make('code')
                ->label('Kode Klasifikasi')
                ->maxLength(255)
                ->required(),


            TextInput::make('name')
                ->label('Nama Klasifikasi')
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
