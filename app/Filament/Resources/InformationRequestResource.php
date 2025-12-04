<?php

namespace App\Filament\Resources;

use App\Enums\PermissionType;
use App\Filament\Resources\InformationRequestResource\Pages;
use App\Models\InformationRequest;
use App\Models\ResponseStatus; // Added
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class InformationRequestResource extends Resource
{
    protected static ?string $model = InformationRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    
    public static function getNavigationGroup(): ?string
    {
        return __('Report');
    }
    
    public static function getNavigationLabel(): string
    {
        return __('Information Request');
    }
    
    public static function getNavigationSort(): ?int
    {
        return 8; // After WBS (7)
    }
    
    public static function getModelLabel(): string
    {
        return __('Information Request');
    }
    
    public static function getPluralModelLabel(): string
    {
        return __('Information Requests');
    }

    public static function canViewAny(): bool
    {
        return Auth::check() && (Auth::user()->hasPermissionTo(PermissionType::PublicInformation->value) ?? false);
    }

    public static function canCreate(): bool
    {
        return false; // Requests are created from public form only
    }

    public static function canView(Model $record): bool
    {
        return Auth::check() && (Auth::user()->hasPermissionTo(PermissionType::PublicInformation->value) ?? false);
    }

    public static function canEdit(Model $record): bool
    {
        return Auth::check() && (Auth::user()->hasPermissionTo(PermissionType::PublicInformation->value) ?? false);
    }

    public static function canDelete(Model $record): bool
    {
        return Auth::check() && (Auth::user()->hasPermissionTo(PermissionType::PublicInformation->value) ?? false);
    }

    public static function canDeleteAny(): bool
    {
        return Auth::check() && (Auth::user()->hasPermissionTo(PermissionType::PublicInformation->value) ?? false);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([
                Forms\Components\Section::make(__('Applicant Information'))
                    ->columnSpan(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('Name'))
                            ->disabled()
                            ->dehydrated(false),
                            
                        Forms\Components\TextInput::make('id_card_number')
                            ->label(__('ID Card Number'))
                            ->disabled()
                            ->dehydrated(false),
                            
                        Forms\Components\TextInput::make('email')
                            ->label(__('Email'))
                            ->disabled()
                            ->dehydrated(false),
                            
                        Forms\Components\TextInput::make('mobile')
                            ->label(__('Mobile'))
                            ->disabled()
                            ->dehydrated(false),
                            
                        Forms\Components\Textarea::make('address')
                            ->label(__('Address'))
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),
                            
                        Forms\Components\TextInput::make('occupation')
                            ->label(__('Occupation'))
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),
                    ]),
                    
                Forms\Components\Section::make(__('Request Details'))
                    ->columnSpan(2)
                    ->schema([
                        Forms\Components\Textarea::make('content')
                            ->label(__('Information Requested'))
                            ->disabled()
                            ->dehydrated(false)
                            ->rows(4)
                            ->columnSpanFull(),
                            
                        Forms\Components\Textarea::make('used_for')
                            ->label(__('Purpose of Request'))
                            ->disabled()
                            ->dehydrated(false)
                            ->rows(3)
                            ->columnSpanFull(),
                            
                        Forms\Components\TagsInput::make('grab_method')
                            ->label(__('Acquisition Method'))
                            ->disabled()
                            ->dehydrated(false),
                            
                        Forms\Components\TagsInput::make('delivery_method')
                            ->label(__('Delivery Method'))
                            ->disabled()
                            ->dehydrated(false),
                    ]),
                    
                Forms\Components\Section::make(__('Processing'))
                    ->columnSpan(1)
                    ->schema([
                        Forms\Components\Select::make('process.response_status_id')
                            ->label(__('Status'))
                            ->relationship('process.responseStatus', 'name')
                            ->options(ResponseStatus::all()->pluck('name', 'id')->toArray()) // Use ResponseStatus model
                            ->required()
                            ->native(false),
                            
                        Forms\Components\Textarea::make('process.answer')
                            ->label(__('Admin Notes'))
                            ->rows(6)
                            ->columnSpanFull(),
                            
                        Forms\Components\DateTimePicker::make('created_at')
                            ->label(__('Submitted At'))
                            ->disabled()
                            ->dehydrated(false)
                            ->native(false)
                            ->displayFormat('d F Y H:i'),
                            
                        Forms\Components\DateTimePicker::make('process.updated_at') // Use process.updated_at
                            ->label(__('Processed At'))
                            ->disabled()
                            ->dehydrated(false)
                            ->native(false)
                            ->displayFormat('d F Y H:i'),
                            
                        Forms\Components\TextInput::make('ip_address')
                            ->label(__('IP Address'))
                            ->disabled()
                            ->dehydrated(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('email')
                    ->label(__('Email'))
                    ->searchable()
                    ->copyable(),
                    
                Tables\Columns\TextColumn::make('mobile')
                    ->label(__('Mobile'))
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('process.responseStatus.name') // Mengambil nama status dari relasi
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn ($state): string => match ($state) { // Menyesuaikan warna dengan nama status baru
                        'Initiation' => 'warning',
                        'Process' => 'info',
                        'Disposition' => 'primary',
                        'Termination' => 'success',
                        default => 'gray',
                    })
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Submitted'))
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('process.updated_at') // Mengambil waktu proses dari relasi
                    ->label(__('Processed'))
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('process.response_status_id') // Memfilter berdasarkan id dari relasi
                    ->label(__('Status'))
                    ->options(ResponseStatus::all()->pluck('name', 'id')->toArray()) // Mengambil opsi filter dari model
                    ->multiple(),
                    
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label(__('From')),
                        Forms\Components\DatePicker::make('created_until')
                            ->label(__('Until')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListInformationRequests::route('/'),
            // 'create' => Pages\CreateInformationRequest::route('/create'),
            'view' => Pages\ViewInformationRequest::route('/{record}'),
            'edit' => Pages\EditInformationRequest::route('/{record}/edit'),
        ];
    }
}
