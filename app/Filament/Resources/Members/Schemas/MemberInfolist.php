<?php

namespace App\Filament\Resources\Members\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MemberInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Grid::make(3)->schema([
                    // --- GRUP KIRI: PROFIL UTAMA ---
                    Section::make('Informasi Profil Member')
                        ->schema([
                            ImageEntry::make('user.photo_path')->label('')
                                ->circular()->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->user->name ?? 'M')),
                            TextEntry::make('user.name')->label('Nama Lengkap')->weight('bold'),
                            TextEntry::make('user.email')->label('Email')->copyable()->icon('heroicon-o-envelope'),
                            TextEntry::make('user.phone')->label('Nomor Telepon')->copyable()->icon('heroicon-o-phone'),
                            TextEntry::make('user.birth_date')->label('Tanggal Lahir')->date('d F Y')->icon('heroicon-o-cake'),
                            TextEntry::make('user.gender')->label('Jenis Kelamin')->badge()
                                ->formatStateUsing(fn (string $state): string => match ($state) {
                                    'MALE' => 'Laki-laki',
                                    'FEMALE' => 'Perempuan',
                                    default => 'Tidak Diketahui',
                                }),
                        ])->columns(2)->columnSpan(2),

                    // --- GRUP KANAN: STATUS & PAKET ---
                    Section::make('Status Keanggotaan')
                        ->schema([
                            TextEntry::make('trainingPackage.name')->label('Paket Latihan')
                                ->badge()->color('success')->default('Belum ada paket'),

                            TextEntry::make('status')->label('Status Member')
                                ->badge()
                                ->color(fn (string $state): string => match ($state) {
                                    'AKTIF' => 'success',
                                    'TIDAK_AKTIF' => 'danger',
                                    default => 'gray',
                                }),

                            TextEntry::make('start_date')->label('Terdaftar Sejak')->date('d F Y'),
                            TextEntry::make('end_date')->label('Berakhir Pada')->date('d F Y')->default('-'),
                        ])->columnSpan(1),

                    // --- BAGIAN BAWAH: RIWAYAT SISTEM ---
                    Section::make('Riwayat Sistem')
                        ->schema([
                            TextEntry::make('user.created_at')->label('Akun Dibuat')->dateTime('d M Y, H:i'),
                            TextEntry::make('created_at')->label('Data Member Dibuat')->dateTime('d M Y, H:i'),
                            TextEntry::make('user.updated_at')->label('Akun Diperbarui')->dateTime('d M Y, H:i'),
                            TextEntry::make('updated_at')->label('Data Member Diperbarui')->dateTime('d M Y, H:i'),
                        ])->columns(2)->collapsible()->columnSpanFull(),
                ]),
            ]);
    }
}
