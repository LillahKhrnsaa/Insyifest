<?php

namespace App\Filament\Resources\Coaches\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Components\Fieldset;


class CoachInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // === BAGIAN 1: INFORMASI PROFIL & AKUN (User Data) ===
                Section::make('Informasi Profil Coach')
                    ->description('Detail dasar dan kontak coach.')
                    ->columns(3) // Gunakan 3 kolom untuk layout foto dan data utama
                    ->schema([
                        // 1. FOTO PROFIL (Image Entry)
                        ImageEntry::make('user.photo_path') // Akses melalui relasi user
                            ->label('Foto Profil')
                            ->disk('public') 
                            ->circular() 
                            ->imageHeight(100)
                            ->imageWidth(100)
                            ->alignment('center')
                            ->columnSpan(1)
                            // Placeholder avatar mengambil gender dari relasi user
                            ->placeholder(fn ($record) => $record->user?->gender === 'FEMALE' ? 'https://ui-avatars.com/api/?name=F&color=FFF&background=E57373' : 'https://ui-avatars.com/api/?name=M&color=FFF&background=4CAF50'),

                        // 2. NAMA & EMAIL & ROLE
                        Fieldset::make('Data Utama')
                            ->columns(1)
                            ->columnSpan(2)
                            ->schema([
                                TextEntry::make('user.name') // Akses user.name
                                    ->label('Nama Lengkap')
                                    ->color('primary')
                                    ->weight('bold'),

                                TextEntry::make('user.email') // Akses user.email
                                    ->color('secondary')
                                    ->copyable()
                                    ->label('Email')
                                    ->icon('heroicon-o-at-symbol'),
                                    
                                TextEntry::make('user.roles') // Akses user.roles (relasi dari model User)
                                    ->label('Role')
                                    ->badge()
                                    ->color('danger')
                                    ->icon('heroicon-o-shield-check')
                                    ->formatStateUsing(function ($state, $record) {
                                        // Akses roles melalui relasi user
                                        return $record->user->roles->pluck('name')->map(fn($role) => ucfirst($role))->join(', ') ?: 'Coach';
                                    })
                                    ->tooltip('Role dan permission coach.'),
                            ]),
                    ])->columnSpanFull()->collapsible(),

                // ---

                // === BAGIAN 2: DETAIL KONTAK & STATUS (User Data) ===
                Section::make('Detail Kontak & Status')
                    ->description('Informasi kontak dan status akun coach.')
                    ->columns(3)
                    ->schema([
                        // 3. TELEPON
                        TextEntry::make('user.phone') // Akses user.phone
                            ->label('Nomor Telepon')
                            ->icon('heroicon-o-phone')
                            ->placeholder('-'),

                        // 4. TANGGAL LAHIR
                        TextEntry::make('user.birth_date') // Akses user.birth_date
                            ->label('Tanggal Lahir')
                            ->date('d M Y')
                            ->icon('heroicon-o-cake')
                            ->placeholder('-'),

                        // 5. JENIS KELAMIN
                        TextEntry::make('user.gender') // Akses user.gender
                            ->label('Jenis Kelamin')
                            ->icon(fn ($state) => $state === 'FEMALE' ? 'heroicon-o-face-frown' : 'heroicon-o-face-smile')
                            ->badge()
                            ->color(fn ($state) => $state === 'FEMALE' ? 'danger' : 'success')
                            ->placeholder('Belum diisi'),

                        // 6. STATUS AKTIF (Badge)
                        TextEntry::make('user.active') // Akses user.active
                            ->badge()
                            ->label('Status Akun')
                            ->formatStateUsing(fn ($state) => $state ? 'Aktif' : 'Non-Aktif')
                            ->color(fn ($state) => $state ? 'success' : 'danger')
                            ->icon(fn ($state) => $state ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                            ->columnSpan(1),
                    ])->columnSpanFull()->columns(2),
                
                // ---

                // === BAGIAN 3: BIO COACH (Coach Data) ===
                Section::make('Keahlian Coach')
                    ->description('Biografi dan detail keahlian spesifik coach.')
                    ->icon('heroicon-o-academic-cap')
                    ->schema([
                        TextEntry::make('bio') // Akses kolom 'bio' langsung dari model Coach
                            ->label('Biografi Coach')
                            ->placeholder('Biografi belum diisi.')
                            ->columnSpanFull()
                            ->markdown(), // Gunakan markdown untuk tampilan bio yang lebih rapi
                    ])->columnSpanFull(),

                // ---

                // === BAGIAN 4: RIWAYAT AKUN (Meta Data) ===
                Section::make('Riwayat Coach & Akun')
                    ->description('Informasi tentang pembuatan dan pembaruan data coach dan akun.')
                    ->columns(4)
                    ->schema([
                        TextEntry::make('user.created_at')
                            ->label('Akun Dibuat')
                            ->dateTime('d M Y, H:i')
                            ->icon('heroicon-o-user-plus'),

                        TextEntry::make('created_at') // Tanggal pembuatan record Coach
                            ->label('Data Coach Dibuat')
                            ->dateTime('d M Y, H:i')
                            ->icon('heroicon-o-calendar-days'),
                        
                        TextEntry::make('user.updated_at')
                            ->label('Akun Terakhir Diperbarui')
                            ->dateTime('d M Y, H:i')
                            ->icon('heroicon-o-clock'),

                        TextEntry::make('updated_at') // Tanggal update record Coach
                            ->label('Data Coach Terakhir Diperbarui')
                            ->dateTime('d M Y, H:i')
                            ->icon('heroicon-o-clock'),
                    ])->columnSpanFull(),
            ]);
    }
}
