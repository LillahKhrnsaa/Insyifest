<?php

namespace App\Filament\Resources\FormEksternals\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FormEksternalInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(1)
                ->schema([
                    // Kolom utama untuk informasi form, mengambil 2/3 bagian
                    Section::make('Informasi Form')
                        ->schema([
                            // Membuat grid di dalam section untuk menata judul dan link
                            Grid::make(2)->schema([
                                TextEntry::make('title')
                                    ->label('Judul Form')
                                    ->icon('heroicon-o-clipboard-document-list')
                                    ->weight('bold')
                                    ->color('primary'),

                                TextEntry::make('slug')
                                    ->label('Tautan Publik')
                                    ->icon('heroicon-o-link')
                                    ->color('info')
                                    ->copyable()
                                    ->copyMessage('Link berhasil disalin!')
                                    ->copyMessageDuration(1500)
                                    ->url(fn ($record) => url('/form/' . $record->slug), true),
                            ]),

                            TextEntry::make('description')
                                ->label('Deskripsi')
                                ->icon('heroicon-o-document-text')
                                ->placeholder('Tidak ada deskripsi.')
                                // Mengambil satu baris penuh
                                ->columnSpanFull(),
                        ])
                        ->columnSpan(2), // Section ini menggunakan 2 dari 3 kolom

                    // Kolom samping untuk metadata, mengambil 1/3 bagian
                    Section::make('Metadata')
                        ->schema([
                            TextEntry::make('status')
                                ->badge()
                                ->label('Status')
                                ->icon(fn ($state) => $state === 'ACTIVE' ? 'heroicon-s-check-circle' : 'heroicon-s-x-circle')
                                ->colors([
                                    'success' => 'ACTIVE',
                                    'danger' => 'INACTIVE',
                                ]),
                            TextEntry::make('created_at')
                                ->label('Dibuat Pada')
                                ->icon('heroicon-o-calendar-days')
                                ->dateTime('d M Y, H:i')
                                ->placeholder('-'),

                            TextEntry::make('updated_at')
                                ->label('Terakhir Diperbarui')
                                ->icon('heroicon-o-arrow-path')
                                ->dateTime('d M Y, H:i')
                                ->placeholder('-'),
                        ])
                        ->columns(3),
                ])->columnSpanFull(),
            ]);
    }
}
