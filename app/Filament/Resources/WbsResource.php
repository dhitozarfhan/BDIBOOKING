<?php

namespace App\Filament\Resources;

use App\Enums\PermissionType;
use App\Filament\Resources\WbsResource\Pages;
use App\Filament\Resources\WbsResource\RelationManagers\ProcessRelationManager;
use App\Models\ResponseStatus;
use App\Models\Employee; // Corrected from User
use App\Models\Violation;
use App\Models\Wbs;
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
use Illuminate\Support\Facades\Storage;

class WbsResource extends Resource
{
    use Translatable;

    protected static ?string $model = Wbs::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    public static function getNavigationGroup(): ?string
    {
        return __('Report');
    }

    public static function getNavigationLabel(): string
    {
        return __('Wbs');
    }

    public static function getNavigationSort(): ?int
    {
        return 7; // Position after Gratification (6)
    }

    public static function canViewAny(): bool
    {
        $user = Auth::user();
        // Allow access to users with Complaints permission
        return $user->hasPermissionTo(PermissionType::Complaints->value);
    }

    public static function canCreate(): bool
    {
        return false; // Should only come from form, not manual creation
    }

    public static function canView($record): bool
    {
        $user = Auth::user();
        return $user->hasPermissionTo(PermissionType::Complaints->value);
    }



    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function getModelLabel(): string
    {
        return __('WBS Report');
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
                        Forms\Components\Select::make('violation_id')
                            ->label(__('Type of Violation'))
                            ->relationship('violation', 'name'),
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
                            ->disk('private')
                            ->directory('identity_cards')
                            ->visibility('private')
                            ->downloadable()
                            ->openable(),
                        Forms\Components\FileUpload::make('attachment')
                            ->label(__('Supporting Data'))
                            ->disk('private')
                            ->directory('wbs')
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
                TextColumn::make('violation.name')
                    ->label(__('Type of Violation'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('process.responseStatus.name')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Initiation' => 'warning',
                        'Process' => 'info',
                        'Disposition' => 'primary',
                        'Termination' => 'success',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('Submitted At'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                            Tables\Actions\ViewAction::make(),
                            Tables\Actions\Action::make('selesai')
                                ->label('Selesai')
                                ->icon('heroicon-o-check-circle')
                                ->color('success')
                                ->visible(function ($record) {
                                    $terminationProcess = $record->reportProcesses()->where('response_status_id', \App\Enums\ResponseStatus::Termination->value)->first();
                                    return $terminationProcess && !$terminationProcess->is_completed;
                                })
                                ->action(function ($record) {
                                    $terminationProcess = $record->reportProcesses()->where('response_status_id', \App\Enums\ResponseStatus::Termination->value)->first();
                                    if ($terminationProcess) {
                                        $terminationProcess->update(['is_completed' => true]);
                                    }
                                })
                                ->modalHeading('Kirim Jawaban ke Publik')
                                ->modalDescription('Pastikan semua proses telah sesuai sebelum mengirimkan jawaban ke publik.')
                                ->modalContent(function ($record) {
                                    $processes = $record->reportProcesses()->orderBy('created_at', 'asc')->get();
                                    $html = '';
                                    foreach ($processes as $process) {
                                        $html .= '<div class="mb-4 border-b pb-4">';
                                        $html .= '<p><strong>Status:</strong> <span class="badge">' . $process->responseStatus->name . '</span></p>';
                                        $html .= '<p><strong>Jawaban:</strong> ' . $process->answer . '</p>';
                                        if ($process->answer_attachment) {
                                            $url = route('download', ['path' => $process->answer_attachment]);
                                            $html .= '<p><strong>Lampiran:</strong> <a href="' . $url . '" target="_blank" class="filament-link inline-flex items-center gap-1">
                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                                </svg>
                                                <span>Download</span>
                                           </a></p>';
                                        }
                                        $html .= '<p><strong>Tanggal:</strong> ' . $process->created_at->format('d/m/Y H:i') . '</p>';
                                        $html .= '</div>';
                                    }
                                    return new \Illuminate\Support\HtmlString($html);
                                })
                                ->modalSubmitActionLabel('Kirim'),
                            Tables\Actions\Action::make('riwayat')
                                ->label('Riwayat')
                                ->icon('heroicon-o-clock')
                                ->color('gray')
                                ->visible(function ($record) {
                                    $terminationProcess = $record->reportProcesses()->where('response_status_id', \App\Enums\ResponseStatus::Termination->value)->first();
                                    return $terminationProcess && $terminationProcess->is_completed;
                                })
                                ->modalHeading('Riwayat Proses Laporan')
                                ->modalContent(function ($record) {
                                    $processes = $record->reportProcesses()->orderBy('created_at', 'asc')->get();
                                    $html = '';
                                    foreach ($processes as $process) {
                                        $html .= '<div class="mb-4 border-b pb-4">';
                                        $html .= '<p><strong>Status:</strong> <span class="badge">' . $process->responseStatus->name . '</span></p>';
                                        $html .= '<p><strong>Jawaban:</strong> ' . $process->answer . '</p>';
                                        if ($process->answer_attachment) {
                                            $url = route('download', ['path' => $process->answer_attachment]);
                                            $html .= '<p><strong>Lampiran:</strong> <a href="' . $url . '" target="_blank" class="filament-link inline-flex items-center gap-1">
                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                                </svg>
                                                <span>Download</span>
                                           </a></p>';
                                        }
                                        $html .= '<p><strong>Tanggal:</strong> ' . $process->created_at->format('d/m/Y H:i') . '</p>';
                                        $html .= '</div>';
                                    }
                                    return new \Illuminate\Support\HtmlString($html);
                                })
                                ->modalSubmitAction(false)
                                ->modalCancelActionLabel('Tutup'),
                        ])
            ->bulkActions([
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
            'index' => Pages\ListWbs::route('/'),
            'view' => Pages\ViewWbs::route('/{record}'),
        ];
    }
}