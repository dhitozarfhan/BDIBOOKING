<?php

namespace App\Filament\Pages;

use App\Models\Account;
use App\Models\Folder;
use App\Models\Classification;
use App\Models\Location;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ArchivePage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static string $view = 'filament.pages.archive-page';

    protected static ?int $navigationSort = 28;

    public $search = '';
    public $classificationId = '';
    public $locationId = '';
    public $accountId = '';
    public $startDate = '';
    public $endDate = '';

    protected $queryString = [
        'search',
        'classificationId',
        'locationId',
        'accountId',
        'startDate',
        'endDate'
    ];
    
    //canAccess
    public static function canAccess(): bool
    {
        $user = Auth::user();

        // Safeguard: if permissions are not yet seeded or user missing permission,
        // simply hide this page from navigation instead of throwing an exception.
        if (! $user) {
            return false;
        }

        try {
            return $user->hasPermissionTo(\App\Enums\PermissionType::Archives->value);
        } catch (\Spatie\Permission\Exceptions\PermissionDoesNotExist $e) {
            return false;
        }
    }

    public function mount()
    {
        $this->search = request()->query('search', $this->search);
        $this->classificationId = request()->query('classificationId', $this->classificationId);
        $this->locationId = request()->query('locationId', $this->locationId);
        $this->accountId = request()->query('accountId', $this->accountId);
        $this->startDate = request()->query('startDate', $this->startDate);
        $this->endDate = request()->query('endDate', $this->endDate);
    }

    public function getTitle(): string
    {
        return __('Archive Recaps');
    }

    public static function getNavigationLabel(): string
    {
        return __('Archive Recaps');
    }
    
    public static function getNavigationGroup(): ?string
    {
        return __('Archives');
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
        $this->accountId = '';
        $this->startDate = '';
        $this->endDate = '';
    }

    public function exportToExcel()
    {
        // Initialize filter parameters from request query
        $search = request()->query('search', $this->search);
        $classificationId = request()->query('classificationId', $this->classificationId);
        $locationId = request()->query('locationId', $this->locationId);
        $accountId = request()->query('accountId', $this->accountId);
        $startDate = request()->query('startDate', $this->startDate);
        $endDate = request()->query('endDate', $this->endDate);

        // If there's a search term, we need to approach this differently
        if ($search) {
            // When searching, get documents that match the search and include their folder info
            $documentQuery = \App\Models\Document::with([
                'folder.classification',
                'folder.location',
                'folder.location.children',
                'folder.location.children.children',
                'segment',
                'accounts'
            ]);

            // Find matching classification and location IDs for hierarchical path searching
            $matchingClassificationIds = $this->getMatchingClassificationIds($search);
            $matchingLocationIds = $this->getMatchingLocationIds($search);
            
            $documentQuery->where(function ($q) use ($search, $matchingClassificationIds, $matchingLocationIds) {
                $q->where('name', 'ilike', '%' . $search . '%')
                  ->orWhere('description', 'ilike', '%' . $search . '%')
                  ->orWhere('information', 'ilike', '%' . $search . '%')
                  ->orWhereHas('folder.classification', function ($q2) use ($search, $matchingClassificationIds) {
                      $q2->where('code', 'ilike', '%' . $search . '%')
                        ->orWhere('name', 'ilike', '%' . $search . '%')
                        ->orWhereHas('ancestors', function ($q3) use ($search) {
                            $q3->where('code', 'ilike', '%' . $search . '%')
                              ->orWhere('name', 'ilike', '%' . $search . '%');
                        });
                      
                      // If we found matching classification IDs based on hierarchical path, include those too
                      if (!empty($matchingClassificationIds)) {
                          $q2->orWhereIn('id', $matchingClassificationIds);
                      }
                  })
                  ->orWhereHas('folder.location', function ($q2) use ($search, $matchingLocationIds) {
                      $q2->where('code', 'ilike', '%' . $search . '%')
                        ->orWhere('name', 'ilike', '%' . $search . '%')
                        ->orWhereHas('ancestors', function ($q3) use ($search) {
                            $q3->where('code', 'ilike', '%' . $search . '%')
                              ->orWhere('name', 'ilike', '%' . $search . '%');
                        });
                      
                      // If we found matching location IDs based on hierarchical path, include those too
                      if (!empty($matchingLocationIds)) {
                          $q2->orWhereIn('id', $matchingLocationIds);
                      }
                  })
                  ->orWhereHas('accounts', function ($q2) use ($search) {
                      $q2->where('code', 'ilike', '%' . $search . '%')
                        ->orWhere('name', 'ilike', '%' . $search . '%');
                  });
            });

            // Apply filters
            if ($classificationId) {
                $documentQuery->whereHas('folder', function ($q) use ($classificationId) {
                    $q->where('classification_id', $classificationId);
                });
            }

            if ($locationId) {
                $documentQuery->whereHas('folder', function ($q) use ($locationId) {
                    $q->where('location_id', $locationId);
                });
            }

            if ($accountId) {
                $documentQuery->whereHas('accounts', function ($q) use ($accountId) {
                    $q->where('accounts.id', $accountId);
                });
            }

            if ($startDate || $endDate) {
                if ($startDate) {
                    $documentQuery->where('published_at', '>=', $startDate);
                }
                if ($endDate) {
                    $documentQuery->where('published_at', '<=', $endDate);
                }
            }

            $documents = $documentQuery->get();

            // Group documents by folder for processing
            $folders = $documents->groupBy('folder_id')->map(function ($groupedDocuments, $folderId) {
                $folder = $groupedDocuments->first()->folder;
                $folder->documents = $groupedDocuments;
                return $folder;
            })->values();
        } else {
            // Standard approach when not searching
            $query = Folder::with([
                'classification',
                'location',
                'location.children',
                'location.children.children',
                'documents.segment',
                'documents.accounts'
            ]);

            // Apply filters
            if ($classificationId) {
                $query->where('classification_id', $classificationId);
            }

            if ($locationId) {
                $query->where('location_id', $locationId);
            }

            if ($accountId) {
                $query->whereHas('documents.accounts', function ($q) use ($accountId) {
                    $q->where('accounts.id', $accountId);
                });
            }

            if ($startDate || $endDate) {
                $query->whereHas('documents', function ($q) use ($startDate, $endDate) {
                    if ($startDate) {
                        $q->where('published_at', '>=', $startDate);
                    }
                    if ($endDate) {
                        $q->where('published_at', '<=', $endDate);
                    }
                });
            }

            $folders = $query->get();
        }

        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator("BDIYK")
            ->setLastModifiedBy("BDIYK")
            ->setTitle("Arsip Data")
            ->setSubject("Arsip Data")
            ->setDescription("Data arsip yang diekspor dari sistem BDIYK");

        // Set column headers
        $headers = [
            'Kode Klasifikasi',
            'Nama Dokumen',
            'Deskripsi',
            'Tanggal Dokumen',
            'Kurun Waktu',
            'Jumlah Dokumen',
            'Lokasi',
            'Mata Anggaran Kegiatan',
            'Akun',
            'Keterangan',
            'Retensi Arsip Aktif',
            'Retensi Arsip Inaktif',
            'Nasib Akhir Arsip'
        ];

        // Write headers
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }

        // Style headers
        $sheet->getStyle('A1:M1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'E2E8F0',
                ],
            ],
        ]);

        // Write data
        $row = 2;
        foreach ($folders as $folder) {
            $documents = $folder->documents;

            if ($documents->count() > 0) {
                foreach ($documents as $document) {
                    // Get classification path
                    $classificationPath = '';
                    if ($folder->classification) {
                        $ancestors = $folder->classification->ancestors()->defaultOrder()->get();
                        $path = [];
                        foreach ($ancestors as $ancestor) {
                            $path[] = $ancestor->code;
                        }
                        $path[] = $folder->classification->code;
                        $classificationPath = implode('.', $path);
                    }

                    // Get location path
                    $locationPath = '';
                    $location = $folder->location;
                    if ($location) {
                        $ancestors = $location->ancestors()->defaultOrder()->get();
                        $path = [];
                        foreach ($ancestors as $ancestor) {
                            $path[] = $ancestor->code;
                        }
                        $path[] = $location->code;
                        $locationPath = implode('.', $path);
                    }

                    // Get segment path
                    $segmentPath = '';
                    if ($document->segment) {
                        $ancestors = $document->segment->ancestors()->defaultOrder()->get();
                        $path = [];
                        foreach ($ancestors as $ancestor) {
                            $path[] = $ancestor->code;
                        }
                        $path[] = $document->segment->code;
                        $segmentPath = implode('.', $path);
                    }

                    // Get account codes
                    $accountCodes = $document->accounts->pluck('code')->implode("\n");

                    // Write data - each document gets its own row
                    $sheet->setCellValue('A' . $row, $classificationPath);
                    $sheet->setCellValue('B' . $row, $document->name);
                    $sheet->setCellValue('C' . $row, $document->description ?? '');
                    $sheet->setCellValue('D' . $row, $document->published_at ? $document->published_at->format('d/m/Y') : '-');
                    $sheet->setCellValue('E' . $row, $document->published_at ? $document->published_at->format('Y') : '-');
                    $sheet->setCellValue('F' . $row, '1 Berkas');
                    $sheet->setCellValue('G' . $row, $locationPath);
                    $sheet->setCellValue('H' . $row, $segmentPath);
                    $sheet->setCellValue('I' . $row, $accountCodes);
                    $sheet->setCellValue('J' . $row, $document->information ?? '');
                    $sheet->setCellValue('K' . $row, $document->active_retention ?? '');
                    $sheet->setCellValue('L' . $row, $document->inactive_retention ?? '');
                    
                    // Nasib Akhir Arsip
                    $nasibAkhir = '';
                    if ($document->condition == '0') {
                        $nasibAkhir = 'Musnah';
                    } elseif ($document->condition == '1') {
                        $nasibAkhir = 'Tidak Musnah';
                    }
                    $sheet->setCellValue('M' . $row, $nasibAkhir);

                    $row++;
                }
            } else {
                // Folder with no documents
                $classificationPath = '';
                if ($folder->classification) {
                    $ancestors = $folder->classification->ancestors()->defaultOrder()->get();
                    $path = [];
                    foreach ($ancestors as $ancestor) {
                        $path[] = $ancestor->code;
                    }
                    $path[] = $folder->classification->code;
                    $classificationPath = implode('.', $path);
                }

                $sheet->setCellValue('A' . $row, $classificationPath);
                $sheet->setCellValue('B' . $row, '-');
                $sheet->setCellValue('C' . $row, '-');
                $sheet->setCellValue('D' . $row, '-');
                $sheet->setCellValue('E' . $row, '-');
                $sheet->setCellValue('F' . $row, '1 Berkas');
                $sheet->setCellValue('G' . $row, '-');
                $sheet->setCellValue('H' . $row, '-');
                $sheet->setCellValue('I' . $row, '-');
                $sheet->setCellValue('J' . $row, '-');
                $sheet->setCellValue('K' . $row, '-');
                $sheet->setCellValue('L' . $row, '-');
                $sheet->setCellValue('M' . $row, '-');

                $row++;
            }
        }

        // Auto-size columns
        foreach (range('A', 'M') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Set alignment for all cells
        $sheet->getStyle('A1:M' . ($row - 1))->applyFromArray([
            'alignment' => [
                'vertical' => Alignment::VERTICAL_TOP,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        // Create a temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'arsip_export_') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        // Return the file as a download response
        return response()->download($tempFile, 'arsip_export_' . date('Y-m-d_H-i-s') . '.xlsx')->deleteFileAfterSend(true);
    }

    public function getViewData(): array
    {
        // If there's a search term, we need to approach this differently
        if ($this->search) {
            // When searching, especially for account codes, we should get documents
            // that match the search and group them by their parent folder
            $documentQuery = \App\Models\Document::with([
                'folder.classification',
                'folder.location',
                'folder.location.children',
                'folder.location.children.children',
                'segment',
                'accounts'
            ]);

            $documentQuery->where(function ($q) {
                $q->where('name', 'ilike', '%' . $this->search . '%')
                  ->orWhere('description', 'ilike', '%' . $this->search . '%')
                  ->orWhere('information', 'ilike', '%' . $this->search . '%')
                  ->orWhereHas('folder.classification', function ($q2) {
                      $q2->where('code', 'ilike', '%' . $this->search . '%')
                        ->orWhere('name', 'ilike', '%' . $this->search . '%')
                        ->orWhereHas('ancestors', function ($q3) {
                            $q3->where('code', 'ilike', '%' . $this->search . '%')
                              ->orWhere('name', 'ilike', '%' . $this->search . '%');
                        });
                  })
                  ->orWhereHas('folder.location', function ($q2) {
                      $q2->where('code', 'ilike', '%' . $this->search . '%')
                        ->orWhere('name', 'ilike', '%' . $this->search . '%')
                        ->orWhereHas('ancestors', function ($q3) {
                            $q3->where('code', 'ilike', '%' . $this->search . '%')
                              ->orWhere('name', 'ilike', '%' . $this->search . '%');
                        });
                  })
                  ->orWhereHas('accounts', function ($q2) {
                      $q2->where('code', 'ilike', '%' . $this->search . '%')
                        ->orWhere('name', 'ilike', '%' . $this->search . '%');
                  });
            });
            
            // Additional search: find classification IDs that match the hierarchical path
            $matchingClassificationIds = $this->getMatchingClassificationIds($this->search);
            if (!empty($matchingClassificationIds)) {
                $documentQuery->orWhereHas('folder.classification', function ($q) use ($matchingClassificationIds) {
                    $q->whereIn('id', $matchingClassificationIds);
                });
            }
            
            // Additional search: find location IDs that match the hierarchical path
            $matchingLocationIds = $this->getMatchingLocationIds($this->search);
            if (!empty($matchingLocationIds)) {
                $documentQuery->orWhereHas('folder.location', function ($q) use ($matchingLocationIds) {
                    $q->whereIn('id', $matchingLocationIds);
                });
            }

            // Apply filters
            if ($this->classificationId) {
                $documentQuery->whereHas('folder', function ($q) {
                    $q->where('classification_id', $this->classificationId);
                });
            }

            if ($this->locationId) {
                $documentQuery->whereHas('folder', function ($q) {
                    $q->where('location_id', $this->locationId);
                });
            }

            if ($this->accountId) {
                $documentQuery->whereHas('accounts', function ($q) {
                    $q->where('accounts.id', $this->accountId);
                });
            }

            if ($this->startDate || $this->endDate) {
                if ($this->startDate) {
                    $documentQuery->where('published_at', '>=', $this->startDate);
                }
                if ($this->endDate) {
                    $documentQuery->where('published_at', '<=', $this->endDate);
                }
            }

            $documents = $documentQuery->get();

            // Group documents by folder
            $folders = $documents->groupBy('folder_id')->map(function ($groupedDocuments, $folderId) {
                // Get the folder with its relationships
                $folder = $groupedDocuments->first()->folder;
                $folder->documents = $groupedDocuments;
                return $folder;
            })->values();
        } else {
            // Standard approach when not searching
            $query = Folder::with([
                'classification',
                'location',
                'location.children',
                'location.children.children',
                'documents.segment',
                'documents.accounts'
            ]);

            // Apply filters
            if ($this->classificationId) {
                $query->where('classification_id', $this->classificationId);
            }

            if ($this->locationId) {
                $query->where('location_id', $this->locationId);
            }

            if ($this->accountId) {
                $query->whereHas('documents.accounts', function ($q) {
                    $q->where('accounts.id', $this->accountId);
                });
            }

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
        }

        // Get classifications with hierarchy for filter dropdowns
        $classifications = Classification::withDepth()->defaultOrder()->get()->mapWithKeys(function (Classification $item) {
            $title = $item->code . ' ' . $item->getReadableNameAttribute();
            $depth = $item->getAttribute('depth') ?? 0;
            $prefix = str_repeat('- ', $depth);
            return [$item->getKey() => trim("{$prefix} {$title}")];
        })->all();

        // Get locations with hierarchy for filter dropdowns
        $locations = Location::withDepth()->defaultOrder()->get()->mapWithKeys(function (Location $item) {
            $title = $item->code . ' ' . $item->getReadableNameAttribute();
            $depth = $item->getAttribute('depth') ?? 0;
            $prefix = str_repeat('- ', $depth);
            return [$item->getKey() => trim("{$prefix} {$title}")];
        })->all();

        // Get accounts for filter dropdown
        $accounts = \App\Models\Account::orderBy('code')->get()->mapWithKeys(function ($item) {
            return [$item->getKey() => $item->code . ' - ' . $item->name];
        })->all();

        // dd($accounts);

        return [
            'folders' => $folders,
            'search' => $this->search,
            'classifications' => $classifications,
            'locations' => $locations,
            'accounts' => $accounts,
            'classificationId' => $this->classificationId,
            'locationId' => $this->locationId,
            'accountId' => $this->accountId,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ];
    }
    
    /**
     * Get classification IDs that match the search term in their hierarchical path
     */
    private function getMatchingClassificationIds($search)
    {
        // If search is too short, don't perform hierarchical search
        if (strlen($search) < 2) {
            return [];
        }
        
        $classificationIds = [];
        
        // Get all classifications with ancestors in a single query using nested set model
        $allClassifications = \App\Models\Classification::with('ancestors')->get();
        
        foreach ($allClassifications as $classification) {
            // Build the hierarchical path
            $path = [];
            
            // Add ancestors codes (ordered from root to parent)
            $ancestors = $classification->ancestors()->defaultOrder()->get();
            foreach ($ancestors as $ancestor) {
                $path[] = $ancestor->code;
            }
            
            // Add the current classification's code
            $path[] = $classification->code;
            
            // Join with dots
            $fullPath = implode('.', $path);
            
            // Check if the search term matches the full hierarchical path
            if (stripos($fullPath, $search) !== false) {
                $classificationIds[] = $classification->id;
            }
        }
        
        return $classificationIds;
    }
    
    /** 
     * Get location IDs that match the search term in their hierarchical path
     */
    private function getMatchingLocationIds($search)
    {
        // If search is too short, don't perform hierarchical search
        if (strlen($search) < 2) {
            return [];
        }
        
        $locationIds = [];
        
        // Get all locations with ancestors in a single query using nested set model
        $allLocations = \App\Models\Location::with('ancestors')->get();
        
        foreach ($allLocations as $location) {
            // Build the hierarchical path
            $path = [];
            
            // Add ancestors codes (ordered from root to parent)
            $ancestors = $location->ancestors()->defaultOrder()->get();
            foreach ($ancestors as $ancestor) {
                $path[] = $ancestor->code;
            }
            
            // Add the current location's code
            $path[] = $location->code;
            
            // Join with dots
            $fullPath = implode('.', $path);
            
            // Check if the search term matches the full hierarchical path
            if (stripos($fullPath, $search) !== false) {
                $locationIds[] = $location->id;
            }
        }
        
        return $locationIds;
    }
}