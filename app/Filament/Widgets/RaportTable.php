<?php

namespace App\Filament\Widgets;

use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Raport;
use Filament\Tables\Columns\TextColumn;

class RaportTable extends TableWidget
{
    // Properties untuk filter
    public ?int $memberId = null;
    public ?string $gaya = 'gaya_bebas_50';
    public ?int $year = null;

    protected static ?string $heading = 'Detail Data Raport';

    public function mount(?int $memberId = null, ?string $gaya = null, ?int $year = null): void
    {
        $this->memberId = $memberId;
        $this->gaya = $gaya ?? 'gaya_bebas_50';
        $this->year = $year ?? now()->year;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function (): Builder {
                if (!$this->memberId) {
                    return Raport::query()->whereRaw('1 = 0'); // Return empty
                }

                return Raport::query()
                    ->where('member_id', $this->memberId)
                    ->where('gaya', $this->gaya)
                    ->where('year', $this->year)
                    ->with(['coach.user'])
                    ->orderByRaw("CASE month 
                        WHEN 'januari' THEN 1 
                        WHEN 'februari' THEN 2 
                        WHEN 'maret' THEN 3 
                        WHEN 'april' THEN 4 
                        WHEN 'mei' THEN 5 
                        WHEN 'juni' THEN 6 
                        WHEN 'juli' THEN 7 
                        WHEN 'agustus' THEN 8 
                        WHEN 'september' THEN 9 
                        WHEN 'oktober' THEN 10 
                        WHEN 'november' THEN 11 
                        WHEN 'desember' THEN 12 
                        ELSE 13 END");
            })
            ->columns([
                TextColumn::make('month')
                    ->label('Bulan')
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->color('primary')
                    ->badge()
                    ->sortable(),

                TextColumn::make('value')
                    ->label('Waktu')
                    ->formatStateUsing(function ($state) {
                        $minutes = floor($state / 60);
                        $seconds = $state - ($minutes * 60);
                        return sprintf('%02d:%05.2f', $minutes, $seconds);
                    })
                    ->icon('heroicon-m-clock')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('volume')
                    ->label('Volume')
                    ->suffix(' meter')
                    ->sortable()
                    ->searchable()
                    ->alignEnd(),

                TextColumn::make('intensity')
                    ->label('Intensitas')
                    ->sortable()
                    ->alignEnd()
                    ->badge()
                    ->color(fn ($state) => match(true) {
                        $state >= 80 => 'success',
                        $state >= 60 => 'warning',
                        default => 'danger',
                    }),

                TextColumn::make('peaking')
                    ->label('Peaking')
                    ->sortable()
                    ->alignEnd(),

                TextColumn::make('coach.user.name')
                    ->label('Coach')
                    ->default('-')
                    ->searchable()
                    ->icon('heroicon-m-user'),

                TextColumn::make('note')
                    ->label('Catatan')
                    ->limit(30)
                    ->tooltip(fn ($state) => $state)
                    ->default('-')
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->paginated(false) // Tampilkan semua data (max 12 bulan)
            ->striped()
            ->defaultSort('month');
    }
}