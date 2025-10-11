<?php

namespace App\Filament\Resources\TrainingSchedules\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;

class TrainingScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Jadwal Latihan')
                    ->description('Tentukan hari, waktu, dan lokasi untuk jadwal latihan ini.') // Gunakan 3 kolom untuk Hari, Waktu, Tempat
                    ->schema([
                        // 1. HARI LATIHAN (Menggunakan Select)
                        Select::make('day')
                            ->label('Hari Latihan')
                            ->options([
                                'SENIN' => 'Senin',
                                'SELASA' => 'Selasa',
                                'RABU' => 'Rabu',
                                'KAMIS' => 'Kamis',
                                'JUMAT' => 'Jumat',
                                'SABTU' => 'Sabtu',
                                'MINGGU' => 'Minggu',
                            ])
                            ->required()
                            ->placeholder('Pilih hari latihan')
                            ->prefixIcon('heroicon-o-calendar-days')
                            ->columnSpan(1),

                        // 2. WAKTU MULAI LATIHAN (TimePicker)
                        TimePicker::make('time')
                            ->label('Waktu Mulai Latihan')
                            ->required()
                            ->placeholder('09:00')
                            ->seconds(false) // Tidak perlu detik
                            ->displayFormat('H:i') // Format jam
                            ->prefixIcon('heroicon-o-clock')
                            ->columnSpan(1),
                            
                        // 3. TEMPAT LATIHAN (TextInput)
                        TextInput::make('place')
                            ->label('Tempat Latihan (Kolam)')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Kolam Renang Cikamepak')
                            ->prefixIcon('heroicon-o-map-pin')
                            ->columnSpan(1),
                    ])->columnSpanFull()->columns(2),
            ]);
    }
}
