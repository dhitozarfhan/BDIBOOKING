<?php

namespace App\Filament\Resources\BookingResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class InvoicesRelationManager extends RelationManager
{
    protected static string $relationship = 'invoices';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('billing_code')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->default(fn () => ($this->getOwnerRecord()->bookable?->price ?? 0) * $this->getOwnerRecord()->quantity)
                    ->live(debounce: 500)
                    ->helperText(function ($state) {
                        if (!$state || !is_numeric($state)) return null;
                        $formatted = number_format((float)$state, 0, ',', '.');
                        $words = self::terbilang((int)$state);
                        return "Rp {$formatted} — {$words} Rupiah";
                    }),
                Forms\Components\Select::make('status')
                    ->options([
                        'unpaid' => 'Unpaid',
                        'paid' => 'Paid',
                        'expired' => 'Expired',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('unpaid')
                    ->required(),
                Forms\Components\FileUpload::make('invoice_file')
                    ->label('File Tagihan (PDF)')
                    ->directory('invoices/bills')
                    ->downloadable(),
                Forms\Components\FileUpload::make('payment_proof')
                    ->label('Bukti Bayar')
                    ->directory('invoices/payments')
                    ->downloadable()
                    ->image(), // Assuming it's an image, or just file
                Forms\Components\DateTimePicker::make('issued_at')
                    ->required()
                    ->default(now()),
                Forms\Components\DateTimePicker::make('due_date')
                    ->required(),
                Forms\Components\DateTimePicker::make('verified_at'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('billing_code')
            ->columns([
                Tables\Columns\TextColumn::make('billing_code'),
                Tables\Columns\TextColumn::make('amount')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->getStateUsing(fn ($record) => $record->effective_status)
                    ->color(fn (string $state): string => match ($state) {
                        'unpaid' => 'gray',
                        'paid' => 'success',
                        'expired' => 'danger',
                        'cancelled' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('due_date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->visible(fn () => !$this->getOwnerRecord()->invoices()->where('status', 'paid')->exists()),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn ($record) => $record->effective_status === 'unpaid'),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn ($record) => $record->effective_status === 'unpaid'),
                Tables\Actions\Action::make('verify')
                    ->label('Verifikasi Lunas')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->modalHeading('Verifikasi Pembayaran')
                    ->modalDescription('Periksa bukti bayar di bawah ini sebelum mengambil keputusan.')
                    ->modalSubmitActionLabel('Konfirmasi Lunas')
                    ->modalSubmitAction(fn ($action) => $action->color('success')->icon('heroicon-o-check-circle'))
                    ->form(fn ($record) => [
                        Forms\Components\Placeholder::make('payment_proof_preview')
                            ->label('Bukti Bayar')
                            ->content(function () use ($record) {
                                if (!$record->payment_proof) return 'Tidak ada bukti bayar.';

                                $url = Storage::url($record->payment_proof);
                                $ext = strtolower(pathinfo($record->payment_proof, PATHINFO_EXTENSION));

                                if ($ext === 'pdf') {
                                    return new \Illuminate\Support\HtmlString(
                                        '<iframe src="' . $url . '" class="w-full rounded-lg border border-gray-200" style="height: 400px;"></iframe>' .
                                        '<a href="' . $url . '" target="_blank" class="inline-flex items-center gap-1 mt-2 text-sm text-primary-600 hover:underline">Buka di tab baru ↗</a>'
                                    );
                                }

                                return new \Illuminate\Support\HtmlString(
                                    '<img src="' . $url . '" class="rounded-lg max-h-96 w-full object-contain border border-gray-200" />'
                                );
                            }),
                        Forms\Components\Placeholder::make('invoice_info')
                            ->label('Detail Invoice')
                            ->content(fn () => new \Illuminate\Support\HtmlString(
                                '<div class="text-sm space-y-1">' .
                                '<p><strong>Billing Code:</strong> ' . $record->billing_code . '</p>' .
                                '<p><strong>Amount:</strong> Rp ' . number_format($record->amount, 0, ',', '.') . '</p>' .
                                '<p><strong>Due Date:</strong> ' . ($record->due_date ? $record->due_date->format('d M Y H:i') : '-') . '</p>' .
                                '</div>'
                            )),
                    ])
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'paid',
                            'verified_at' => now(),
                        ]);
                        $record->booking->update(['status' => 'approved']);
                        Notification::make()->title('Pembayaran dikonfirmasi')->success()->send();
                    })
                    ->extraModalFooterActions([
                        Tables\Actions\Action::make('reject')
                            ->label('Tolak')
                            ->color('danger')
                            ->icon('heroicon-o-x-circle')
                            ->requiresConfirmation()
                            ->modalHeading('Tolak Pembayaran?')
                            ->modalDescription('Apakah Anda yakin ingin menolak pembayaran ini?')
                            ->action(function ($record) {
                                $record->update([
                                    'status' => 'cancelled',
                                ]);
                                Notification::make()->title('Pembayaran ditolak')->warning()->send();
                            }),
                    ])
                    ->visible(fn ($record) => $record->status === 'unpaid' && !$record->is_expired && $record->payment_proof),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    private static function terbilang(int $angka): string
    {
        $angka = abs($angka);
        $satuan = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];

        if ($angka < 12) {
            return $satuan[$angka];
        } elseif ($angka < 20) {
            return self::terbilang($angka - 10) . ' Belas';
        } elseif ($angka < 100) {
            return self::terbilang(intdiv($angka, 10)) . ' Puluh' . ($angka % 10 ? ' ' . self::terbilang($angka % 10) : '');
        } elseif ($angka < 200) {
            return 'Seratus' . ($angka - 100 ? ' ' . self::terbilang($angka - 100) : '');
        } elseif ($angka < 1000) {
            return self::terbilang(intdiv($angka, 100)) . ' Ratus' . ($angka % 100 ? ' ' . self::terbilang($angka % 100) : '');
        } elseif ($angka < 2000) {
            return 'Seribu' . ($angka - 1000 ? ' ' . self::terbilang($angka - 1000) : '');
        } elseif ($angka < 1000000) {
            return self::terbilang(intdiv($angka, 1000)) . ' Ribu' . ($angka % 1000 ? ' ' . self::terbilang($angka % 1000) : '');
        } elseif ($angka < 1000000000) {
            return self::terbilang(intdiv($angka, 1000000)) . ' Juta' . ($angka % 1000000 ? ' ' . self::terbilang($angka % 1000000) : '');
        } elseif ($angka < 1000000000000) {
            return self::terbilang(intdiv($angka, 1000000000)) . ' Miliar' . ($angka % 1000000000 ? ' ' . self::terbilang($angka % 1000000000) : '');
        } else {
            return self::terbilang(intdiv($angka, 1000000000000)) . ' Triliun' . ($angka % 1000000000000 ? ' ' . self::terbilang($angka % 1000000000000) : '');
        }
    }
}
