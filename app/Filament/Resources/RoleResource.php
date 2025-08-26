<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Filament\Resources\RoleResource\RelationManagers;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-finger-print';
    
    public static function getNavigationSort(): ?int
    {
        return 92;
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Manage Access Rights');
    }

    public static function getModelLabel(): string
    {
        return __('Role');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label(__('Name'))
                    ->minLength(2)->maxLength(100)->unique(ignoreRecord:true)->required(),

                Forms\Components\Select::make('permissions')->label(__('Permissions'))
                    ->relationship('permissions', 'name')->multiple()->preload()->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label(__('Name'))->sortable()->searchable(),
                TextColumn::make('permissions.name')->label(__('Permissions'))->searchable()->badge(),
                TextColumn::make('users.name')->label(__('Employee'))->searchable()->badge(),
                
                
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
            'index' => Pages\ListRoles::route('/')
        ];
    }
}
