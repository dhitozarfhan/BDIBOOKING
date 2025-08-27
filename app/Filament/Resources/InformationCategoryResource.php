<?php

namespace App\Filament\Resources;

use App\Enums\CategoryType;
use App\Enums\PermissionType;
use App\Filament\Resources\InformationCategoryResource\Pages;
use App\Models\Category;
use Filament\Forms\Components\Radio;
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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class InformationCategoryResource extends Resource
{

    use Translatable;

    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';
    
    public static function getNavigationSort(): ?int
    {
        return 12;
    }

    public static function getModelLabel(): string
    {
        return __('Public Information Type');
    }

    public static function canViewAny(): bool
    {
        return Auth::user()->hasPermissionTo(PermissionType::PublicInformationCategory->value) ?? false;
    }

    public static function canCreate(): bool
    {
        return Auth::user()->hasPermissionTo(PermissionType::PublicInformationCategory->value) ?? false;
    }

    public static function canView(Model $record): bool
    {
        return Auth::user()->hasPermissionTo(PermissionType::PublicInformationCategory->value) ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return Auth::user()->hasPermissionTo(PermissionType::PublicInformationCategory->value) ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        return Auth::user()->hasPermissionTo(PermissionType::PublicInformationCategory->value) ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return Auth::user()->hasPermissionTo(PermissionType::PublicInformationCategory->value) ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Radio::make('parent_id', fn (Builder $query) => $query->orderBy('id'))
                    ->label(__('Category Type'))
                    ->options(Category::where('category_type_id', CategoryType::InformationType->value)->pluck('name', 'id'))
                    ->inlineLabel(false)
                    ->required()
                    ->disabled(fn (string $operation): bool => $operation === 'edit'),
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
            'index' => Pages\ListInformationCategories::route('/'),
            'create' => Pages\CreateInformationCategory::route('/create'),
            'edit' => Pages\EditInformationCategory::route('/{record}/edit'),
        ];
    }



    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('category_type_id', CategoryType::Information->value);
    }
}
