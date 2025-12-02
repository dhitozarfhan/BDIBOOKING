<?php

namespace App\Filament\Resources;

use App\Enums\ResponseStatus;
use App\Filament\Resources\DispositionResource\Pages;
use App\Models\Gratification;
use App\Models\Wbs;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DispositionResource extends Resource
{
    use Translatable;

    protected static ?string $model = Wbs::class;

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
        $wbsQuery = Wbs::query()
            ->whereHas('processes', function (Builder $query) {
                $query->where('response_status_id', ResponseStatus::Disposition);
            })
            ->select([
                'id',
                'reporter_name',
                'report_title',
                'created_at',
                \DB::raw("'Wbs' as source"),
            ]);

        $gratificationQuery = Gratification::query()
            ->whereHas('processes', function (Builder $query) {
                $query->where('response_status_id', ResponseStatus::Disposition);
            })
            ->select([
                'id',
                'reporter_name',
                'report_title',
                'created_at',
                \DB::raw("'Gratification' as source"),
            ]);

        $unionQuery = $wbsQuery->union($gratificationQuery);

        // We need to remove the default ordering that Filament applies.
        // We can do this by ordering by a column that exists in both queries.
        return Wbs::query()->fromSub($unionQuery, 'sub')->orderBy('created_at', 'desc');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reporter_name')
                    ->label('Reporter Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('report_title')
                    ->label('Report Title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                //
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