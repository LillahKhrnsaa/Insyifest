<?php

namespace App\Filament\Resources\PaymentHistories\Schemas;


use Filament\Infolists\Components\BadgeEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PaymentHistoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
    return $schema
        ->schema([
            Section::make('Informasi Pembayaran')
                ->schema([
                    Grid::make(2) // Membuat layout 2 kolom
                        ->schema([
                            TextEntry::make('member.user.name')
                                ->label('Nama Member'),

                            TextEntry::make('amount')
                                ->label('Jumlah Pembayaran')
                                ->money('IDR') // Format sebagai mata uang Rupiah
                                ->size('lg'),

                            TextEntry::make('status')
                                ->badge()
                                ->colors([
                                    'success' => 'TERKONFIRMASI',
                                    'warning' => 'PENDING',
                                    'danger' => 'GAGAL',
                                ])
                                ->formatStateUsing(fn ($state) => ucfirst(strtolower($state))), // Mengubah 'PENDING' -> 'Pending'

                            TextEntry::make('payment_date')
                                ->label('Tanggal Pembayaran')
                                ->dateTime('d M Y H:i'), // Format tanggal agar mudah dibaca
                        ]),
                    
                    TextEntry::make('description')
                        ->label('Deskripsi')
                        ->columnSpanFull(), // Deskripsi mengambil lebar penuh
                ]),
            
            Section::make('Bukti Pembayaran')
                ->schema([
                    ImageEntry::make('proof_path')
                        ->label('')
                        ->imageHeight(250)
                        ->disk('public') // Pastikan disk storage kamu benar
                        ->visible(fn ($state) => filled($state)), // Hanya tampil jika ada gambar

                    TextEntry::make('proof_path')
                        ->label('Tidak ada bukti pembayaran')
                        ->hidden(fn ($state) => filled($state)), // Tampil jika tidak ada gambar
                ]),
        ]);
    }
}
