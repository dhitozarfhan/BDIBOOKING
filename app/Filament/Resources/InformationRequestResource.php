<?php

namespace App\Filament\Resources;

use App\Enums\PermissionType;
use App\Filament\Resources\InformationRequestResource\Pages;
use App\Filament\Resources\InformationRequestResource\RelationManagers\ProcessRelationManager;
use App\Models\InformationRequest;
use App\Models\ResponseStatus; // Added
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

    public static function getTranslatableLocales(): array
    {
        return ['id', 'en'];
    }

    public static function canViewAny(): bool
    {
        return Auth::check() && (
            Auth::user()->hasPermissionTo(PermissionType::PublicInformation->value) ||
            Auth::user()->hasPermissionTo(PermissionType::Complaints->value)
        );
    }

    public static function canCreate(): bool
    {
        return false; // Requests are created from public form only
    }

    public static function canView(Model $record): bool
    {
        return Auth::check() && (
            Auth::user()->hasPermissionTo(PermissionType::PublicInformation->value) ||
            Auth::user()->hasPermissionTo(PermissionType::Complaints->value)
        );
    }

    public static function canDelete(Model $record): bool
    {
        return Auth::check() && (
            Auth::user()->hasPermissionTo(PermissionType::PublicInformation->value) ||
            Auth::user()->hasPermissionTo(PermissionType::Complaints->value)
        );
    }

    public static function canDeleteAny(): bool
    {
        return Auth::check() && (
            Auth::user()->hasPermissionTo(PermissionType::PublicInformation->value) ||
            Auth::user()->hasPermissionTo(PermissionType::Complaints->value)
        );
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Request Details'))
                    ->schema([
                        Forms\Components\TextInput::make('reporter_name')
                            ->label(__('Name'))
                            ->required()
                            ->maxLength(255)
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\RichEditor::make('report_title')
                            ->label(__('Information Requested'))
                            ->required()
                            ->columnSpanFull()
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\RichEditor::make('used_for')
                            ->label(__('Purpose of Request'))
                            ->required()
                            ->columnSpanFull()
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('id_card_number')
                            ->label(__('ID Card Number'))
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('occupation')
                            ->label(__('Occupation'))
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('mobile')
                            ->label(__('Mobile'))
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('email')
                            ->label(__('Email'))
                            ->email()
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\Textarea::make('address')
                            ->label(__('Address'))
                            ->columnSpanFull()
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('registration_code')
                            ->label(__('Registration Code'))
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('created_at')
                            ->label(__('Submitted At'))
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
                Tables\Columns\TextColumn::make('reporter_name')
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('report_title')
                    ->label(__('Information Requested'))
                    ->limit(50)
                    ->searchable(),
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
                    ->color(fn (string $state): string => match ($state) { // Menyesuaikan warna dengan nama status baru
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
            'index' => Pages\ListInformationRequests::route('/'),
            'view' => Pages\ViewInformationRequest::route('/{record}'),
        ];
    }
}
