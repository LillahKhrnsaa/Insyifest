<?php

namespace App\Filament\Widgets;

use App\Models\Coach;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class AttendanceSummaryTable extends TableWidget
{
    // Properti untuk menangkap filter dari modal header
    public ?string $month = null;
    public ?string $year = null;

    protected static ?string $heading = 'Rekapitulasi Kehadiran Coach';

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                // Query dasar ke Coach
                return Coach::query()
                    ->with(['user'])
                    ->withCount(['attendances' => function (Builder $query) {
                        // Filter dinamis berdasarkan pilihan di modal
                        if ($this->month) {
                            $query->whereMonth('date', $this->month);
                        }
                        if ($this->year) {
                            $query->whereYear('date', $this->year);
                        }
                    }]);
            })
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama Coach')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('attendances_count')
                    ->label('Total Sesi Latihan')
                    ->badge()
                    ->color('info')
                    ->alignCenter()
                    ->suffix(' Kali')
                    ->sortable(),

                TextColumn::make('user.phone')
                    ->label('Kontak')
                    ->description(fn($record) => $record->phone ?? '-'),
            ])
            ->striped()
            ->paginated(false);
    }
}
