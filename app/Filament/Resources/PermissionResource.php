<?php

namespace App\Filament\Resources;

use App\Enums\PermissionType;
use App\Filament\Resources\PermissionResource\Pages;
use App\Models\Permission;
use CodeWithDennis\SimpleAlert\Components\Forms\SimpleAlert;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    public static function getNavigationSort(): ?int
    {
        return 91;
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Manage Access Rights');
    }

    public static function getModelLabel(): string
    {
        return __('Permission');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                SimpleAlert::make('info')->title('Cara membuat nama akses')->description('Pastikan Anda telah membuat nama permission di Enums')->info(),
                Forms\Components\TextInput::make('name')->label(__('Name'))
                    ->rules([
                        Rule::in( array_map(static fn(PermissionType $permissionType) => $permissionType->value, PermissionType::cases()) )
                    ])
                    ->minLength(2)->maxLength(100)->unique(ignoreRecord:true)->required(),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label(__('Name'))->sortable()->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                
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
            'index' => Pages\ListPermissions::route('/')
        ];
    }
}
