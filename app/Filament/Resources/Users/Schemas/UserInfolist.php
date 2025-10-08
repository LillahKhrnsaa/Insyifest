<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\BadgeEntry;
use Filament\Schemas\Components\Fieldset;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Profil Pengguna')
                    ->description('Detail dasar dan kontak pengguna.')
                    ->schema([
                        // 1. FOTO PROFIL (Image Entry)
                        ImageEntry::make('photo_path')
                            ->label('Foto Profil')
                            ->disk('public') // Asumsi storage disk adalah 'public'
                            ->circular() // Tampilan foto bulat
                            ->imageHeight(100)
                            ->imageWidth(100)
                            ->alignment('center')
                            ->columnSpan(1)
                            ->placeholder(fn ($record) => $record->gender === 'Female' ? 'https://ui-avatars.com/api/?name=F&color=FFF&background=E57373' : 'https://ui-avatars.com/api/?name=M&color=FFF&background=4CAF50'), // Placeholder avatar

                        // 2. NAMA & EMAIL (di kolom 2 & 3)
                        Fieldset::make('Data Utama')
                            ->columns(1)
                            ->columnSpan(2)
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Nama Lengkap')
                                    ->color('primary')
                                    ->weight('bold'),

                                TextEntry::make('email')
                                    ->color('secondary')
                                    ->copyable()
                                    ->label('Email')
                                    ->icon('heroicon-o-at-symbol'),
                                    
                                TextEntry::make('roles')
                                    ->label('Role')
                                    ->badge()
                                    ->color('danger')
                                    ->icon('heroicon-o-shield-check')
                                    ->formatStateUsing(function (string $state, $record) {
                                        return $record->roles->pluck('name')->map(fn($role) => ucfirst($role))->join(', ') ?: '-';
                                    })
                                    ->tooltip('Role dan permission pengguna.')
                            ])->columnSpanFull(),
                    ])->columnSpanFull()->collapsible(),

                // ---

                Section::make('Detail Kontak & Status')
                    ->description('Informasi kontak dan status akun pengguna.')
                    ->columns(3)
                    ->schema([
                        // 3. TELEPON
                        TextEntry::make('phone')
                            ->label('Nomor Telepon')
                            ->icon('heroicon-o-phone')
                            ->placeholder('-'),

                        // 4. TANGGAL LAHIR
                        TextEntry::make('birth_date')
                            ->label('Tanggal Lahir')
                            ->date('d M Y')
                            ->icon('heroicon-o-cake')
                            ->placeholder('-'),

                        // 5. JENIS KELAMIN
                        TextEntry::make('gender')
                            ->label('Jenis Kelamin')
                            ->icon(fn ($state) => $state === 'Female' ? 'heroicon-o-face-frown' : 'heroicon-o-face-smile')
                            ->badge()
                            ->color(fn ($state) => $state === 'Female' ? 'danger' : 'success')
                            ->placeholder('Belum diisi'),

                        // 6. STATUS AKTIF (Badge)
                        TextEntry::make('active')
                            ->badge()
                            ->label('Status Akun')
                            ->formatStateUsing(fn ($state) => $state ? 'Aktif' : 'Non-Aktif')
                            ->color(fn ($state) => $state ? 'success' : 'danger')
                            ->icon(fn ($state) => $state ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle'),

                        
                    ])->columnSpanFull(),

                // ---

                Section::make('Riwayat Akun')
                    ->description('Informasi tentang pembuatan dan pembaruan akun.')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Dibuat Pada')
                            ->dateTime('d M Y, H:i')
                            ->icon('heroicon-o-calendar-days'),

                        TextEntry::make('updated_at')
                            ->label('Terakhir Diperbarui')
                            ->dateTime('d M Y, H:i')
                            ->icon('heroicon-o-clock'),
                    ])->columnSpanFull(),
            ]);
    }
}
