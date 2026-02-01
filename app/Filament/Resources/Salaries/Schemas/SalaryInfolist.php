<?php

namespace App\Filament\Resources\Salaries\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Infolists\Components\Split;

class SalaryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                 Section::make('Informasi Pelatih')
                    ->icon('heroicon-o-user-circle')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('coach.user.name')
                                    ->label('Nama Pelatih')
                                    ->icon('heroicon-o-user')
                                    ->weight('bold')
                                    ->color('primary')
                                    ->size('lg'),

                                TextEntry::make('coach.user.email')
                                    ->label('Email')
                                    ->icon('heroicon-o-envelope')
                                    ->copyable()
                                    ->color('gray'),

                                TextEntry::make('month')
                                    ->label('Periode Gaji')
                                    ->icon('heroicon-o-calendar')
                                    ->badge()
                                    ->color('info'),

                                TextEntry::make('member_count_display')
                                    ->label('Total Atlet')
                                    ->icon('heroicon-o-user-group')
                                    ->badge()
                                    ->color('success')
                                    ->state(function ($record) {
                                        $original = $record->coach?->members()->count() ?? 0;

                                        $additionalData = $record->additional_athletes;

                                        $additionalCount = 0;
                                        
                                        if (is_array($additionalData)) {
                                            $additionalCount = count($additionalData);
                                        } elseif (is_string($additionalData) && !empty($additionalData)) {
                                            $decoded = json_decode($additionalData, true);
                                            $additionalCount = is_array($decoded) ? count($decoded) : 0;
                                        }

                                        $total = $original + $additionalCount;

                                        return "{$total} Orang ({$original} Binaan + {$additionalCount} Tambahan)";
                                    }),

                                TextEntry::make('additional_athletes')
                                    ->label('Daftar Atlet Tambahan')
                                    ->icon('heroicon-o-users')
                                    ->badge()
                                    ->color('warning')
                                    ->separator(',')
                                    ->visible(fn ($record) => !empty($record->additional_athletes))
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->collapsible(),

                Section::make('Komponen Gaji')
                    ->icon('heroicon-o-calculator')
                    ->description('Rincian perhitungan gaji pelatih')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('training_sessions')
                                    ->label('Jumlah Pertemuan')
                                    ->icon('heroicon-o-academic-cap')
                                    ->numeric()
                                    ->suffix(' Pertemuan'),

                                TextEntry::make('per_meeting_fee')
                                    ->label('Nominal per Pertemuan')
                                    ->icon('heroicon-o-banknotes')
                                    ->money('IDR')
                                    ->color('info'),

                                TextEntry::make('per_member_fee')
                                    ->label('Nominal per Atlet')
                                    ->icon('heroicon-o-currency-dollar')
                                    ->money('IDR')
                                    ->color('info'),

                                TextEntry::make('transport_fee')
                                    ->label('Uang Transport')
                                    ->icon('heroicon-o-truck')
                                    ->money('IDR')
                                    ->color('warning'),

                                TextEntry::make('health_fee')
                                    ->label('Uang Kesehatan')
                                    ->icon('heroicon-o-heart')
                                    ->money('IDR')
                                    ->color('danger'),

                                TextEntry::make('bonus')
                                    ->label('Bonus Tambahan')
                                    ->icon('heroicon-o-gift')
                                    ->money('IDR')
                                    ->color('success'),
                            ]),
                    ])
                    ->collapsible(),

                Section::make('Total Gaji')
                    ->icon('heroicon-o-calculator')
                    ->schema([
                        TextEntry::make('calculation_detail')
                            ->label('Rincian Perhitungan')
                            ->state(function ($record) {
                                $original = $record->coach?->members()->count() ?? 0;
                                $additional = is_array($record->additional_athletes) ? count($record->additional_athletes) : 0;
                                $totalMembers = $original + $additional;
                                
                                $meetingTotal = $record->training_sessions * $record->per_meeting_fee;
                                $memberTotal = $totalMembers * $record->per_member_fee;
                                
                                return "
                                    <div style='line-height: 1.8;'>
                                        <div>🚗 Transport: <strong>Rp " . number_format($record->transport_fee, 0, ',', '.') . "</strong></div>
                                        <div>🏋️ Pertemuan: {$record->training_sessions} × Rp " . number_format($record->per_meeting_fee, 0, ',', '.') . " = <strong>Rp " . number_format($meetingTotal, 0, ',', '.') . "</strong></div>
                                        <div>👥 Atlet: <strong>{$totalMembers}</strong> ({$original} + {$additional}) × Rp " . number_format($record->per_member_fee, 0, ',', '.') . " = <strong>Rp " . number_format($memberTotal, 0, ',', '.') . "</strong></div>
                                        <div>❤️ Kesehatan: <strong>Rp " . number_format($record->health_fee, 0, ',', '.') . "</strong></div>
                                        <div>🎁 Bonus: <strong>Rp " . number_format($record->bonus, 0, ',', '.') . "</strong></div>
                                    </div>
                                ";
                            })
                            ->html()
                            ->columnSpanFull(),

                        TextEntry::make('total_amount')
                            ->label('💵 Total Gaji')
                            ->money('IDR')
                            ->size('xl')
                            ->weight('bold')
                            ->color('success')
                            ->columnSpanFull(),
                    ])
                    ->collapsed(false),

                Section::make('Status Pembayaran')
                    ->icon('heroicon-o-check-badge')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('status')
                                    ->label('Status')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'pending' => 'warning',
                                        'paid' => 'success',
                                        default => 'gray',
                                    })
                                    ->formatStateUsing(fn (string $state): string => match ($state) {
                                        'pending' => 'Belum Dibayar',
                                        'paid' => 'Sudah Dibayar',
                                        default => $state,
                                    })
                                    ->icon(fn (string $state): string => match ($state) {
                                        'pending' => 'heroicon-o-clock',
                                        'paid' => 'heroicon-o-check-circle',
                                        default => 'heroicon-o-question-mark-circle',
                                    }),

                                TextEntry::make('paid_at')
                                    ->label('Tanggal Pembayaran')
                                    ->icon('heroicon-o-calendar-days')
                                    ->date('d F Y')
                                    ->placeholder('—')
                                    ->visible(fn ($record) => $record->paid_at !== null),
                            ]),
                    ])
                    ->collapsible(),

                Section::make('Informasi Sistem')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label('Dibuat Pada')
                                    ->icon('heroicon-o-clock')
                                    ->dateTime('d F Y, H:i')
                                    ->color('gray'),

                                TextEntry::make('updated_at')
                                    ->label('Terakhir Diupdate')
                                    ->icon('heroicon-o-arrow-path')
                                    ->dateTime('d F Y, H:i')
                                    ->color('gray'),
                            ]),
                    ])
                    ->collapsed(true)
                    ->collapsible(),
            ]);
    }
}