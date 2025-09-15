<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentResource\Pages;
use App\Filament\Forms\Components\TreeSelect;
use App\Filament\Forms\Components\SegmentTreeSelect;
use App\Models\Document;
use App\Models\Folder;
use App\Models\Segment;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
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
                        TreeSelect::make('folder_id')
                            ->label(__('Folder'))
                            ->required(),

                        SegmentTreeSelect::make('segment_id')
                            ->label(__('Segment'))
                            ->required(),

                        Forms\Components\TextInput::make('name')
                            ->label(__('Name'))
                            ->required()
                            ->maxLength(255),

                        FileUpload::make('file_path')
                            ->label(__('File Path'))
                            ->required()
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(10240), // 10MB

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
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable(),

                \App\Filament\Tables\Columns\FolderHierarchyColumn::make('folder.id')
                    ->label(__('Folder'))
                    ->sortable()
                    ->searchable(),

                \App\Filament\Tables\Columns\SegmentHierarchyColumn::make('segment.code')
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
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('condition')
                    ->label(__('Condition'))
                    ->boolean()
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                \App\Filament\Filters\FolderTreeSelectFilter::make('folder_id')
                    ->label(__('Folder')),

                \App\Filament\Filters\SegmentTreeSelectFilter::make('segment_id')
                    ->label(__('Segment')),

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