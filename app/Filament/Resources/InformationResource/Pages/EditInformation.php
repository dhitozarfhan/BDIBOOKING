<?php

namespace App\Filament\Resources\InformationResource\Pages;

use App\Enums\CategoryType;
use App\Filament\Resources\InformationResource;
use App\Models\Category;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInformation extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = InformationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    // protected function mutateFormDataBeforeFill(array $data): array
    // {
    //     if (empty($data['parent_id'])) {
    //         // $data['parent_id'] = Category::where('id', $data['category_id'])->pluck('parent_id')->first();
    //     // dd($data);
    //     }
    //     $data['parent_id'] = $data['parent_id'] ?? Category::where('id', Category::where('id', $data['category_id'])->pluck('parent_id')->first())->where('category_type_id', CategoryType::InformationType->value)->first()?->id;
    //     return $data;
    // }
}
