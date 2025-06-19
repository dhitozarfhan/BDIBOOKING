<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Symfony\Contracts\Service\Attribute\Required;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    TextInput::make('id_name')->required(),
                    TextInput::make('en_name'),
                ])->columns(2),
                Section::make()->schema([
                    Select::make('type')
                    ->options(['news' => 'News', 'blog' => 'Blog', 'gallery' => 'Gallery',
                                'event' => 'Event', 'information' => 'Information', 'question' => 'Question',
                                'request' => 'Request', 'wbs' => 'WBS', 'service' => 'Service',])->required(),
                    Select::make('core_id')->relationship('cores', 'core_id'),
                ])->columns(2),
                Section::make()->schema([
                    TextInput::make('sort')->required(),
                    Toggle::make('is_root')->default(true)->label('Apakah Root?'),
                    Toggle::make('is_active')->default(true)->label('Apakah Active?'),
                ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category_id'),
                TextColumn::make('core_id'),
                TextColumn::make('type'),
                TextColumn::make('en_name'),
                TextColumn::make('id_name'),
                TextColumn::make('sort'),
                ToggleColumn::make('is_root'),
                ToggleColumn::make('is_active'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
