<?php

namespace App\Filament\Pages;

use App\Models\Location;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Facades\Auth;
use Kalnoy\Nestedset\QueryBuilder;
use Studio15\FilamentTree\Components\TreePage;

class LocationPage extends TreePage
{
    
    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?int $navigationSort = 21;

    public static function canAccess(): bool
    {
        return Auth::user()->hasPermissionTo(\App\Enums\PermissionType::Archives->value);
    }

    public static function getModel(): string|QueryBuilder
    {
        // Menggunakan scoped query untuk memastikan semua node berada dalam scope yang sama
        // Ini akan memungkinkan pembuatan/penyimpanan child node secara otomatis
        return Location::scoped([]);
    }

    public static function getCreateForm(): array
    {
        return [
            TextInput::make('code')
                ->label(__('Code'))
                ->maxLength(255)
                ->required(),

            TextInput::make('name')
                ->label(__('Name'))
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
        return __('Location');
    }

    public static function getNavigationLabel(): string
    {
        return __('Location');
    }
    
    public static function getNavigationGroup(): ?string
    {
        return __('Archives');
    }
}