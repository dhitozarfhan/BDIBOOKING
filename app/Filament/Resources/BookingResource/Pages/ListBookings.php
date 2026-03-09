<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Resources\BookingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

use Filament\Resources\Components\Tab;
use App\Models\Property;
use App\Models\Training;
use Illuminate\Database\Eloquent\Builder;

class ListBookings extends ListRecords
{
    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'semua' => Tab::make('Semua')
                ->badge($this->getModel()::count())
                ->icon('heroicon-o-document-text'),
            
            'properti' => Tab::make('Sewa Properti')
                ->badge($this->getModel()::where('bookable_type', Property::class)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('bookable_type', Property::class))
                ->icon('heroicon-o-home-modern')
                ->badgeColor('info'),

            'pelatihan' => Tab::make('Pelatihan')
                ->badge($this->getModel()::where('bookable_type', Training::class)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('bookable_type', Training::class))
                ->icon('heroicon-o-academic-cap')
                ->badgeColor('success'),
        ];
    }
}
