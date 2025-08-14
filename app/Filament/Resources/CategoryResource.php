<?php

namespace App\Filament\Resources;

use App\Enums\CategoryType;
use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CategoryResource extends Resource
{

    use Translatable;

    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder-open';

    public static function getModelLabel(): string
    {
        return __('Category');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                TextInput::make('name')->label(__('Category Name'))->required(),
                Toggle::make('is_active')->default(true)->label(__('Is Active ?'))
            ])->columnSpan(1)
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort', 'asc')
            ->reorderable('sort')
            ->columns([
                TextColumn::make('name')->label(__('Category Name'))->searchable(),
                ToggleColumn::make('is_active')->label(__('Is Active ?'))
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()->disabled(fn($record) => $record->is_root)->hidden(fn($record) => $record->is_root),
                Tables\Actions\EditAction::make(),//->disabled(fn($record) => $record->is_root)->hidden(fn($record) => $record->is_root),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }



    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('category_type_id', CategoryType::Article->value);
    }
}
