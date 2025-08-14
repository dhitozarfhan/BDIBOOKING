<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Resources\ArticleResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditArticle extends EditRecord
{
    use EditRecord\Concerns\Translatable;
    
    protected static string $resource = ArticleResource::class;

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

    protected function getCancelFormAction(): Action
    {
        return Actions\Action::make('cancel')
            ->label(__('Cancel'))
            ->color('gray')
            ->url(static::getResource()::getUrl('index'))
            ->requiresConfirmation(false);
    }
}
