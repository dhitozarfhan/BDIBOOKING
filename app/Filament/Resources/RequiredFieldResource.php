<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RequiredFieldResource\Pages;
use App\Models\RequiredField;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RequiredFieldResource extends Resource
{
    protected static ?string $model = RequiredField::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'PNBP';

    protected static ?string $navigationLabel = 'Persyaratan';

    protected static ?string $modelLabel = 'Persyaratan';

    protected static ?string $pluralModelLabel = 'Persyaratan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Field')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_file')
                    ->label('Berupa File?')
                    ->helperText('Aktifkan jika field ini memerlukan upload file')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort')
            ->defaultSort('sort')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Field')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_file')
                    ->label('File?')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListRequiredFields::route('/'),
            'create' => Pages\CreateRequiredField::route('/create'),
            'edit' => Pages\EditRequiredField::route('/{record}/edit'),
        ];
    }
}
