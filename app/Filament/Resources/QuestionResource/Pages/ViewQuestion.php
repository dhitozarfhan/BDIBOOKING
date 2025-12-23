<?php

namespace App\Filament\Resources\QuestionResource\Pages;

use App\Filament\Resources\QuestionResource;
use Filament\Actions;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use App\Enums\ResponseStatus;

class ViewQuestion extends ViewRecord
{
    protected static string $resource = QuestionResource::class;

    public function mount(int | string $record): void
    {
        parent::mount($record);

        $record = $this->getRecord();
        if ($record->process && $record->process->response_status_id === ResponseStatus::Initiation->value) {
            $record->reportProcesses()->create([
                'response_status_id' => ResponseStatus::Process->value,
                'is_completed' => false,
            ]);
        }
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Grid::make(3)
                    ->schema([
                        Group::make()
                            ->schema([
                                Section::make(__('Question Details'))
                                    ->schema([
                                        TextEntry::make('report_title')
                                            ->label(__('Subject')),
                                        TextEntry::make('content')
                                            ->label(__('Content'))
                                            ->html(),
                                        TextEntry::make('created_at')
                                            ->label(__('Submitted At'))
                                            ->dateTime('d F Y H:i'),
                                    ])->columns(1),
                            ])->columnSpan(2),

                        Group::make()
                            ->schema([
                                Section::make(__('Reporter Information'))
                                    ->schema([
                                        TextEntry::make('reporter_name')
                                            ->label(__('Name')),
                                        TextEntry::make('identity_number')
                                            ->label(__('Identity Number')),
                                        TextEntry::make('identity_card_attachment')
                                            ->label(__('ID Card Scan'))
                                            ->formatStateUsing(function ($state) {
                                                if (empty($state)) {
                                                    return '-';
                                                }
                                                $url = route('download', ['path' => $state]);
                                                return '<img src="' . $url . '" style="width: 100%; height: auto;" alt="ID Card Scan" class="rounded-lg border">';
                                            })
                                            ->html()
                                            ->columnSpanFull()
                                            ->visible(fn ($state) => !empty($state)),
                                        TextEntry::make('mobile')
                                            ->label(__('Mobile')),
                                        TextEntry::make('email')
                                            ->label(__('Email')),
                                        TextEntry::make('registration_code')
                                            ->label(__('Registration Code')),
                                    ])->columns(1),

                            ])->columnSpan(1),
                    ]),
            ]);
    }
}
