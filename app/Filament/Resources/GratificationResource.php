<?php

namespace App\Filament\Resources;

use App\Enums\PermissionType;
use App\Filament\Resources\GratificationResource\Pages;
use App\Filament\Resources\GratificationResource\RelationManagers\ProcessRelationManager;
use App\Models\Gratification;
use App\Models\ResponseStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class GratificationResource extends Resource
{
    use Translatable;

    protected static ?string $model = Gratification::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function getNavigationGroup(): ?string
    {
        return __('Report');
    }

    public static function getNavigationLabel(): string
    {
        return __('Gratification');
    }

    public static function getNavigationSort(): ?int
    {
        return 6; // Position after Article (5)
    }

    public static function getTranslatableLocales(): array
    {
        return ['id', 'en'];
    }

    public static function canViewAny(): bool
    {
        $user = Auth::user();
        // Allow access to users with Complaints permission
        return $user->hasPermissionTo(PermissionType::Complaints->value);
    }

    public static function canEdit($record): bool
    {
        $user = Auth::user();
        return $user->hasPermissionTo(PermissionType::Complaints->value);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Reporter Information'))
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('reporter_name')
                            ->label(__('Reporter Name'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('identity_number')
                            ->label(__('Identity Number'))
                            ->maxLength(255),
                        Forms\Components\Textarea::make('address')
                            ->label(__('Address'))
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('occupation')
                            ->label(__('Occupation'))
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->label(__('Phone'))
                            ->maxLength(20),
                        Forms\Components\TextInput::make('email')
                            ->label(__('Email'))
                            ->email()
                            ->maxLength(255),
                    ]),

                Forms\Components\Section::make(__('Report Details'))
                    ->columns(1)
                    ->schema([
                        Forms\Components\TextInput::make('report_title')
                            ->label(__('Report Title'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('report_description')
                            ->label(__('Report Description'))
                            ->required()
                            ->rows(6)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('registration_code')
                            ->label(__('Registration Code'))
                            ->required()
                            ->maxLength(255)
                            ->disabled(),
                        Forms\Components\FileUpload::make('identity_card_attachment')
                            ->label(__('ID Card Scan'))
                            ->disk('public')
                            ->directory('gratifications/identity_cards')
                            ->visibility('private')
                            ->downloadable()
                            ->openable(),
                        Forms\Components\FileUpload::make('attachment')
                            ->label(__('Supporting Data'))
                            ->disk('public')
                            ->directory('gratifications')
                            ->visibility('private')
                            ->downloadable()
                            ->openable(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('registration_code')
                    ->label(__('Registration Code'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('reporter_name')
                    ->label(__('Reporter Name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('report_title')
                    ->label(__('Report Title'))
                    ->searchable()
                    ->wrap()
                    ->sortable(),
                TextColumn::make('process.responseStatus.name')
                    ->label(__('Status'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('Submitted At'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                // You can add filters here if needed
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // You can add bulk actions here if needed
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ProcessRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGratifications::route('/'),
            'view' => Pages\ViewGratification::route('/{record}'),
            'edit' => Pages\EditGratification::route('/{record}/edit'),
        ];
    }
}