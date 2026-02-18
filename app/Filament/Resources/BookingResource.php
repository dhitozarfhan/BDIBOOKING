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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'PNBP';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Peserta')
                    ->schema([
                        Forms\Components\TextInput::make('participant.name')
                            ->label('Nama Peserta')
                            ->formatStateUsing(fn ($record) => $record?->participant?->name)
                            ->disabled(),
                        Forms\Components\TextInput::make('participant.email')
                            ->label('Email')
                            ->formatStateUsing(fn ($record) => $record?->participant?->email)
                            ->disabled(),
                    ])->columns(2),

                Forms\Components\Section::make('Informasi Diklat')
                    ->schema([
                        Forms\Components\TextInput::make('bookable.title')
                            ->label('Nama Diklat')
                            ->formatStateUsing(fn ($record) => $record?->bookable?->title)
                            ->disabled(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                                'completed' => 'Completed',
                            ])
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Sertifikat')
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
                Tables\Columns\TextColumn::make('participant.name')
                    ->label('Peserta')
                    ->searchable(),
                Tables\Columns\TextColumn::make('participant.email')
                    ->label('Email'),
                Tables\Columns\TextColumn::make('bookable.title')
                    ->label('Diklat')
                    ->searchable(),
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
                Tables\Filters\Filter::make('has_certificate')
                    ->label('Sudah ada sertifikat')
                    ->query(fn ($query) => $query->whereHas('certificate')),
                Tables\Filters\Filter::make('no_certificate')
                    ->label('Belum ada sertifikat')
                    ->query(fn ($query) => $query->whereDoesntHave('certificate')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),

                // Certificate actions
                Tables\Actions\Action::make('upload_certificate')
                    ->label('Upload Sertifikat')
                    ->icon('heroicon-o-document-arrow-up')
                    ->color('primary')
                    ->visible(fn ($record) => !$record->certificate && in_array($record->status, ['approved', 'completed']))
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
                    ->visible(fn ($record) => $record->certificate !== null)
                    ->url(fn ($record) => $record->certificate ? Storage::url($record->certificate->file_path) : null)
                    ->openUrlInNewTab(),

                Tables\Actions\Action::make('delete_certificate')
                    ->label('Hapus Sertifikat')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->visible(fn ($record) => $record->certificate !== null)
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
