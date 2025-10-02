<?php

namespace App\Filament\Resources\FolderResource\RelationManagers;

use App\Filament\Forms\Components\SegmentTreeSelect;
use App\Filament\Forms\Components\TreeSelect;
use App\Models\Account;
use App\Models\Document;
use Asmit\FilamentUpload\Enums\PdfViewFit;
use Asmit\FilamentUpload\Forms\Components\AdvancedFileUpload;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

    public function form(Form $form): Form
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
                            SegmentTreeSelect::make('segment_id')
                                ->label(__('Segment'))
                                ->required()
                                ->depth(5)
                                ->restrictDepthSelection(),

                            Forms\Components\Select::make('accounts')
                                ->label(__('Accounts'))
                                ->multiple()
                                ->relationship('accounts', 'code')
                                ->getOptionLabelFromRecordUsing(fn (Account $record) => "{$record->name} - {$record->code}")
                                ->preload(),

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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('segment')
                    ->label(__('Segment'))
                    ->formatStateUsing(function ($state, Document $record) {
                        if ($record->segment) {
                            // Get ancestors ordered from root to parent
                            $ancestors = $record->segment->ancestors()->defaultOrder()->get();
                            
                            // Build the hierarchical path
                            $path = [];
                            
                            // Add ancestors codes
                            foreach ($ancestors as $ancestor) {
                                $path[] = $ancestor->code;
                            }
                            
                            // Add the current segment's code
                            $path[] = $record->segment->code;
                            
                            // Join with dots and display
                            return implode('.', $path);
                        }
                        
                        return '';
                    })
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('file_path')
                    ->label(__('File Path'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('published_at')
                    ->label(__('Published At'))
                    ->date(),

                Tables\Columns\TextColumn::make('active_retention')
                    ->label(__('Active Retention')),

                Tables\Columns\TextColumn::make('inactive_retention')
                    ->label(__('Inactive Retention')),
                Tables\Columns\TextColumn::make('information')
                    ->label(__('Information'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('access')
                    ->label(__('Access'))
                    ->boolean(),

                Tables\Columns\IconColumn::make('condition')
                    ->label(__('Condition'))
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('Updated At'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}