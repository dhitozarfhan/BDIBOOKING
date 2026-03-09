<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Filament\Resources\BookingResource\RelationManagers;
use App\Models\Booking;
use App\Models\Certificate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with([
            'customer',
            'participantData.requiredField',
        ]);
    }

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'PNBP';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Customer / Pemesan')
                    ->description('Informasi pemesan')
                    ->icon('heroicon-o-identification')
                    ->schema([
                        Forms\Components\TextInput::make('customer.name')
                            ->label('Nama Lengkap')
                            ->formatStateUsing(fn ($record) => $record?->customer?->name)
                            ->disabled(),
                        Forms\Components\TextInput::make('customer.email')
                            ->label('Email')
                            ->formatStateUsing(fn ($record) => $record?->customer?->email)
                            ->disabled(),
                        Forms\Components\TextInput::make('customer.phone')
                            ->label('No. Telepon')
                            ->formatStateUsing(fn ($record) => $record?->customer?->phone ?? '-')
                            ->disabled(),
                    ])->columns(3),

                Forms\Components\Section::make('Informasi Booking')
                    ->icon('heroicon-o-academic-cap')
                    ->schema([
                        Forms\Components\TextInput::make('bookable.title')
                            ->label('Nama Diklat / Layanan')
                            ->formatStateUsing(fn ($record) => $record?->bookable?->title ?? $record?->bookable?->name)
                            ->disabled(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                                'completed' => 'Completed',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('booking_type')
                            ->label('Tipe Booking')
                            ->formatStateUsing(fn ($record) => ucfirst($record?->booking_type ?? 'individual'))
                            ->disabled(),
                        Forms\Components\TextInput::make('quantity')
                            ->label('Jumlah')
                            ->formatStateUsing(fn ($record) => $record?->quantity ?? 1)
                            ->disabled(),
                        Forms\Components\TextInput::make('created_at_display')
                            ->label('Tanggal Pendaftaran')
                            ->formatStateUsing(fn ($record) => $record?->created_at?->format('d M Y H:i') ?? '-')
                            ->disabled(),
                    ])->columns(3),

                Forms\Components\Section::make('Persyaratan Dokumen')
                    ->description('Dokumen yang diupload peserta saat pendaftaran')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Forms\Components\Placeholder::make('documents_list')
                            ->label('')
                            ->content(function ($record) {
                                if (!$record) return 'Tidak ada data.';

                                $data = $record->participantData()->with('requiredField')->get();

                                if ($data->isEmpty()) {
                                    return new \Illuminate\Support\HtmlString(
                                        '<div class="text-sm text-gray-500 italic">Tidak ada persyaratan dokumen untuk booking ini.</div>'
                                    );
                                }

                                $html = '<div class="space-y-3">';
                                foreach ($data as $item) {
                                    $fieldName = $item->requiredField?->name ?? 'Dokumen';
                                    $isFile = $item->requiredField?->is_file ?? false;

                                    if ($isFile && $item->value) {
                                        $url = Storage::url($item->value);
                                        $html .= '<div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700">';
                                        $html .= '<div class="flex items-center gap-3">';
                                        $html .= '<div class="flex-shrink-0 w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900 flex items-center justify-center">';
                                        $html .= '<svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
                                        $html .= '</div>';
                                        $html .= '<div>';
                                        $html .= '<p class="text-sm font-semibold text-gray-900 dark:text-gray-100">' . e($fieldName) . '</p>';
                                        $html .= '<p class="text-xs text-gray-500 dark:text-gray-400">File terupload</p>';
                                        $html .= '</div></div>';
                                        $html .= '<a href="' . e($url) . '" target="_blank" class="inline-flex items-center gap-1 text-xs font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400 hover:underline">';
                                        $html .= '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>';
                                        $html .= 'Lihat File</a>';
                                        $html .= '</div>';
                                    } else {
                                        $html .= '<div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700">';
                                        $html .= '<div class="flex-shrink-0 w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900 flex items-center justify-center">';
                                        $html .= '<svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>';
                                        $html .= '</div>';
                                        $html .= '<div>';
                                        $html .= '<p class="text-sm font-semibold text-gray-900 dark:text-gray-100">' . e($fieldName) . '</p>';
                                        $html .= '<p class="text-xs text-gray-500 dark:text-gray-400">' . e($item->value ?? '-') . '</p>';
                                        $html .= '</div></div>';
                                    }
                                }
                                $html .= '</div>';

                                return new \Illuminate\Support\HtmlString($html);
                            }),
                    ]),

                Forms\Components\Section::make('Sertifikat')
                    ->icon('heroicon-o-trophy')
                    ->visible(fn ($record) => $record ? $record->bookable_type !== \App\Models\Property::class : true)
                    ->schema([
                        Forms\Components\TextInput::make('certificate.certificate_number')
                            ->label('No. Sertifikat')
                            ->formatStateUsing(fn ($record) => $record?->certificate?->certificate_number ?? '-')
                            ->disabled(),
                        Forms\Components\TextInput::make('certificate.issued_at')
                            ->label('Tanggal Terbit')
                            ->formatStateUsing(fn ($record) => $record?->certificate?->issued_at?->format('d M Y') ?? '-')
                            ->disabled(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Pemesan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.email')
                    ->label('Email'),
                Tables\Columns\TextColumn::make('bookable_title')
                    ->label('Diklat / Layanan')
                    ->getStateUsing(fn ($record) => $record->bookable?->title ?? $record->bookable?->name ?? '-')
                    ->searchable(query: function (Builder $query, string $search) {
                        return $query->whereHasMorph('bookable', '*', function (Builder $query, string $type) use ($search) {
                            if ($type === \App\Models\Training::class) {
                                $query->where('title', 'like', "%{$search}%");
                            } elseif ($type === \App\Models\Property::class) {
                                $query->where('name', 'like', "%{$search}%");
                            }
                        });
                    }),
                Tables\Columns\TextColumn::make('booking_type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'individual' => 'info',
                        'batch' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'completed' => 'warning',
                    }),
                Tables\Columns\TextColumn::make('certificate.certificate_number')
                    ->label('No. Sertifikat')
                    ->placeholder('-')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'gray')
                    ->visible(fn ($livewire) => !property_exists($livewire, 'activeTab') || $livewire->activeTab !== 'properti')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'completed' => 'Completed',
                    ]),
                Tables\Filters\SelectFilter::make('booking_type')
                    ->label('Tipe Booking')
                    ->options([
                        'individual' => 'Individual',
                        'batch' => 'Batch',
                    ]),
                Tables\Filters\Filter::make('has_certificate')
                    ->label('Sudah ada sertifikat')
                    ->visible(fn ($livewire) => !property_exists($livewire, 'activeTab') || $livewire->activeTab !== 'properti')
                    ->query(fn ($query) => $query->whereHas('certificate')),
                Tables\Filters\Filter::make('no_certificate')
                    ->label('Belum ada sertifikat')
                    ->visible(fn ($livewire) => !property_exists($livewire, 'activeTab') || $livewire->activeTab !== 'properti')
                    ->query(fn ($query) => $query->whereDoesntHave('certificate')),
                Tables\Filters\Filter::make('sewa_properti')
                    ->label('Kategori: Sewa Properti')
                    ->query(fn (Builder $query) => $query->where('bookable_type', \App\Models\Property::class)),
                Tables\Filters\Filter::make('diklat')
                    ->label('Kategori: Diklat')
                    ->query(fn (Builder $query) => $query->where('bookable_type', \App\Models\Training::class)),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),

                // Certificate actions
                Tables\Actions\Action::make('upload_certificate')
                    ->label('Upload Sertifikat')
                    ->icon('heroicon-o-document-arrow-up')
                    ->color('primary')
                    ->visible(fn ($record) => $record->bookable_type !== \App\Models\Property::class && !$record->certificate && in_array($record->status, ['approved', 'completed']))
                    ->form([
                        Forms\Components\TextInput::make('certificate_number')
                            ->label('Nomor Sertifikat')
                            ->default(fn () => Certificate::generateNumber())
                            ->required()
                            ->unique('certificates', 'certificate_number')
                            ->helperText('Nomor otomatis, bisa diubah jika perlu.'),
                        Forms\Components\FileUpload::make('file_path')
                            ->label('File Sertifikat (PDF)')
                            ->directory('certificates')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(5120)
                            ->required(),
                        Forms\Components\DatePicker::make('issued_at')
                            ->label('Tanggal Terbit')
                            ->default(now())
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $record->certificate()->create([
                            'certificate_number' => $data['certificate_number'],
                            'file_path' => $data['file_path'],
                            'issued_at' => $data['issued_at'],
                        ]);

                        if ($record->status === 'approved') {
                            $record->update(['status' => 'completed']);
                        }

                        Notification::make()
                            ->success()
                            ->title('Sertifikat berhasil diupload')
                            ->body("Nomor: {$data['certificate_number']}")
                            ->send();
                    }),

                Tables\Actions\Action::make('download_certificate')
                    ->label('Download Sertifikat')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->visible(fn ($record) => $record->bookable_type !== \App\Models\Property::class && $record->certificate !== null)
                    ->url(fn ($record) => $record->certificate ? Storage::url($record->certificate->file_path) : null)
                    ->openUrlInNewTab(),

                Tables\Actions\Action::make('delete_certificate')
                    ->label('Hapus Sertifikat')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->visible(fn ($record) => $record->bookable_type !== \App\Models\Property::class && $record->certificate !== null)
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Sertifikat')
                    ->modalDescription('Apakah Anda yakin ingin menghapus sertifikat ini?')
                    ->action(function ($record) {
                        $cert = $record->certificate;
                        if ($cert) {
                            Storage::delete($cert->file_path);
                            $cert->delete();
                        }
                        Notification::make()->success()->title('Sertifikat berhasil dihapus')->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\InvoicesRelationManager::class,
        ];
    }



    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
