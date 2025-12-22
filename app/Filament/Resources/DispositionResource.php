<?php

namespace App\Filament\Resources;

use App\Enums\ResponseStatus;
use App\Enums\PermissionType;
use App\Filament\Resources\DispositionResource\Pages;
use App\Models\Gratification;
use App\Models\InformationRequest;
use App\Models\Question;
use App\Models\ReportProcess;
use App\Models\Wbs;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class DispositionResource extends Resource
{
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

    public static function getModelLabel(): string
    {
        return __('Disposition');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Dispositions');
    }

    public static function shouldRegisterNavigation(): bool
    {
        // Only show this navigation item to users who have the permission.
        // We use can() to check for the permission, whether it's direct or via a role.
        // A check for 'super-admin' role is kept as a fallback.
        return Auth::user()->hasRole('super-admin') || Auth::user()->can(PermissionType::ManageDisposition->value);
    }

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();
        
        // Start with base query
        $query = parent::getEloquentQuery();
        
        // IMPORTANT: Apply personalization filter FIRST!
        // Non-super-admin employees can ONLY see dispositions assigned to them.
        // Super-admin can see all dispositions (no filter applied).
        if ($user && !$user->hasRole('super-admin')) {
            $query->where('disposition_to_employee_id', $user->id);
        }
        
        // Then apply other filters
        $query->where('response_status_id', ResponseStatus::Disposition->value)
            ->whereIn('reportable_type', [
                Wbs::class,
                Gratification::class,
                Question::class,
                InformationRequest::class,
            ])
            ->whereDoesntHave('reportable.reportProcesses', function (Builder $query) {
                $query->where('response_status_id', ResponseStatus::Termination->value);
            });

        return $query;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reportable.reporter_name')
                    ->label(__('Reporter Name'))
                    ->formatStateUsing(fn ($state, $record) => $record->reportable->reporter_name ?? $record->reportable->name)
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHasMorph('reportable', [Wbs::class, Gratification::class, InformationRequest::class, Question::class], function (Builder $query) use ($search) {
                            $query->where('reporter_name', 'like', "%{$search}%");
                        });
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('reportable.report_title')
                    ->label(__('Report Title'))
                    ->formatStateUsing(fn ($state, $record) => $record->reportable->report_title ?? $record->reportable->subject)
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHasMorph('reportable', [Wbs::class, Gratification::class, InformationRequest::class, Question::class], function (Builder $query) use ($search) {
                            $query->where('report_title', 'like', "%{$search}%");
                        });
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('reportable_type')
                    ->label(__('Source'))
                    ->formatStateUsing(fn (string $state): string => class_basename($state))
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('Balas')
                    ->label(__('Reply'))
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->form([
                        Forms\Components\Textarea::make('answer')
                            ->label(__('Answer'))
                            ->required(),
                        Forms\Components\FileUpload::make('answer_attachment')
                            ->label(__('Answer Attachment'))
                            ->disk('private')
                            ->directory(function ($record) {
                                return match ($record->reportable_type) {
                                    \App\Models\Wbs::class => 'wbs/answers',
                                    \App\Models\Gratification::class => 'gratifications/answers',
                                    \App\Models\Question::class => 'questions/answers',
                                    \App\Models\InformationRequest::class => 'information-requests/answers',
                                    default => 'answers',
                                };
                            }),
                    ])
                    ->action(function (array $data, ReportProcess $record): void {
                        // Create new Termination record instead of updating disposition
                        ReportProcess::create([
                            'reportable_type' => $record->reportable_type,
                            'reportable_id' => $record->reportable_id,
                            'response_status_id' => ResponseStatus::Termination,
                            'answer' => $data['answer'],
                            'answer_attachment' => $data['answer_attachment'],
                        ]);

                        Notification::make()
                            ->title(__('Berhasil'))
                            ->success()
                            ->body(__('Balasan disposisi telah berhasil disimpan.'))
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
            'view' => Pages\ViewDisposition::route('/{record}'),
        ];
    }
}