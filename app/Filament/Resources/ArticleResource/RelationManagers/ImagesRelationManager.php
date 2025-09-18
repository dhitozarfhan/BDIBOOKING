<?php

namespace App\Filament\Resources\ArticleResource\RelationManagers;

use App\Enums\ArticleType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';

    public function form(Form $form): Form
    {
        $locales = config('services.locale.available');
        $schemas = [
            Forms\Components\FileUpload::make('path')
                ->label(__('Image'))
                ->image()
                ->imageEditor()
                ->directory(config('services.disk.article.slide'))
                ->columnSpanFull()
                ->required()
        ];

        foreach ($locales as $locale) {
            $schemas[] = Forms\Components\Textarea::make("description.{$locale}")
                ->label(__('Description') . ' (' . strtoupper($locale) . ')')
                ->maxLength(255)
                ->columnSpanFull();
        }
        return $form->schema($schemas);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort', 'asc')
            ->reorderable('sort')
            ->columns([
                ImageColumn::make('path')
                    ->label(__('Image'))
                    ->default(asset('images/no-image.jpg'))
                    ->size(50),
                Tables\Columns\TextColumn::make('description'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
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

    //set model name 
    public static function getModelLabel(): string
    {
        return __('Image');
    }

    //set model name plural
    public static function getModelLabelPlural(): string
    {
        return __('Images');
    }

    //set header name
    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Images') . ' - ' . $ownerRecord->title;
    }

    
    
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->article_type_id == ArticleType::Gallery->value;
    }
}
