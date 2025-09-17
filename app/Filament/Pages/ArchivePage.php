<?php

namespace App\Filament\Pages;

use App\Models\Folder;
use App\Models\Document;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
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
            ->query(Folder::query()->with([
                'classification',
                'location',
                'location.children',
                'location.children.children',
                'documents' => function ($query) {
                    $query->with(['segment', 'accounts']);
                }
            ]))
            ->columns([
                TextColumn::make('classification_info')
                    ->label(__('Kode Klasifikasi'))
                    ->formatStateUsing(fn (?Model $record) => $record?->classification ? 
                        "({$record->classification->code}) {$record->classification->name}" : '-'),
                
                // Merge column for Uraian Informasi Berkas Arsip
                TextColumn::make('archive_file_info')
                    ->label(__('Uraian Informasi Berkas Arsip'))
                    ->formatStateUsing(fn () => '')
                    ->weight('bold'),
                TextColumn::make('name')
                    ->label(__('Uraian Berkas'))
                    ->extraAttributes(['class' => 'pl-4']), // Indent to show it's under the merge column
                TextColumn::make('latest_document_date')
                    ->label(__('Tanggal'))
                    ->formatStateUsing(function (?Model $record) {
                        $latestDate = $record->documents->max('published_at');
                        return $latestDate ? $latestDate->format('d/m/Y') : '-';
                    })
                    ->extraAttributes(['class' => 'pl-4']), // Indent to show it's under the merge column
                TextColumn::make('year')
                    ->label(__('Kurun Waktu'))
                    ->formatStateUsing(function (?Model $record) {
                        $latestDate = $record->documents->max('published_at');
                        return $latestDate ? $latestDate->format('Y') : '-';
                    })
                    ->extraAttributes(['class' => 'pl-4']), // Indent to show it's under the merge column
                TextColumn::make('documents_count')
                    ->label(__('Jumlah Berkas'))
                    ->counts('documents')
                    ->formatStateUsing(fn ($state) => $state . ' Berkas')
                    ->extraAttributes(['class' => 'pl-4']), // Indent to show it's under the merge column
                
                // Merge column for Uraian Informasi Item Arsip
                TextColumn::make('archive_item_info')
                    ->label(__('Uraian Informasi Item Arsip'))
                    ->formatStateUsing(fn () => '')
                    ->weight('bold'),
                TextColumn::make('documents.number')
                    ->label(__('Nomor Item Arsip'))
                    ->formatStateUsing(function ($state, $record, $rowLoop) {
                        return $rowLoop->iteration;
                    })
                    ->extraAttributes(['class' => 'pl-4']), // Indent to show it's under the merge column
                TextColumn::make('documents.segment.code')
                    ->label(__('Segment'))
                    ->formatStateUsing(fn (Model $record) => $record->segment ? 
                        "({$record->segment->code}) {$record->segment->name}" : '-')
                    ->extraAttributes(['class' => 'pl-4']), // Indent to show it's under the merge column
                TextColumn::make('documents.accounts')
                    ->label(__('Akun'))
                    ->formatStateUsing(function (Model $record) {
                        $accounts = $record->accounts->pluck('code')->toArray();
                        return count($accounts) > 0 ? implode(', ', $accounts) : '-';
                    })
                    ->extraAttributes(['class' => 'pl-4']), // Indent to show it's under the merge column
                TextColumn::make('documents.name')
                    ->label(__('Uraian Arsip'))
                    ->extraAttributes(['class' => 'pl-4']), // Indent to show it's under the merge column
                TextColumn::make('documents.published_at')
                    ->label(__('Tanggal'))
                    ->date('d/m/Y')
                    ->extraAttributes(['class' => 'pl-4']), // Indent to show it's under the merge column
                
                // Merge column for Lokasi Simpan
                TextColumn::make('storage_location_info')
                    ->label(__('Lokasi Simpan'))
                    ->formatStateUsing(fn () => '')
                    ->weight('bold'),
                TextColumn::make('location_subchild')
                    ->label(__('File/Folder'))
                    ->formatStateUsing(function (?Model $record) {
                        if (!$record->location) return '-';
                        
                        // Get the sub-child (grandchild) of the location
                        $location = $record->location;
                        $children = $location->children;
                        
                        if ($children->count() > 0) {
                            $firstChild = $children->first();
                            $grandChildren = $firstChild->children;
                            if ($grandChildren->count() > 0) {
                                return $grandChildren->first()->code ?? '-';
                            }
                        }
                        
                        return '-';
                    })
                    ->extraAttributes(['class' => 'pl-4']), // Indent to show it's under the merge column
                TextColumn::make('location_child')
                    ->label(__('Box File'))
                    ->formatStateUsing(function (?Model $record) {
                        if (!$record->location) return '-';
                        
                        // Get the child of the location
                        $location = $record->location;
                        $children = $location->children;
                        
                        if ($children->count() > 0) {
                            return $children->first()->code ?? '-';
                        }
                        
                        return '-';
                    })
                    ->extraAttributes(['class' => 'pl-4']), // Indent to show it's under the merge column
                
                TextColumn::make('documents.active_retention')
                    ->label(__('Retensi Arsip Aktif')),
                TextColumn::make('documents.inactive_retention')
                    ->label(__('Retensi Arsip Inaktif')),
                TextColumn::make('documents.condition')
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