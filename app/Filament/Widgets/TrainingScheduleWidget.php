<?php

namespace App\Filament\Widgets;

use App\Models\TrainingSchedule;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TrainingScheduleWidget extends BaseWidget
{
    public function getColumnSpan(): int|array|string
    {
        return 1; // atau 'full', 'half', 2, dsb.
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Jadwal Latihan', TrainingSchedule::count() . ' Jadwal')
                ->icon('heroicon-o-calendar-days')
                ->color('secondary'),
        ];
    }
}
