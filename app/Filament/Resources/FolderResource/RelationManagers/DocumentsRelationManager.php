<?php

namespace App\Filament\Resources\FolderResource\RelationManagers;

use App\Models\Document;
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
                Forms\Components\Section::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('Name'))
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('segment_id')
                            ->label(__('Segment'))
                            ->relationship('segment', 'name')
                            ->searchable()
                            ->preload(),

                        Forms\Components\FileUpload::make('file_path')
                            ->label(__('File Path'))
                            ->required()
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(10240), // 10MB

                        Forms\Components\DatePicker::make('published_at')
                            ->label(__('Published At')),

                        Forms\Components\TextInput::make('active_retention')
                            ->label(__('Active Retention')),

                        Forms\Components\TextInput::make('inactive_retention')
                            ->label(__('Inactive Retention')),

                        Forms\Components\Toggle::make('condition')
                            ->label(__('Condition')),

                        Forms\Components\Textarea::make('description')
                            ->label(__('Description'))
                            ->columnSpanFull(),
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

                Tables\Columns\TextColumn::make('segment.name')
                    ->label(__('Segment'))
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