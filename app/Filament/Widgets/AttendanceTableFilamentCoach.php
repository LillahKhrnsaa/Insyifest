<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AttendanceTableFilamentCoach extends TableWidget
{
    public $coachId;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Attendance::query()->where('coach_id', $this->coachId)->latest('date')
            )
            ->columns([
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->dateTime('d M Y')
                    ->sortable(),

                TextColumn::make('date_day')
                    ->label('Hari')
                    ->state(fn ($record) => \Carbon\Carbon::parse($record->date)->isoFormat('dddd')),

                TextColumn::make('time')
                    ->label('Jam')
                    ->dateTime('H:i')
                    ->placeholder('--:--'),

                TextColumn::make('place')
                    ->label('Tempat')
                    ->limit(20),

                // Menghitung Atlet Binaan
                TextColumn::make('binaan_count')
                    ->label('Atlet Binaan')
                    ->state(function ($record) {
                        // Ambil daftar ID member yang dibina oleh coach ini
                        $binaanIds = $record->coach->members->pluck('id')->toArray();
                        // Hitung berapa banyak dari member yang hadir yang termasuk dalam binaanIds
                        return $record->members()->whereIn('members.id', $binaanIds)->count();
                    })
                    ->badge()
                    ->color('info')
                    ->suffix(' Orang'),

                // Menghitung Atlet Luar (Bukan Binaan)
                TextColumn::make('luar_count')
                    ->label('Atlet Luar')
                    ->state(function ($record) {
                        $binaanIds = $record->coach->members->pluck('id')->toArray();
                        return $record->members()->whereNotIn('members.id', $binaanIds)->count();
                    })
                    ->badge()
                    ->color('warning')
                    ->suffix(' Orang'),
            ])
            ->filters([
                // Kamu bisa tambahkan filter bulan di sini jika ingin lebih spesifik
            ]);
    }
}
