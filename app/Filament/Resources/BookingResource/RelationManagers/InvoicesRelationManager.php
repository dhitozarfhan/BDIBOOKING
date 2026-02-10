<?php

namespace App\Filament\Resources\BookingResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                    ->default(fn () => $this->getOwnerRecord()->bookable?->price),
                Forms\Components\Select::make('status')
                    ->options([
                        'unpaid' => 'Unpaid',
                        'paid' => 'Paid',
                        'expired' => 'Expired',
                        'cancelled' => 'Cancelled',
                    ])
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
                    ->required(),
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
                Tables\Columns\TextColumn::make('payment_proof')
                    ->label('Bukti Bayar')
                    ->formatStateUsing(fn ($state) => $state ? 'Lihat Bukti' : '-')
                    ->url(fn ($record) => $record->payment_proof ? \Illuminate\Support\Facades\Storage::url($record->payment_proof) : null)
                    ->openUrlInNewTab()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('due_date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'paid',
                            'verified_at' => now(),
                        ]);
                        // Update booking status
                        $record->booking->update(['status' => 'approved']);
                    })
                    ->visible(fn ($record) => $record->status === 'unpaid' && !$record->is_expired && $record->payment_proof),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
