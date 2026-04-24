<?php

namespace App\Filament\Resources\PengaturanAdmins\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PengaturanAdminsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('coach.user.name')
                    ->label('Nama Coach')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('trainingSchedule.day')
                    ->label('Hari')
                    ->formatStateUsing(fn (string $state): string => match (strtoupper($state)) {
                        'MONDAY', 'SENIN' => 'Senin',
                        'TUESDAY', 'SELASA' => 'Selasa',
                        'WEDNESDAY', 'RABU' => 'Rabu',
                        'THURSDAY', 'KAMIS' => 'Kamis',
                        'FRIDAY', 'JUMAT' => 'Jumat',
                        'SATURDAY', 'SABTU' => 'Sabtu',
                        'SUNDAY', 'MINGGU' => 'Minggu',
                        default => $state,
                    })
                    ->sortable(),

                TextColumn::make('trainingSchedule.time')
                    ->label('Jam')
                    ->sortable(),

                TextColumn::make('trainingSchedule.place')
                    ->label('Tempat'),

                TextColumn::make('quota')
                    ->label('Kuota')
                    ->alignCenter(),

                TextColumn::make('usage_count')
                    ->label('Terisi')
                    ->state(function ($record) {
                        return \App\Models\Member::whereHas('coaches', function ($query) use ($record) {
                            $query->where('coaches.id', $record->coach_id);
                        })->count();
                    })
                    ->alignCenter()
                    ->color(fn ($state, $record) => $state >= $record->quota ? 'danger' : 'success'),
            ])
            ->defaultSort('coach_id');
    }
}
