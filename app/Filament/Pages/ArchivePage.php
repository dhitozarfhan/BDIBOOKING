<?php

namespace App\Filament\Pages;

use App\Models\Folder;
use App\Models\Document;
use Filament\Pages\Page;

class ArchivePage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static string $view = 'filament.pages.archive-page';

    protected static ?string $navigationGroup = 'Kearsipan';

    protected static ?int $navigationSort = 13;

    public $search = '';

    protected $queryString = ['search'];

    public function getTitle(): string
    {
        return __('Arsip');
    }

    public static function getNavigationLabel(): string
    {
        return __('Arsip');
    }

    public function updatedSearch()
    {
        $this->dispatchBrowserEvent('search-updated', ['search' => $this->search]);
    }

    public function getViewData(): array
    {
        // Get all folders with their relationships
        $query = Folder::with([
            'classification',
            'location',
            'location.children',
            'location.children.children',
            'documents.segment',
            'documents.accounts'
        ]);

        if ($this->search) {
            // Filter folders based on search term
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhereHas('classification', function ($q2) {
                      $q2->where('code', 'like', '%' . $this->search . '%')
                        ->orWhere('name', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('documents', function ($q2) {
                      $q2->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%')
                        ->orWhere('information', 'like', '%' . $this->search . '%');
                  });
            });
        }

        $folders = $query->get();

        return [
            'folders' => $folders,
            'search' => $this->search,
        ];
    }
}