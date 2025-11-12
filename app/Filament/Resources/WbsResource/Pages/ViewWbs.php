<?php

namespace App\Filament\Resources\WbsResource\Pages;

use App\Filament\Resources\WbsResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewWbs extends ViewRecord
{
    use ViewRecord\Concerns\Translatable;

    protected static string $resource = WbsResource::class;
}