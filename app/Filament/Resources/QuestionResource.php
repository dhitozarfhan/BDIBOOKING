<?php

namespace App\Filament\Resources;

use App\Enums\PermissionType;
use App\Filament\Resources\QuestionResource\Pages;
use App\Filament\Resources\QuestionResource\RelationManagers\ProcessRelationManager;
use App\Models\Question;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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

    public static function getNavigationSort(): ?int
    {
        return 9; // Position after other reports
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
            ->schema([
                Forms\Components\Section::make(__('Question Details'))
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
                        Forms\Components\TextInput::make('registration_code')
                            ->label(__('Registration Code'))
                            ->required()
                            ->maxLength(255)
                            ->disabled(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
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
                Tables\Columns\TextColumn::make('process.responseStatus.name') // Shows latest status
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
                                            $url = Illuminate\Support\Facades\Storage::disk('public')->url($process->answer_attachment);
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
                                            $url = Illuminate\Support\Facades\Storage::disk('public')->url($process->answer_attachment);
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
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListQuestions::route('/'),
            'view' => Pages\ViewQuestion::route('/{record}'),
        ];
    }
}
