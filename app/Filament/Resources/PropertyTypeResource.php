<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PropertyTypeResource\Pages;
use App\Filament\Resources\PropertyTypeResource\RelationManagers;
use App\Models\PropertyType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PropertyTypeResource extends Resource
{
    protected static ?string $model = PropertyType::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationGroup = 'PNBP';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Property Type Name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                ->label('Description')
                ->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->searchable()
                ->sortable(),


                Tables\Columns\TextColumn::make('description')
                ->limit(50),

                Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable(),
            ])
            ->filters([
               
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListPropertyTypes::route('/'),
            'create' => Pages\CreatePropertyType::route('/create'),
            'edit' => Pages\EditPropertyType::route('/{record}/edit'),
        ];
    }
}
