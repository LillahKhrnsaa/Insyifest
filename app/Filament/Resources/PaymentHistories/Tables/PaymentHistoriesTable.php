<?php

namespace App\Filament\Resources\PaymentHistories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Tables\Columns\SelectColumn;

class PaymentHistoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('member.user.name')
                    ->label('Nama Member')
                    ->searchable(isIndividual: true)
                    ->sortable(),

                TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR')
                    ->sortable(),

                SelectColumn::make('status')
                    ->options([
                        'TERKONFIRMASI' => 'Terkonfirmasi',
                        'PENDING'        => 'Pending',
                        'GAGAL'          => 'Gagal',
                    ])
                    ->afterStateUpdated(function ($record, $state) {
                        // Pastikan $record adalah model (Filament biasanya mengirim model)
                        if (! $record) {
                            return;
                        }

                        // Ambil relasi member (bisa null)
                        $member = $record->member;

                        if (! $member || ! $member->user) {
                            // Tidak ada member / user -> nothing to do
                            return;
                        }

                        // Jika ada kolom pending_active dan nilainya true -> skip
                        // (sesuai permintaan: jalankan hanya jika pending_active == false)
                        if (isset($record->pending_active) && $record->pending_active === true) {
                            return;
                        }

                        // Mapping status ke nilai active
                        switch ($state) {
                            case 'TERKONFIRMASI':
                                $active = true;
                                break;
                            case 'PENDING':
                            case 'GAGAL':
                            default:
                                $active = false;
                                break;
                        }

                        // Update user
                        $member->user->update(['active' => $active]);

                        // (opsional) log/debug:
                        // \Log::info('status-afterStateUpdated', ['id' => $record->id, 'status' => $state, 'pending_active' => $record->pending_active ?? null]);
                    }),

                TextColumn::make('payment_date')
                    ->label('Tgl. Pembayaran')
                    ->date('d M Y')
                    ->sortable(),
                
                IconColumn::make('proof_path')
                    ->label('Bukti')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'PENDING' => 'Pending',
                        'TERKONFIRMASI' => 'Terkonfirmasi',
                        'GAGAL' => 'Gagal',
                    ]),
                
                SelectFilter::make('member_id')
                    ->label('Member')
                    ->relationship('member.user', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('')
                    ->button()
                    ->tooltip('View details')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->size('sm')
                    ->extraAttributes([
                        'class' => 'border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 rounded-lg px-3 py-2']),

                EditAction::make()
                    ->label('')
                    ->button()
                    ->tooltip('Edit')
                    ->icon('heroicon-o-pencil-square')
                    ->color('primary')
                    ->size('sm')
                    ->extraAttributes([
                        'class' => 'border border-blue-300 text-blue-700 bg-white hover:bg-blue-50 rounded-lg px-3 py-2']),

                DeleteAction::make()
                    ->label('')
                    ->button()
                    ->tooltip('Delete')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->size('sm')
                    ->requiresConfirmation()
                    ->extraAttributes([
                        'class' => 'border border-red-300 text-red-700 bg-white hover:bg-red-50 rounded-lg px-3 py-2'])
                    ->requiresConfirmation(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
