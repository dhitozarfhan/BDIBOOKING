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

class ViewQuestion extends ViewRecord
{
    use ViewRecord\Concerns\Translatable;

    protected static string $resource = QuestionResource::class;

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
                                        TextEntry::make('subject')
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
                                        TextEntry::make('name')
                                            ->label(__('Name')),
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
                                            ->default('-'),
                                        TextEntry::make('process.created_at')
                                            ->label(__('Processed At'))
                                            ->dateTime('d F Y H:i')
                                            ->default('-'),
                                    ])->columns(1),
                            ])->columnSpan(1),
                    ]),
            ]);
    }
}
