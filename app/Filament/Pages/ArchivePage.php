<?php

namespace App\Filament\Pages;

use App\Models\Folder;
use App\Models\Document;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Tables\Columns\HierarchyColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;

class ArchivePage extends Page implements HasTable
{
    use InteractsWithTable;

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

    public function table(Table $table): Table
    {
        return $table
            ->query(Document::query()->with([
                'folder',
                'folder.classification',
                'folder.location',
                'folder.location.children',
                'folder.location.children.children',
                'segment',
                'accounts'
            ]))
            ->columns([
                TextColumn::make('folder.classification.code')
                    ->label(__('Kode Klasifikasi'))
                    ->searchable(query: function ($query, $search) {
                        return $query->whereHas('folder.classification', function ($query) use ($search) {
                            $query->where('code', 'ilike', "%{$search}%")
                                ->orWhere('name', 'ilike', "%{$search}%")
                                ->orWhereHas('ancestors', function ($query) use ($search) {
                                    $query->where('code', 'ilike', "%{$search}%")
                                        ->orWhere('name', 'ilike', "%{$search}%");
                                });
                        });
                    }),
                
                // Group column for Uraian Informasi Berkas Arsip
                ColumnGroup::make(__('Uraian Informasi Berkas Arsip'), [
                    TextColumn::make('folder.name')
                        ->label(__('Uraian Berkas')),
                    TextColumn::make('folder_latest_date')
                        ->label(__('Tanggal'))
                        ->formatStateUsing(function (?Model $record) {
                            $latestDate = $record->folder->documents->max('published_at');
                            return $latestDate ? $latestDate->format('d/m/Y') : '-';
                        }),
                    TextColumn::make('folder_year')
                        ->label(__('Kurun Waktu'))
                        ->formatStateUsing(function (?Model $record) {
                            $latestDate = $record->folder->documents->max('published_at');
                            return $latestDate ? $latestDate->format('Y') : '-';
                        }),
                    TextColumn::make('folder_count')
                        ->label(__('Jumlah Berkas'))
                        ->formatStateUsing(function (?Model $record) {
                            $count = $record->folder->documents->count();
                            $type = $record->folder->type ?? 'berkas';
                            $label = $type === 'lembar' ? 'Lembar' : 'Berkas';
                            return $count . ' ' . $label;
                        }),
                ]),
                
                // Group column for Uraian Informasi Item Arsip
                ColumnGroup::make(__('Uraian Informasi Item Arsip'), [
                    TextColumn::make('id')
                        ->label(__('Nomor Item Arsip'))
                        ->formatStateUsing(function ($state, $record, $rowLoop) {
                            return $rowLoop->iteration;
                        }),
                    TextColumn::make('accounts')
                        ->label(__('Akun'))
                        ->formatStateUsing(function (?Model $record) {
                            if (!$record->accounts) return '-';
                            $accounts = $record->accounts->pluck('code')->toArray();
                            return count($accounts) > 0 ? implode(', ', $accounts) : '-';
                        }),
                    
                    TextColumn::make('segment')
                        ->label(__('Segment'))
                        ->formatStateUsing(function (?Model $record) {
                            if (!$record->segment) return '-';
                            return "({$record->segment->code}) {$record->segment->name}";
                        }),
                    TextColumn::make('name')
                        ->label(__('Uraian Arsip')),
                    TextColumn::make('published_at')
                        ->label(__('Tanggal'))
                        ->date('d/m/Y'),
                ]),
                
                // Group column for Lokasi Simpan
                ColumnGroup::make(__('Lokasi Simpan'), [
                    TextColumn::make('folder.location')
                        ->label(__('File/Folder'))
                        ->formatStateUsing(function (?Model $record) {
                            if (!$record->folder || !$record->folder->location) return '-';
                            
                            // Get the sub-child (grandchild) of the location
                            $location = $record->folder->location;
                            $children = $location->children;
                            
                            if ($children->count() > 0) {
                                $firstChild = $children->first();
                                $grandChildren = $firstChild->children;
                                if ($grandChildren->count() > 0) {
                                    return $grandChildren->first()->code ?? '-';
                                }
                            }
                            
                            return '-';
                        }),
                    TextColumn::make('folder.location')
                        ->label(__('Box File'))
                        ->formatStateUsing(function (?Model $record) {
                            if (!$record->folder || !$record->folder->location) return '-';
                            
                            // Get the child of the location
                            $location = $record->folder->location;
                            $children = $location->children;
                            
                            if ($children->count() > 0) {
                                return $children->first()->code ?? '-';
                            }
                            
                            return '-';
                        }),
                ]),
                
                TextColumn::make('active_retention')
                    ->label(__('Retensi Arsip Aktif')),
                TextColumn::make('inactive_retention')
                    ->label(__('Retensi Arsip Inaktif')),
                TextColumn::make('condition')
                    ->label(__('Nasib Akhir Arsip'))
                    ->formatStateUsing(fn ($state) => $state ? 'Tidak Musnah' : 'Musnah'),
            ])
            ->filters([
                // Add any filters you need here
            ])
            ->actions([
                // Add any actions you need here
            ])
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25);
    }
}