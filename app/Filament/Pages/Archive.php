<?php

namespace App\Filament\Pages;

use App\Models\Document;
use App\Models\Folder;
use Filament\Pages\Page;

class Archive extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static string $view = 'filament.pages.archive';

    protected static ?string $navigationGroup = 'Kearsipan';

    protected static ?int $navigationSort = 10;

    public static function getNavigationLabel(): string
    {
        return __('Arsip');
    }

    public function getViewData(): array
    {
        // Get all folders with their relationships
        $folders = Folder::with([
            'classification',
            'location',
            'documents.segment',
            'documents.accounts'
        ])->get();

        return [
            'folders' => $folders,
        ];
    }
}