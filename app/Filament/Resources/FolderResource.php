<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FolderResource\Pages;
use App\Filament\Resources\FolderResource\RelationManagers\DocumentsRelationManager;
use App\Filament\Forms\Components\TreeSelect;
use App\Filament\Filters\TreeSelectFilter;
use App\Filament\Tables\Columns\HierarchyColumn;
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
                        Forms\Components\TextInput::make('name')
                            ->label(__('Name'))
                            ->required()
                            ->maxLength(65535),
                        Forms\Components\Select::make('type')
                            ->label(__('Type'))
                            ->options([
                                'bundle' => __('Bundle'),
                                'lembar' => __('Lembar'),
                            ])
                            ->required(),
                        TreeSelect::make('classification_id')
                            ->label(__('Classification'))
                            ->required()
                            ->depth(2)
                            ->restrictDepthSelection(),

                        TreeSelect::make('location_id')
                            ->label(__('Location'))
                            ->required()
                            ->depth(2)
                            ->restrictDepthSelection(),
                        
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                HierarchyColumn::make('classification.code')
                    ->label(__('Classification'))
                    ->sortable()
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('classification', function ($query) use ($search) {
                            $query->where('code', 'ilike', "%{$search}%")
                                ->orWhere('name', 'ilike', "%{$search}%")
                                ->orWhereHas('ancestors', function ($query) use ($search) {
                                    $query->where('code', 'ilike', "%{$search}%")
                                        ->orWhere('name', 'ilike', "%{$search}%");
                                });
                        });
                    }),

                HierarchyColumn::make('location.code')
                    ->label(__('Location'))
                    ->sortable()
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('location', function ($query) use ($search) {
                            $query->where('code', 'ilike', "%{$search}%")
                                ->orWhere('name', 'ilike', "%{$search}%")
                                ->orWhereHas('ancestors', function ($query) use ($search) {
                                    $query->where('code', 'ilike', "%{$search}%")
                                        ->orWhere('name', 'ilike', "%{$search}%");
                                });
                        });
                    }),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('type')
                    ->label(__('Type'))
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'bundle' => __('Bundle'),
                        'lembar' => __('Lembar'),
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('documents_count')
                    ->label(__('Documents'))
                    ->counts('documents')
                    ->sortable(),
            ])
            ->filters([
                TreeSelectFilter::make('classification_id')
                    ->label(__('Classification')),

                TreeSelectFilter::make('location_id')
                    ->label(__('Location')),

                Tables\Filters\SelectFilter::make('type')
                    ->label(__('Type'))
                    ->options([
                        'bundle' => __('Bundle'),
                        'lembar' => __('Lembar'),
                    ]),
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
            DocumentsRelationManager::class,
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