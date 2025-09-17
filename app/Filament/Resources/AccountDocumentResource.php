<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccountDocumentResource\Pages;
use App\Models\AccountDocument;
use App\Models\Account;
use App\Models\Document;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AccountDocumentResource extends Resource
{
    protected static ?string $model = AccountDocument::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    public static function getNavigationGroup(): ?string
    {
        return __('Kearsipan');
    }

    public static function getNavigationSort(): ?int
    {
        return 13;
    }

    public static function getModelLabel(): string
    {
        return __('Account Document');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Dokumen Akun');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('account_id')
                            ->label(__('Account'))
                            ->options(Account::all()->pluck('formatted_code_name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('document_id')
                            ->label(__('Document'))
                            ->relationship('document', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('account.code')
                    ->label(__('Account Code'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('account.name')
                    ->label(__('Account Name'))
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('document.name')
                    ->label(__('Document Name'))
                    ->searchable()
                    ->sortable()
                    ->limit(30),

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
            'index' => Pages\ListAccountDocuments::route('/'),
            'create' => Pages\CreateAccountDocument::route('/create'),
            'edit' => Pages\EditAccountDocument::route('/{record}/edit'),
        ];
    }
}