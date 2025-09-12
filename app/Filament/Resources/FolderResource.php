<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FolderResource\Pages;
use App\Models\Folder;
use App\Models\Classification;
use App\Models\Location;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FolderResource extends Resource
{
    protected static ?string $model = Folder::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    public static function getNavigationGroup(): ?string
    {
        return __('Kearsipan');
    }

    public static function getNavigationSort(): ?int
    {
        return 11;
    }

    public static function getModelLabel(): string
    {
        return __('Folder');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Folders');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(1)
                    ->schema([
                        
                        Forms\Components\Select::make('classification_id')
                            ->label(__('Classification'))
                            ->relationship('classification', 'code')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('location_id')
                            ->label(__('Location'))
                            ->relationship('location', 'code')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Textarea::make('description')
                            ->label(__('Description'))
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('classification.code')
                    ->label(__('Classification'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('location.code')
                    ->label(__('Location'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('description')
                    ->label(__('Description'))
                    ->searchable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('documents_count')
                    ->label(__('Documents'))
                    ->counts('documents')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('classification_id')
                    ->label(__('Classification'))
                    ->relationship('classification', 'code')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('location_id')
                    ->label(__('Location'))
                    ->relationship('location', 'code')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            'index' => Pages\ListFolders::route('/'),
            'create' => Pages\CreateFolder::route('/create'),
            'edit' => Pages\EditFolder::route('/{record}/edit'),
        ];
    }
}