<?php

namespace App\Filament\Pages;

use App\Models\Folder;
use App\Models\Document;
use App\Models\Classification;
use App\Models\Location;
use Filament\Pages\Page;

class ArchivePage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static string $view = 'filament.pages.archive-page';

    protected static ?string $navigationGroup = 'Kearsipan';

    protected static ?int $navigationSort = 13;

    public $search = '';
    public $classificationId = '';
    public $locationId = '';
    public $startDate = '';
    public $endDate = '';

    protected $queryString = [
        'search',
        'classificationId',
        'locationId',
        'startDate',
        'endDate'
    ];

    public function mount()
    {
        $this->search = request()->query('search', $this->search);
        $this->classificationId = request()->query('classificationId', $this->classificationId);
        $this->locationId = request()->query('locationId', $this->locationId);
        $this->startDate = request()->query('startDate', $this->startDate);
        $this->endDate = request()->query('endDate', $this->endDate);
    }

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

    public function resetFilters()
    {
        $this->search = '';
        $this->classificationId = '';
        $this->locationId = '';
        $this->startDate = '';
        $this->endDate = '';
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

        // Filter by classification
        if ($this->classificationId) {
            $query->where('classification_id', $this->classificationId);
        }

        // Filter by location
        if ($this->locationId) {
            $query->where('location_id', $this->locationId);
        }

        // Filter by date range
        if ($this->startDate || $this->endDate) {
            $query->whereHas('documents', function ($q) {
                if ($this->startDate) {
                    $q->where('published_at', '>=', $this->startDate);
                }
                if ($this->endDate) {
                    $q->where('published_at', '<=', $this->endDate);
                }
            });
        }

        $folders = $query->get();

        // Get classifications and locations for filter dropdowns
        $classifications = Classification::orderBy('code')->get();
        $locations = Location::orderBy('code')->get();

        return [
            'folders' => $folders,
            'search' => $this->search,
            'classifications' => $classifications,
            'locations' => $locations,
            'classificationId' => $this->classificationId,
            'locationId' => $this->locationId,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ];
    }
}