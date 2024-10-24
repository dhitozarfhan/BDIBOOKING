<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CoreResource\Pages;
use App\Filament\Resources\CoreResource\RelationManagers;
use App\Models\Core;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CoreResource extends Resource
{
    protected static ?string $model = Core::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('en_name'),
                TextInput::make('id_name'),
                TextInput::make('slug')->required(),
                Select::make('type')->options(['information' => 'Information']),
                TextInput::make('icon'),
                TextInput::make('sort')->numeric()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_name')->sortable(),
                TextColumn::make('en_name')->sortable(),
                TextColumn::make('type')->sortable(),
                TextColumn::make('icon')->sortable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCores::route('/'),
            'create' => Pages\CreateCore::route('/create'),
            'edit' => Pages\EditCore::route('/{record}/edit'),
        ];
    }
}
