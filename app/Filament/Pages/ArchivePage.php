<?php

namespace App\Filament\Pages;

use App\Models\Folder;
use App\Models\Document;
use App\Models\Classification;
use App\Models\Location;
use Filament\Pages\Page;
use Illuminate\Http\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

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

    public function exportToExcel()
    {
        // Get filtered data
        $data = $this->getViewData();
        $folders = $data['folders'];

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
            'Uraian Berkas',
            'Tanggal Berkas',
            'Kurun Waktu',
            'Jumlah Berkas',
            'No. Item Arsip',
            'Segment',
            'Akun',
            'Uraian Item Arsip',
            'Tanggal Item',
            'Lokasi',
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
        $sheet->getStyle('A1:O1')->applyFromArray([
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
            $folderRowSpan = $documents->count();

            if ($folderRowSpan > 0) {
                foreach ($documents as $index => $document) {
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

                    // Get latest document date for folder
                    $latestDate = $folder->documents->max('published_at');

                    // Write data
                    if ($index === 0) {
                        // First row of folder - include folder data
                        $sheet->setCellValue('A' . $row, $classificationPath);
                        $sheet->setCellValue('B' . $row, $folder->name);
                        $sheet->setCellValue('C' . $row, $latestDate ? $latestDate->format('d/m/Y') : '-');
                        $sheet->setCellValue('D' . $row, $latestDate ? $latestDate->format('Y') : '-');
                        $sheet->setCellValue('E' . $row, $folder->documents->count() . ' ' . ($folder->type === 'lembar' ? 'lembar' : 'berkas'));
                    }

                    // Document-specific data
                    $sheet->setCellValue('F' . $row, $index + 1);
                    $sheet->setCellValue('G' . $row, $segmentPath);
                    $sheet->setCellValue('H' . $row, $accountCodes);
                    $sheet->setCellValue('I' . $row, $document->name);
                    $sheet->setCellValue('J' . $row, $document->published_at ? $document->published_at->format('d/m/Y') : '-');
                    $sheet->setCellValue('K' . $row, $locationPath);
                    $sheet->setCellValue('L' . $row, $document->information ?? '');
                    $sheet->setCellValue('M' . $row, $document->active_retention ?? '');
                    $sheet->setCellValue('N' . $row, $document->inactive_retention ?? '');
                    
                    // Nasib Akhir Arsip
                    $nasibAkhir = '';
                    if ($document->condition == '0') {
                        $nasibAkhir = 'Musnah';
                    } elseif ($document->condition == '1') {
                        $nasibAkhir = 'Tidak Musnah';
                    }
                    $sheet->setCellValue('O' . $row, $nasibAkhir);

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

                $latestDate = $folder->documents->max('published_at');

                $sheet->setCellValue('A' . $row, $classificationPath);
                $sheet->setCellValue('B' . $row, $folder->name);
                $sheet->setCellValue('C' . $row, $latestDate ? $latestDate->format('d/m/Y') : '-');
                $sheet->setCellValue('D' . $row, $latestDate ? $latestDate->format('Y') : '-');
                $sheet->setCellValue('E' . $row, '0 ' . ($folder->type === 'lembar' ? 'lembar' : 'berkas'));
                $sheet->setCellValue('F' . $row, '');
                $sheet->setCellValue('G' . $row, '');
                $sheet->setCellValue('H' . $row, '');
                $sheet->setCellValue('I' . $row, __('Tidak ada dokumen dalam folder ini.'));
                $sheet->setCellValue('J' . $row, '');
                $sheet->setCellValue('K' . $row, '');
                $sheet->setCellValue('L' . $row, '');
                $sheet->setCellValue('M' . $row, '');
                $sheet->setCellValue('N' . $row, '');
                $sheet->setCellValue('O' . $row, '');

                $row++;
            }
        }

        // Auto-size columns
        foreach (range('A', 'O') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Set alignment for all cells
        $sheet->getStyle('A1:O' . ($row - 1))->applyFromArray([
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