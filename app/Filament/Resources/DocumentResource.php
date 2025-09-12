<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentResource\Pages;
use App\Models\Document;
use App\Models\Folder;
use App\Models\Segment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function getNavigationGroup(): ?string
    {
        return __('Kearsipan');
    }

    public static function getNavigationSort(): ?int
    {
        return 12;
    }

    public static function getModelLabel(): string
    {
        return __('Document');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Documents');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(1)
                    ->schema([
                        Forms\Components\Select::make('folder_id')
                            ->label(__('Folder'))
                            ->relationship('folder', 'id')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('segment_id')
                            ->label(__('Segment'))
                            ->relationship('segment', 'code')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\TextInput::make('file_path')
                            ->label(__('File Path'))
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->label(__('Description'))
                            ->columnSpanFull(),

                        Forms\Components\DatePicker::make('published_at')
                            ->label(__('Published At'))
                            ->native(false)
                            ->displayFormat('d F Y'),

                        Forms\Components\TextInput::make('active_retention')
                            ->label(__('Active Retention'))
                            ->maxLength(255),

                        Forms\Components\TextInput::make('inactive_retention')
                            ->label(__('Inactive Retention'))
                            ->maxLength(255),

                        Forms\Components\Toggle::make('condition')
                            ->label(__('Condition'))
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('folder.id')
                    ->label(__('Folder'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('segment.code')
                    ->label(__('Segment'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('file_path')
                    ->label(__('File Path'))
                    ->searchable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('description')
                    ->label(__('Description'))
                    ->searchable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('published_at')
                    ->label(__('Published At'))
                    ->date()
                    ->sortable(),

                Tables\Columns\IconColumn::make('condition')
                    ->label(__('Condition'))
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('folder_id')
                    ->label(__('Folder'))
                    ->relationship('folder', 'id')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('segment_id')
                    ->label(__('Segment'))
                    ->relationship('segment', 'code')
                    ->searchable()
                    ->preload(),

                Tables\Filters\Filter::make('condition')
                    ->label(__('Condition'))
                    ->toggle()
                    ->query(fn ($query) => $query->where('condition', true)),
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
            'index' => Pages\ListDocuments::route('/'),
            'create' => Pages\CreateDocument::route('/create'),
            'edit' => Pages\EditDocument::route('/{record}/edit'),
        ];
    }
}