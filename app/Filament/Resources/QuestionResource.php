<?php

namespace App\Filament\Resources;

use App\Enums\PermissionType;
use App\Filament\Resources\QuestionResource\Pages;
use App\Models\Question;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use App\Models\ResponseStatus; // Added

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    public static function getNavigationGroup(): ?string
    {
        return __('Report');
    }

    public static function getModelLabel(): string
    {
        return __('Public Complaint');
    }

    public static function canViewAny(): bool
    {
        $user = Auth::user();
        return $user->hasPermissionTo(PermissionType::Complaints->value);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([
                Forms\Components\Section::make(__('Question Details'))
                    ->columnSpan(2)
                    ->schema([
                        Forms\Components\TextInput::make('subject')
                            ->required()
                            ->maxLength(255)
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\RichEditor::make('content')
                            ->required()
                            ->columnSpanFull()
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('mobile')
                            ->required()
                            ->maxLength(255)
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->disabled()
                            ->dehydrated(false),
                    ]),

                Forms\Components\Section::make(__('Processing'))
                    ->columnSpan(1)
                    ->schema([
                        Forms\Components\Select::make('process.response_status_id')
                            ->label(__('Status'))
                            ->relationship('process.responseStatus', 'name')
                            ->options(ResponseStatus::all()->pluck('name', 'id')->toArray())
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
                            
                        Forms\Components\DateTimePicker::make('process.updated_at')
                            ->label(__('Processed At'))
                            ->disabled()
                            ->dehydrated(false)
                            ->native(false)
                            ->displayFormat('d F Y H:i'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('subject')
                    ->label(__('Subject'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('mobile')
                    ->label(__('Mobile'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('Email'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('process.responseStatus.name') // Added status column
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        'Initiation' => 'warning',
                        'Process' => 'info',
                        'Disposition' => 'primary',
                        'Termination' => 'success',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Submitted At'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
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
            ReportAnswersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuestions::route('/'),
            'view' => Pages\ViewQuestion::route('/{record}'),
        ];
    }
}
