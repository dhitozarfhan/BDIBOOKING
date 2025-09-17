<?php

namespace App\Filament\Pages;

use App\Models\Folder;
use Filament\Pages\Page;

class ArchivePage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static string $view = 'filament.pages.archive-page';

    protected static ?string $navigationGroup = 'Kearsipan';

    protected static ?int $navigationSort = 13;

    public function getTitle(): string
    {
        return __('Arsip');
    }

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
            'location.children',
            'location.children.children',
            'documents.segment',
            'documents.accounts'
        ])->get();

        return [
            'folders' => $folders,
        ];
    }
}