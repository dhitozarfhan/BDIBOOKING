<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FinanceDocumentResource\Pages;
use App\Filament\Forms\Components\TreeSelect;
use App\Filament\Forms\Components\SegmentTreeSelect;
use App\Models\Account;
use App\Models\Document;
use App\Models\Folder;
use App\Models\Segment;
use Asmit\FilamentUpload\Enums\PdfViewFit;
use Asmit\FilamentUpload\Forms\Components\AdvancedFileUpload;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class FinanceDocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    
    protected static ?int $navigationSort = 24;

    public static function getNavigationGroup(): ?string
    {
        return __('Archives');
    }

    public static function getModelLabel(): string
    {
        return __('Note / Upload');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Note / Upload');
    }

    public static function canAccess(): bool
    {
        return Auth::user()->hasPermissionTo(\App\Enums\PermissionType::Finance->value);
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
                            AdvancedFileUpload::make('file_path')
                                ->label(__('Upload PDF'))
                                ->acceptedFileTypes(['application/pdf'])
                                ->required()
                                ->maxSize(51200) // 50 MB
                                ->directory('documents')
                                ->preserveFilenames()
                                ->visibility('private')
                                ->helperText(__('Maximum file size: 50 MB.'))

                                ->pdfPreviewHeight(400) // Customize preview height
                                ->pdfDisplayPage(1) // Set default page
                                ->pdfToolbar(true) // Enable toolbar
                                ->pdfZoomLevel(100) // Set zoom level
                                ->pdfFitType(PdfViewFit::FIT) // Set fit type
                                ->pdfNavPanes(true),

                            Forms\Components\TextInput::make('name')
                                ->label(__('Name'))
                                ->required()
                                ->maxLength(255),

                            Forms\Components\Textarea::make('description')
                                ->label(__('Description'))
                                ->columnSpanFull(),

                            Forms\Components\DatePicker::make('published_at')
                                ->label(__('Published Date'))
                                ->native(false)
                                ->displayFormat('d F Y'),
                            
                            Forms\Components\Toggle::make('condition')
                                ->label(__('Condition (Destroyed/Not Destroyed)'))
                                ->default(true),

                            Forms\Components\Toggle::make('access')
                                ->label(__('Access (Public/Secret)'))
                                ->default(false),
                        ]),

                    Forms\Components\Section::make()
                        ->columnSpan(1)
                        ->schema([
                            Forms\Components\Select::make('accounts')
                                ->label(__('Accounts'))
                                ->multiple()
                                ->distinct()
                                ->relationship('accounts', 'code')
                                ->getOptionLabelFromRecordUsing(fn (Account $record) => "{$record->name} - {$record->code}")
                                ->preload()
                                ->required()
                                ->createOptionForm([
                                    Forms\Components\TextInput::make('code')->label(__('Account Code'))
                                        ->numeric()
                                        ->required()
                                        ->unique()
                                        ->length(6),
                                    Forms\Components\TextInput::make('name')->label(__('Account Name'))
                                        ->required()
                                ]),
                                
                            TreeSelect::make('folder_id')
                                ->label(__('Folder'))
                                ->required(),

                            SegmentTreeSelect::make('segment_id')
                                ->label(__('Segment'))
                                ->required()
                                ->depth(5)
                                ->restrictDepthSelection(),

                            Forms\Components\TextInput::make('active_retention')
                                ->label(__('Active Retention'))
                                ->maxLength(255),

                            Forms\Components\TextInput::make('inactive_retention')
                                ->label(__('Inactive Retention'))
                                ->maxLength(255),
                                
                            Forms\Components\TextInput::make('information')
                                ->label(__('Information'))
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