<?php

namespace App\Filament\Resources\WbsResource\Pages;

use App\Filament\Resources\WbsResource;
use Filament\Actions;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Storage;
use App\Enums\ResponseStatus;

class ViewWbs extends ViewRecord
{
    use ViewRecord\Concerns\Translatable;

    protected static string $resource = WbsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }

    public function mount(int | string $record): void
    {
        parent::mount($record);

        $record = $this->getRecord();
        if ($record->process && $record->process->response_status_id === ResponseStatus::Initiation->value) {
            $record->process->update([
                'response_status_id' => ResponseStatus::Process->value,
            ]);
        }
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
                                    ->size('lg'),
                                TextEntry::make('violation.name')
                                    ->label(__('Type of Violation')),
                                TextEntry::make('registration_code')
                                    ->label(__('Registration Code'))
                                    ->copyable()
                                    ->copyMessage('Registration code copied')
                                    ->icon('heroicon-o-clipboard-document'),
                                TextEntry::make('created_at')
                                    ->label(__('Submitted At'))
                                    ->dateTime('d F Y H:i')
                                    ->icon('heroicon-o-calendar-days'),
                                TextEntry::make('report_description')
                                    ->label(__('Report Description'))
                                    ->html(),
                            ])->columns(1),
                    ])->columnSpan(2),

                    Group::make()->schema([
                        Section::make(__('Reporter Information'))
                            ->schema([
                                TextEntry::make('reporter_name')
                                    ->label(__('Reporter Name'))
                                    ->icon('heroicon-o-user'),
                                TextEntry::make('identity_number')
                                    ->label(__('Identity Number'))
                                    ->icon('heroicon-o-identification'),
                                ImageEntry::make('identity_card_attachment')
                                    ->label(__('ID Card Scan'))
                                    ->disk('public')
                                    ->width('100%')
                                    ->height('auto')
                                    ->columnSpanFull()
                                    ->visible(fn ($state) => !empty($state)),
                                TextEntry::make('occupation')
                                    ->label(__('Occupation'))
                                    ->icon('heroicon-o-briefcase'),
                                TextEntry::make('phone')
                                    ->label(__('Phone'))
                                    ->icon('heroicon-o-phone')
                                    ->copyable(),
                                TextEntry::make('email')
                                    ->label(__('Email'))
                                    ->icon('heroicon-o-envelope')
                                    ->copyable(),
                                TextEntry::make('address')
                                    ->label(__('Address'))
                                    ->html()
                                    ->icon('heroicon-o-map-pin'),
                            ])->columns(1),
                    ])->columnSpan(1),
                ]),

                Section::make(__('Attachments'))
                    ->collapsible()
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('attachment')
                                ->label(__('Supporting Data'))
                                ->formatStateUsing(function ($state) {
                                    if (empty($state)) {
                                        return '-';
                                    }
                                    $url = Storage::disk('public')->url($state);
                                    return '<a href="' . $url . '" target="_blank" class="filament-link inline-flex items-center gap-1">
                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                                </svg>
                                                <span>Download File</span>
                                           </a>';
                                })
                                ->html()
                                ->visible(fn ($state) => !empty($state)),
                        ]),
                    ])->columns(1),
            ]);
    }
}