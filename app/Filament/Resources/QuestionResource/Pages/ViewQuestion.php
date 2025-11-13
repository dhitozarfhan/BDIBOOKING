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
                                    ])->columns(1),
                            ])->columnSpan(1),
                    ]),
            ]);
    }
}
