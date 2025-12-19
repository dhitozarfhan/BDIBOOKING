<?php

namespace App\Filament\Resources\InformationRequestResource\Pages;

use App\Filament\Resources\InformationRequestResource;
use Filament\Actions;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use App\Enums\ResponseStatus;

class ViewInformationRequest extends ViewRecord
{
    protected static string $resource = InformationRequestResource::class;

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
                                Section::make(__('Request Details'))
                                    ->schema([
                                        TextEntry::make('report_title')
                                            ->label(__('Information Requested'))
                                            ->html(),
                                        TextEntry::make('used_for')
                                            ->label(__('Purpose of Request'))
                                            ->html(),
                                        TextEntry::make('created_at')
                                            ->label(__('Submitted At'))
                                            ->dateTime('d F Y H:i'),
                                    ])->columns(1),
                            ])->columnSpan(2),

                        Group::make()
                            ->schema([
                                Section::make(__('Requester Information'))
                                    ->schema([
                                        TextEntry::make('reporter_name')
                                            ->label(__('Name')),
                                        TextEntry::make('id_card_number')
                                            ->label(__('ID Card Number')),
                                        TextEntry::make('identity_card_attachment')
                                            ->label(__('ID Card Attachment'))
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

                                Section::make(__('Latest Status'))
                                    ->schema([
                                        TextEntry::make('process.responseStatus.name')
                                            ->label(__('Status')),
                                        TextEntry::make('process.answer')
                                            ->label(__('Latest Answer'))
                                            ->html()
                                            ->placeholder('-'),
                                        TextEntry::make('process.created_at')
                                            ->label(__('Processed At'))
                                            ->dateTime('d F Y H:i')
                                            ->placeholder('-'),
                                    ])->columns(1),
                            ])->columnSpan(1),
                    ]),
            ]);
    }
}
