<?php

namespace App\Filament\Widgets;

use App\Models\Coach;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CoachWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Coach', Coach::count() . ' Orang')
                ->icon('heroicon-o-user-group')
                ->color('info'),
        ];
    }
}
