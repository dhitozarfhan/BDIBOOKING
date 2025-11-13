<?php

namespace App\Filament\Resources\WbsResource\RelationManagers;

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

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('response_status_id')
                    ->label(__('Response Status'))
                    ->options(fn () => ResponseStatus::all()->pluck('name', 'id'))
                    ->required(),
                Forms\Components\Textarea::make('answer')
                    ->label(__('Answer'))
                    ->required()
                    ->rows(6)
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('published_at')
                    ->label(__('Published At'))
                    ->seconds(false)
                    ->native(false)
                    ->displayFormat('d F Y H:i'),
                Forms\Components\FileUpload::make('answer_attachment')
                    ->label(__('Answer Attachment'))
                    ->disk('public')
                    ->directory('wbs/answers')
                    ->visibility('private')
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
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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