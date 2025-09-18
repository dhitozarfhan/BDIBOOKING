<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FinanceDocumentResource\Pages;
use App\Filament\Forms\Components\TreeSelect;
use App\Filament\Forms\Components\SegmentTreeSelect;
use App\Models\Account;
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

class FinanceDocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function getNavigationGroup(): ?string
    {
        return __('Archives');
    }

    public static function getNavigationSort(): ?int
    {
        return 12;
    }

    public static function getModelLabel(): string
    {
        return __('Finance Document');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Finance Documents');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make([
                    'default' => 1,
                    'md' => 2,
                ])
                ->schema([
                    Forms\Components\Section::make()
                        ->columnSpan(1)
                        ->schema([

                            FileUpload::make('file_path')
                                ->label(__('File'))
                                ->required()
                                ->directory('documents')
                                ->maxSize(10240) // 10 MB
                                ->preserveFilenames()
                                ->visibility('private')
                                ->helperText(__('Maximum file size: 10 MB.')),

                            Forms\Components\TextInput::make('name')
                                ->label(__('Name'))
                                ->required()
                                ->maxLength(255),

                            Forms\Components\Textarea::make('description')
                                ->label(__('Deskripsi'))
                                ->columnSpanFull(),

                            Forms\Components\DatePicker::make('published_at')
                                ->label(__('Tanggal Terbit'))
                                ->native(false)
                                ->displayFormat('d F Y'),
                            
                            Forms\Components\Toggle::make('condition')
                                ->label(__('Kondisi (Musnah/Tidak Musnah)'))
                                ->default(true),

                            Forms\Components\Toggle::make('access')
                                ->label(__('Akses (Publik/Rahasia)'))
                                ->default(false),
                        ]),

                    Forms\Components\Section::make()
                        ->columnSpan(1)
                        ->schema([
                            Forms\Components\Select::make('accounts')
                                ->label(__('Akun'))
                                ->multiple()
                                ->relationship('accounts', 'code')
                                ->getOptionLabelFromRecordUsing(fn (Account $record) => "{$record->name} - {$record->code}")
                                ->preload(),
                                
                            TreeSelect::make('folder_id')
                                ->label(__('Folder'))
                                ->required(),

                            SegmentTreeSelect::make('segment_id')
                                ->label(__('Segment'))
                                ->required()
                                ->depth(5)
                                ->restrictDepthSelection(),

                            Forms\Components\TextInput::make('active_retention')
                                ->label(__('Retention Aktif'))
                                ->maxLength(255),

                            Forms\Components\TextInput::make('inactive_retention')
                                ->label(__('Retention Tidak Aktif'))
                                ->maxLength(255),
                                
                            Forms\Components\TextInput::make('information')
                                ->label(__('Keterangan'))
                                ->maxLength(255),
                            

                        ]),
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

                Tables\Columns\TextColumn::make('accounts.code')
                    ->label(__('Accounts'))
                    ->badge()
                    ->separator(',')
                    ->searchable()
                    ->sortable(),

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

                Tables\Columns\TextColumn::make('active_retention')
                    ->label(__('Active Retention'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('inactive_retention')
                    ->label(__('Inactive Retention'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('information')
                    ->label(__('Information'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('access')
                    ->label(__('Access'))
                    ->boolean()
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