<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Kalnoy\Nestedset\QueryBuilder;
use Studio15\FilamentTree\Components\TreePage;

class SegmentPage extends TreePage
{
    public static function getModel(): string|QueryBuilder
    {
        return \App\Models\Segment::class;
    }

    public static function getCreateForm(): array
    {
        return [
            TextInput::make('code')
                ->label('Kode Segment')
                ->maxLength(255)
                ->required(),


            TextInput::make('name')
                ->label('Nama Segment')
                ->maxLength(255)
                ->required()
        ];
    }

    public static function getEditForm(): array
    {
        return static::getCreateForm();
    }

    public static function getInfolistColumns(): array
    {
        return [
            //
        ];
    }
}
