<?php

namespace App\Filament\Resources\TrainingSchedules\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class TrainingScheduleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->description('Detail jadwal latihan untuk atlet.')
                    ->icon('heroicon-o-calendar-days')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('day')
                                    ->label('Hari Latihan')
                                    ->badge()
                                    ->color(fn (string $state): string => match (strtoupper($state)) {
                                        'SENIN' => 'success',
                                        'SELASA' => 'warning',
                                        'RABU' => 'info',
                                        'KAMIS' => 'primary',
                                        'JUMAT' => 'danger',
                                        'SABTU' => 'success',
                                        'MINGGU' => 'gray',
                                        default => 'primary',
                                    })
                                    ->formatStateUsing(fn (string $state): string => match (strtoupper($state)) {
                                        'SENIN' => 'Senin',
                                        'SELASA' => 'Selasa',
                                        'RABU' => 'Rabu',
                                        'KAMIS' => 'Kamis',
                                        'JUMAT' => 'Jumat',
                                        'SABTU' => 'Sabtu',
                                        'MINGGU' => 'Minggu',
                                        default => $state,
                                    })
                                    ->icon('heroicon-m-calendar'),
                                
                                TextEntry::make('time')
                                    ->label('Waktu Mulai')
                                    ->time('H:i')
                                    ->icon('heroicon-m-clock')
                                    ->placeholder('-'),
                                    
                                TextEntry::make('place')
                                    ->label('Lokasi Kolam')
                                    ->icon('heroicon-m-map-pin')
                                    ->placeholder('-'),
                            ]),
                    ]),
                
                Section::make('Informasi Sistem')
                    ->description('Metadata mengenai jadwal ini.')
                    ->icon('heroicon-o-server')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label('Dibuat Pada')
                                    ->dateTime('d M Y, H:i')
                                    ->placeholder('-')
                                    ->icon('heroicon-m-plus-circle'),
                                    
                                TextEntry::make('updated_at')
                                    ->label('Terakhir Diperbarui')
                                    ->dateTime('d M Y, H:i')
                                    ->placeholder('-')
                                    ->icon('heroicon-m-arrow-path'),
                            ]),
                    ])
                    ->collapsed(),
            ]);
    }
}
