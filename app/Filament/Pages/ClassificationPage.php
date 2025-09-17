<?php

namespace App\Filament\Pages;

use App\Models\Classification;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Kalnoy\Nestedset\QueryBuilder;
use Studio15\FilamentTree\Components\TreePage;


class ClassificationPage extends TreePage
{

    protected static ?string $navigationIcon = 'heroicon-o-folder-open';

    public static function getModel(): string|QueryBuilder
    {
        // Menggunakan scoped query untuk memastikan semua node berada dalam scope yang sama
        // Ini akan memungkinkan pembuatan/penyimpanan child node secara otomatis
        return Classification::scoped([]);
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
        return static::getCreateForm();
    }

    public static function getInfolistColumns(): array
    {
        return [
            //
        ];
    }
    
    public function getTitle(): string
    {
        return __('Klasifikasi');
    }

    public static function getNavigationLabel(): string
    {
        return __('Klasifikasi');
    }
    
    public static function getNavigationGroup(): ?string
    {
        return ('Kearsipan');
    }
    
    public static function getNavigationSort(): ?int
    {
        return 10;
    }
}