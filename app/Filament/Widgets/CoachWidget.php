<?php

namespace App\Filament\Widgets;

use App\Models\Coach;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CoachWidget extends BaseWidget
{
    public function getColumnSpan(): int|array|string
    {
        return 1; // atau 'full', 'half', 2, dsb.
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Coach', Coach::count() . ' Orang')
                ->icon('heroicon-o-user-group')
                ->color('info'),
        ];
    }
}
