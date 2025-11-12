<?php

namespace App\Filament\Resources\GratificationResource\Pages;

use App\Filament\Resources\GratificationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewGratification extends ViewRecord
{
    use ViewRecord\Concerns\Translatable;

    protected static string $resource = GratificationResource::class;
}