<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PropertyResource\Pages;
use App\Filament\Resources\PropertyResource\RelationManagers;
use App\Models\Property;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    protected static ?string $navigationGroup = 'PNBP';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('category')
                    ->options([
                        'kamar_inap' => 'Kamar Inap',
                        'ruang_rapat' => 'Ruang Rapat',
                        'kelas' => 'Kelas',
                        'lainnya' => 'Lainnya',
                    ])
                    ->required()
                    ->label('Kategori'),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('capacity')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->default(1),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->prefix('Rp')
                    ->default(0.00),
                Forms\Components\Select::make('status')
                    ->required()
                    ->label('Status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ])
                    ->default('active'),
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->multiple()
                    ->maxFiles(5)
                    ->reorderable()
                    ->directory('properties')
                    ->label('Gambar Properti (Maksimal 5)')
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('created_at')
                    ->disabled()
                    ->dehydrated(false)
                    ->default(now()),
                Forms\Components\DateTimePicker::make('updated_at')
                    ->disabled()
                    ->dehydrated(false)
                    ->default(now()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->circular()
                    ->stacked()
                    ->label('Gambar'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->colors([
                        'primary' => 'kamar_inap',
                        'success' => 'ruang_rapat',
                        'warning' => 'kelas',
                        'gray' => 'lainnya',
                    ]),
                Tables\Columns\TextColumn::make('capacity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\IconColumn::make('status')
                    ->icon(fn ($state) => $state === 'active' ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                    ->color(fn ($state) => $state === 'active' ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'kamar_inap' => 'Kamar Inap',
                        'ruang_rapat' => 'Ruang Rapat',
                        'kelas' => 'Kelas',
                        'lainnya' => 'Lainnya',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ]),
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
            'index' => Pages\ListProperties::route('/'),
            'create' => Pages\CreateProperty::route('/create'),
            'edit' => Pages\EditProperty::route('/{record}/edit'),
        ];
    }
}
