<?php

namespace App\Filament\Resources\GratificationResource\RelationManagers;

use App\Models\ResponseStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ReportAnswersRelationManager extends RelationManager
{
    protected static string $relationship = 'reportProcesses';



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
                        $url = Storage::disk('public')->url($state);
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
                Tables\Actions\CreateAction::make(),
                Tables\Actions\Action::make('reply')
                    ->label('Tambahkan Balasan')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('info')
                    ->form([
                        Forms\Components\Select::make('response_status_id')
                            ->label(__('Response Status'))
                            ->options(fn () => \App\Models\ResponseStatus::all()->pluck('name', 'id'))
                            ->required(),
                        Forms\Components\RichEditor::make('answer')
                            ->label(__('Answer')),
                        Forms\Components\FileUpload::make('answer_attachment')
                            ->label(__('Answer Attachment'))
                            ->disk('public')
                            ->directory('gratifications/answers')
                            ->downloadable()
                            ->openable(),
                    ])
                    ->action(function (array $data, $livewire) {
                        $process = new \App\Models\ReportProcess();
                        $process->reportable_id = $livewire->ownerRecord->id;
                        $process->reportable_type = \App\Models\Gratification::class;
                        $process->response_status_id = $data['response_status_id'] ?? $livewire->ownerRecord->latestProcess->response_status_id ?? 1;
                        $process->answer = $data['answer'];
                        $process->answer_attachment = $data['answer_attachment'];
                        $process->save();
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->form([
                        Forms\Components\Select::make('response_status_id')
                            ->label(__('Response Status'))
                            ->options(fn () => \App\Models\ResponseStatus::all()->pluck('name', 'id'))
                            ->required(),
                        Forms\Components\RichEditor::make('answer')
                            ->label(__('Answer')),
                        Forms\Components\FileUpload::make('answer_attachment')
                            ->label(__('Answer Attachment'))
                            ->disk('public')
                            ->directory('gratifications/answers')
                            ->downloadable()
                            ->openable(),
                    ]),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}