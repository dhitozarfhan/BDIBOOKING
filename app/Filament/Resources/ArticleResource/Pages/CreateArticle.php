<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Enums\ArticleType;
use App\Filament\Resources\ArticleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateArticle extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = ArticleResource::class;
    protected static bool $canCreateAnother = false;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make()
        ];
    }

    protected function getRedirectUrl(): string
    {
        $data = $this->form->getState();

        if($data['article_type_id'] == ArticleType::Gallery->value){
            return static::getResource()::getUrl('edit', [
                'record' => $this->record
            ]);
        }
        else {
            return static::getResource()::getUrl('index');
        }
    }
}
