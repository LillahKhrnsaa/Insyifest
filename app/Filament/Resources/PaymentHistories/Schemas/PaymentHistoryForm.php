<?php

namespace App\Filament\Resources\PaymentHistories\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use App\Models\Member;
use Filament\Tables\Columns\SelectColumn;

class PaymentHistoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Detail Pembayaran')
                    ->schema([
                        Select::make('member_id')
                            ->relationship('member', titleAttribute: 'user_name')
                            ->getOptionLabelFromRecordUsing(fn (Member $record) => $record->user->name)
                            ->searchable(['user_name'])
                            ->preload()
                            ->required()
                            ->label('Nama Member'),
                        
                        Grid::make(2)
                            ->schema([
                                TextInput::make('amount')
                                    ->required()
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->label('Jumlah Pembayaran'),

                                Select::make('status')
                                    ->options([
                                        'TERKONFIRMASI' => 'Terkonfirmasi',
                                        'PENDING'        => 'Pending',
                                        'GAGAL'          => 'Gagal',
                                    ])
                                    ->afterStateUpdated(function ($state, $record) {
                                        // Pastikan record ada
                                        if (! $record) {
                                            return;
                                        }

                                        $member = $record->member;

                                        // Pastikan ada relasi member dan user
                                        if (! $member || ! $member->user) {
                                            return;
                                        }

                                        // Jalankan hanya kalau pending_active == false
                                        if (isset($record->pending_active) && $record->pending_active === true) {
                                            return;
                                        }

                                        // Tentukan nilai active berdasarkan status
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

                                        // (opsional) log debugging
                                        // \Log::info('form-status-afterStateUpdated', [
                                        //     'id' => $record->id,
                                        //     'status' => $state,
                                        //     'pending_active' => $record->pending_active ?? null,
                                        // ]);
                                    })
                            ->required()
                            ->native(false) // Opsi untuk style dropdown
                            ->label('Status'),

                        
                        DateTimePicker::make('payment_date')
                            ->required()
                            ->default(now())
                            ->label('Tanggal Pembayaran'),

                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->columnSpanFull(),

                        FileUpload::make('proof_path')
                            ->label('Bukti Pembayaran')
                            ->directory('payment-proofs') // Simpan di storage/app/public/payment-proofs
                            ->image()
                            ->imageEditor()
                            ->columnSpanFull(),
                        ])
                    ])->columnSpanFull()
            ]);
    }
}
