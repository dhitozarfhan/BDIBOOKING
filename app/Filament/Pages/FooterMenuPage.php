<?php

namespace App\Filament\Pages;

use App\Enums\NavigationType as EnumsNavigationType;
use App\Models\Navigation;
use Kalnoy\Nestedset\QueryBuilder;
use Studio15\FilamentTree\Components\TreePage;

class FooterMenuPage extends TreePage
{

    public function getTitle(): string
    {
        return __('Footer Navigation');
    }

    public static function getNavigationLabel(): string
    {
        return __('Footer Navigation');
    }
    
    public static function getNavigationGroup(): ?string
    {
        return __('Menu');
    }
    
    public static function getNavigationSort(): ?int
    {
        return 11;
    }

    public static function getModel(): string|QueryBuilder
    {
        return Navigation::scoped(['navigation_type_id' => EnumsNavigationType::Footer->value]);
    }

    public static function getCreateForm(): array
    {
        return HeaderMenuPage::getCreateForm();
    }

    public static function getEditForm(): array
    {
        return HeaderMenuPage::getEditForm();
    }

    public static function getInfolistColumns(): array
    {
        return [
            //
        ];
    }
}
