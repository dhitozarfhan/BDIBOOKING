<?php

namespace App\Filament\Pages;

use App\Models\Segment;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Kalnoy\Nestedset\QueryBuilder;
use Studio15\FilamentTree\Components\TreePage;

class SegmentPage extends TreePage
{
    
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    public static function getModel(): string|QueryBuilder
    {
        // Menggunakan scoped query untuk memastikan semua node berada dalam scope yang sama
        // Ini akan memungkinkan pembuatan/penyimpanan child node secara otomatis
        return Segment::scoped([]);
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
    
    public function getTitle(): string
    {
        return __('Segments');
    }

    public static function getNavigationLabel(): string
    {
        return __('Segments');
    }
    
    public static function getNavigationGroup(): ?string
    {
        return __('Archives');
    }
    
    public static function getNavigationSort(): ?int
    {
        return 21;
    }
}
