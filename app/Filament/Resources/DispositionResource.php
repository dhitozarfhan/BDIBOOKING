<?php

namespace App\Filament\Resources;

use App\Enums\ResponseStatus;
use App\Filament\Resources\DispositionResource\Pages;
use App\Models\Gratification;
use App\Models\Wbs;
use App\Models\ReportProcess;
use App\Models\Question;
use Filament\Forms;
use Filament\Notifications\Notification;
use App\Models\WbsProcess;
use App\Models\GratificationProcess;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DispositionResource extends Resource
{
    use Translatable;

    protected static ?string $model = ReportProcess::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    public static function getNavigationLabel(): string
    {
        return __('Disposition');
    }

    protected static ?string $slug = 'dispositions';

    public static function getNavigationGroup(): ?string
    {
        return __('Report');
    }

    public static function getNavigationSort(): ?int
    {
        return 8;
    }

    public static function getTranslatableLocales(): array
    {
        return ['id', 'en'];
    }

    public static function getModelLabel(): string
    {
        return __('Disposition');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Dispositions');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('response_status_id', ResponseStatus::Disposition->value)
            ->whereIn('reportable_type', [
                Wbs::class,
                Gratification::class,
                \App\Models\Question::class,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reportable.reporter_name')
                    ->label('Reporter Name')
                    ->formatStateUsing(fn ($state, $record) => $record->reportable->reporter_name ?? $record->reportable->name)
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHasMorph('reportable', [Wbs::class, Gratification::class], function (Builder $query) use ($search) {
                            $query->where('reporter_name', 'like', "%{$search}%");
                        })->orWhereHasMorph('reportable', [Question::class], function (Builder $query) use ($search) {
                            $query->where('name', 'like', "%{$search}%");
                        });
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('reportable.report_title')
                    ->label('Report Title')
                    ->formatStateUsing(fn ($state, $record) => $record->reportable->report_title ?? $record->reportable->subject)
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHasMorph('reportable', [Wbs::class, Gratification::class], function (Builder $query) use ($search) {
                            $query->where('report_title', 'like', "%{$search}%");
                        })->orWhereHasMorph('reportable', [Question::class], function (Builder $query) use ($search) {
                            $query->where('subject', 'like', "%{$search}%");
                        });
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('reportable_type')
                    ->label('Source')
                    ->formatStateUsing(fn (string $state): string => class_basename($state))
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('Balas')
                    ->label(__('Reply'))
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->form([
                        Forms\Components\Textarea::make('answer')
                            ->label(__('Answer'))
                            ->required(),
                        Forms\Components\FileUpload::make('answer_attachment')
                            ->label(__('Answer Attachment'))
                            ->disk('public')
                            ->directory('dispositions'),
                    ])
                    ->action(function (array $data, ReportProcess $record): void {
                        $record->update([
                            'answer' => $data['answer'],
                            'answer_attachment' => $data['answer_attachment'],
                            'response_status_id' => ResponseStatus::Termination,
                        ]);

                        Notification::make()
                            ->title('Berhasil')
                            ->success()
                            ->body('Balasan disposisi telah berhasil disimpan.')
                            ->send();
                    }),
            ])
            ->bulkActions([
                //
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
            'index' => Pages\ListDispositions::route('/'),
        ];
    }
}