<?php

namespace App\Filament\Resources\InformationRequestResource\Pages;

use App\Filament\Resources\InformationRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Enums\ResponseStatus;

class ViewInformationRequest extends ViewRecord
{
    protected static string $resource = InformationRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\EditAction::make(),
        ];
    }

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
}
