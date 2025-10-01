<?php

namespace App\Filament\Pages;

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
    public $startDate = '';
    public $endDate = '';

    protected $queryString = [
        'search',
        'classificationId',
        'locationId',
        'startDate',
        'endDate'
    ];
    
    //canAccess
    public static function canAccess(): bool
    {
        return Auth::user()->hasPermissionTo(\App\Enums\PermissionType::Archives->value);
    }

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
        $this->startDate = '';
        $this->endDate = '';
    }

    public function exportToExcel()
    {
        // Initialize filter parameters from request query
        $search = request()->query('search', $this->search);
        $classificationId = request()->query('classificationId', $this->classificationId);
        $locationId = request()->query('locationId', $this->locationId);
        $startDate = request()->query('startDate', $this->startDate);
        $endDate = request()->query('endDate', $this->endDate);

        // Get all folders with their relationships
        $query = Folder::with([
            'classification',
            'location',
            'location.children',
            'location.children.children',
            'documents.segment',
            'documents.accounts'
        ]);

        if ($search) {
            // Filter folders based on search term
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhereHas('classification', function ($q2) use ($search) {
                      $q2->where('code', 'like', '%' . $search . '%')
                        ->orWhere('name', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('documents', function ($q2) use ($search) {
                      $q2->where('name', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%')
                        ->orWhere('information', 'like', '%' . $search . '%');
                  });
            });
        }

        // Filter by classification
        if ($classificationId) {
            $query->where('classification_id', $classificationId);
        }

        // Filter by location
        if ($locationId) {
            $query->where('location_id', $locationId);
        }

        // Filter by date range
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