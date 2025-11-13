<?php

namespace App\Filament\Resources\GratificationResource\Pages;



use App\Filament\Resources\GratificationResource;

use Filament\Actions;

use Filament\Infolists\Components\Grid;

use Filament\Infolists\Components\Group;

use Filament\Infolists\Components\ImageEntry;

use Filament\Infolists\Components\Section;

use Filament\Infolists\Components\TextEntry;

use Filament\Infolists\Infolist;

use Filament\Resources\Pages\ViewRecord;



class ViewGratification extends ViewRecord

{

    use ViewRecord\Concerns\Translatable;



    protected static string $resource = GratificationResource::class;



    public function infolist(Infolist $infolist): Infolist

    {

        return $infolist

            ->schema([

                Grid::make(3)

                    ->schema([

                        Group::make()

                            ->schema([

                                Section::make(__('Report Details'))

                                    ->schema([

                                        TextEntry::make('report_title')

                                            ->label(__('Report Title')),

                                        TextEntry::make('report_description')

                                            ->label(__('Report Description'))

                                            ->html(),

                                        TextEntry::make('registration_code')

                                            ->label(__('Registration Code')),

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

                                            ->label(__('Reporter Name')),

                                        TextEntry::make('identity_number')

                                            ->label(__('Identity Number')),

                                        TextEntry::make('occupation')

                                            ->label(__('Occupation')),

                                        TextEntry::make('phone')

                                            ->label(__('Phone')),

                                        TextEntry::make('email')

                                            ->label(__('Email')),

                                        TextEntry::make('address')

                                            ->label(__('Address'))

                                            ->html(),

                                    ])->columns(1),

                            ])->columnSpan(1),

                    ]),



                Section::make(__('Attachments'))

                    ->schema([

                        ImageEntry::make('identity_card_attachment')

                            ->label(__('ID Card Scan'))

                            ->disk('public')

                            ->visibility('private'),

                        TextEntry::make('attachment')

                            ->label(__('Supporting Data'))

                            ->html(),

                    ])->columns(1),

            ]);

    }

}
