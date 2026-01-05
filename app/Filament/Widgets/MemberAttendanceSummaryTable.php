<?php

namespace App\Filament\Widgets;

use App\Models\Member;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MemberAttendanceSummaryTable extends TableWidget
{
    public ?string $month = null;
    public ?string $year = null;

    protected static ?string $heading = 'Rekap Kehadiran Member';

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                return Member::query()
                    ->with(['user'])
                    ->where('status', 'AKTIF') // Hanya member aktif
                    ->withCount(['attendances' => function (Builder $query) {
                        if ($this->month) {
                            $query->whereMonth('attendances.date', $this->month);
                        }
                        if ($this->year) {
                            $query->whereYear('attendances.date', $this->year);
                        }
                    }]);
            })
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama Atlet')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('attendances_count')
                    ->label('Total Hadir')
                    ->badge()
                    ->color('primary')
                    ->suffix(' Sesi')
                    ->sortable(),
            ])
            ->striped()
            ->defaultSort('attendances_count', 'desc');
    }
}
