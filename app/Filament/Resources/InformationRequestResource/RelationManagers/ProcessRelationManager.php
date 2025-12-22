<?php

namespace App\Filament\Resources\InformationRequestResource\RelationManagers;

use App\Enums\ResponseStatus as EnumsResponseStatus;
use App\Models\Employee;
use App\Models\ResponseStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProcessRelationManager extends RelationManager
{
    protected static string $relationship = 'reportProcesses';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('response_status_id')
                    ->label(__('Response Status'))
                    ->options(fn () => \App\Models\ResponseStatus::where('id', '!=', \App\Enums\ResponseStatus::Initiation->value)->pluck('name', 'id'))
                    ->required()
                    ->live(),
                Forms\Components\Select::make('disposition_to_employee_id')
                    ->label(__('Disposition To'))
                    ->options(Employee::all()->pluck('name', 'id'))
                    ->searchable()
                    ->visible(fn (Get $get): bool => (int) $get('response_status_id') === EnumsResponseStatus::Disposition->value)
                    ->required(fn (Get $get): bool => (int) $get('response_status_id') === EnumsResponseStatus::Disposition->value),
                Forms\Components\RichEditor::make('answer')
                    ->label(__('Answer'))
                    ->required(fn (Get $get): bool => (int) $get('response_status_id') !== EnumsResponseStatus::Disposition->value)
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('answer_attachment')
                    ->label(__('Answer Attachment'))
                    ->disk('private')
                    ->directory('information-requests/answers')
                    ->downloadable()
                    ->openable(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('responseStatus.name')
                    ->label(__('Status'))
                    ->badge(),
                TextColumn::make('answer')
                    ->label(__('Answer'))
                    ->limit(50)
                    ->wrap()
                    ->html(),
                TextColumn::make('answer_attachment')
                    ->label(__('Answer Attachment'))
                    ->formatStateUsing(function ($state) {
                        if (empty($state)) {
                            return '-';
                        }
                        $url = route('download', ['path' => $state]);
                        return '<a href="' . $url . '" target="_blank" class="filament-link inline-flex items-center gap-1">
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                    </svg>
                                    <span>Download</span>
                               </a>';
                    })
                    ->html(),
                TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime('d/m/Y H:i'),
            ])
            ->filters([
            ])
            ->headerActions([
                Tables\Actions\Action::make('reply')
                    ->label(__('Tambahkan Balasan'))
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('info')
                    ->visible(fn ($livewire) => ! $livewire->ownerRecord->process?->is_completed)
                    ->form([
                        Forms\Components\Select::make('response_status_id')
                            ->label(__('Response Status'))
                            ->options(fn () => \App\Models\ResponseStatus::where('id', '!=', \App\Enums\ResponseStatus::Initiation->value)->pluck('name', 'id'))
                            ->required()
                            ->live(),
                        Forms\Components\Select::make('disposition_to_employee_id')
                            ->label(__('Disposition To'))
                            ->options(Employee::all()->pluck('name', 'id'))
                            ->searchable()
                            ->visible(fn (Get $get): bool => (int) $get('response_status_id') === EnumsResponseStatus::Disposition->value)
                            ->required(fn (Get $get): bool => (int) $get('response_status_id') === EnumsResponseStatus::Disposition->value),
                        Forms\Components\RichEditor::make('answer')
                            ->label(__('Answer'))
                            ->required(fn (Get $get): bool => (int) $get('response_status_id') !== EnumsResponseStatus::Disposition->value)
                            ->columnSpanFull(),
                Forms\Components\RichEditor::make('answer')
                    ->label(__('Answer'))
                    ->required(fn (Get $get): bool => (int) $get('response_status_id') !== EnumsResponseStatus::Disposition->value),
                \Asmit\FilamentUpload\Forms\Components\AdvancedFileUpload::make('answer_attachment')
                    ->label(__('Answer Attachment'))
                    ->disk('private')
                    ->directory('gratifications/answers')
                    ->downloadable()
                    ->openable()
                    ->pdfPreviewHeight(400)
                    ->pdfDisplayPage(1)
                    ->pdfToolbar(true)
                    ->pdfZoomLevel(100)
                    ->pdfFitType(\Asmit\FilamentUpload\Enums\PdfViewFit::FIT)
                    ->pdfNavPanes(true),
                    ])
                    ->action(function (array $data, $livewire) {
                        $process = new \App\Models\ReportProcess();
                        $process->reportable_id = $livewire->ownerRecord->id;
                        $process->reportable_type = \App\Models\InformationRequest::class;
                        $process->response_status_id = $data['response_status_id'] ?? $livewire->ownerRecord->latestProcess->response_status_id ?? 1;
                        $process->answer = $data['answer'];
                        $process->answer_attachment = $data['answer_attachment'];
                        $process->disposition_to_employee_id = $data['disposition_to_employee_id'] ?? null;
                        $process->save();

                        if ($process->response_status_id == \App\Enums\ResponseStatus::Process->value) {
                            $terminationProcess = new \App\Models\ReportProcess();
                            $terminationProcess->reportable_id = $livewire->ownerRecord->id;
                            $terminationProcess->reportable_type = \App\Models\InformationRequest::class;
                            $terminationProcess->response_status_id = \App\Enums\ResponseStatus::Termination->value;
                            $terminationProcess->answer = $data['answer'];
                            $terminationProcess->answer_attachment = $data['answer_attachment'];
                            $terminationProcess->save();
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    public function infolist(\Filament\Infolists\Infolist $infolist): \Filament\Infolists\Infolist
    {
        return $infolist
            ->schema([
                \Filament\Infolists\Components\TextEntry::make('responseStatus.name')
                    ->label(__('Response Status'))
                    ->badge(),
                \Filament\Infolists\Components\TextEntry::make('dispositionTo.name')
                    ->label(__('Disposition To'))
                    ->visible(fn ($record) => $record->response_status_id === EnumsResponseStatus::Disposition->value),
                \Filament\Infolists\Components\TextEntry::make('answer')
                    ->label(__('Answer'))
                    ->html(),
                \Filament\Infolists\Components\TextEntry::make('answer_attachment')
                    ->label(__('Answer Attachment'))
                    ->formatStateUsing(function ($state) {
                        if (empty($state)) {
                            return '-';
                        }
                        $url = route('download', ['path' => $state]);
                        return '<a href="' . $url . '" target="_blank" class="filament-link inline-flex items-center gap-1">
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                    </svg>
                                    <span>Download</span>
                               </a>';
                    })
                    ->html(),
                \Filament\Infolists\Components\TextEntry::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime('d/m/Y H:i'),
            ]);
    }
}
