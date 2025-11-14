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
    protected static string $relationship = 'processes';



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
                TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime('d/m/Y H:i'),
                TextColumn::make('published_at')
                    ->label(__('Published At'))
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
                            ->visibility('private')
                            ->downloadable()
                            ->openable(),
                    ])
                    ->action(function (array $data, $livewire) {
                        $process = new \App\Models\GratificationProcess();
                        $process->gratification_id = $livewire->ownerRecord->id;
                        $process->response_status_id = $data['response_status_id'] ?? $livewire->ownerRecord->latestProcess->response_status_id ?? 1;
                        $process->answer = $data['answer'];
                        $process->answer_attachment = $data['answer_attachment'];

                        if ($data['answer'] || $data['answer_attachment']) {
                            $process->published_at = now();
                        }

                        $process->save();
                    }),
            ])
            ->actions([

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}