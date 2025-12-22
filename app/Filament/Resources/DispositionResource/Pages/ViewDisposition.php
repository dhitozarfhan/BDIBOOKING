<?php

namespace App\Filament\Resources\DispositionResource\Pages;

use App\Filament\Resources\DispositionResource;
use Filament\Actions;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewDisposition extends ViewRecord
{
    protected static string $resource = DispositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Grid::make(3)->schema([
                    Group::make()->schema([
                        Section::make(__('Report Details'))
                            ->schema([
                                TextEntry::make('report_title')
                                    ->label(__('Report Title'))
                                    ->weight('bold')
                                    ->size('lg')
                                    ->state(function ($record) {
                                        return $record->reportable->report_title 
                                            ?? $record->reportable->subject 
                                            ?? '-';
                                    }),
                                TextEntry::make('registration_code')
                                    ->label(__('Registration Code'))
                                    ->copyable()
                                    ->copyMessage('Registration code copied')
                                    ->icon('heroicon-o-clipboard-document')
                                    ->state(fn ($record) => $record->reportable->registration_code ?? '-'),
                                TextEntry::make('created_at')
                                    ->label(__('Submitted At'))
                                    ->dateTime('d F Y H:i')
                                    ->icon('heroicon-o-calendar-days')
                                    ->state(fn ($record) => $record->reportable->created_at),
                                TextEntry::make('report_description')
                                    ->label(__('Report Description'))
                                    ->html()
                                    ->icon('heroicon-o-document-text')
                                    ->state(function ($record) {
                                        return $record->reportable->report_description 
                                            ?? $record->reportable->content 
                                            ?? $record->reportable->used_for 
                                            ?? '-';
                                    }),
                                TextEntry::make('source')
                                    ->label(__('Source'))
                                    ->badge()
                                    ->state(fn ($record) => class_basename($record->reportable_type)),
                            ])->columns(1),
                    ])->columnSpan(2),

                    Group::make()->schema([
                        Section::make(__('Reporter Information'))
                            ->schema([
                                TextEntry::make('reporter_name')
                                    ->label(__('Reporter Name'))
                                    ->icon('heroicon-o-user')
                                    ->state(fn ($record) => $record->reportable->reporter_name ?? $record->reportable->name ?? '-'),
                                TextEntry::make('identity_number')
                                    ->label(__('Identity Number'))
                                    ->icon('heroicon-o-identification')
                                    ->state(fn ($record) => $record->reportable->identity_number ?? $record->reportable->id_card_number ?? '-'),
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
                                    ->state(fn ($record) => $record->reportable->identity_card_attachment)
                                    ->visible(fn ($record) => !empty($record->reportable->identity_card_attachment)),
                                TextEntry::make('occupation')
                                    ->label(__('Occupation'))
                                    ->icon('heroicon-o-briefcase')
                                    ->state(fn ($record) => $record->reportable->occupation ?? '-')
                                    ->visible(fn ($record) => isset($record->reportable->occupation)),
                                TextEntry::make('phone')
                                    ->label(__('Phone'))
                                    ->icon('heroicon-o-phone')
                                    ->copyable()
                                    ->state(fn ($record) => $record->reportable->phone ?? $record->reportable->mobile ?? '-'),
                                TextEntry::make('email')
                                    ->label(__('Email'))
                                    ->icon('heroicon-o-envelope')
                                    ->copyable()
                                    ->state(fn ($record) => $record->reportable->email ?? '-'),
                                TextEntry::make('address')
                                    ->label(__('Address'))
                                    ->html()
                                    ->icon('heroicon-o-map-pin')
                                    ->state(fn ($record) => $record->reportable->address ?? '-')
                                    ->visible(fn ($record) => isset($record->reportable->address)),
                            ])->columns(1),
                    ])->columnSpan(1),
                ]),

                Section::make(__('Attachments'))
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('attachment')
                                ->label(__('Supporting Data'))
                                ->formatStateUsing(function ($state) {
                                    if (empty($state)) {
                                        return '-';
                                    }
                                    $url = route('download', ['path' => $state]);
                                    return '<a href="' . $url . '" target="_blank" class="filament-link inline-flex items-center gap-1">
                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                                </svg>
                                                <span>Download File</span>
                                           </a>';
                                })
                                ->html()
                                ->state(fn ($record) => $record->reportable->attachment ?? null)
                                ->visible(fn ($record) => !empty($record->reportable->attachment)),
                        ]),
                    ])
                    ->visible(fn ($record) => !empty($record->reportable->attachment)),
            ]);
    }
}
