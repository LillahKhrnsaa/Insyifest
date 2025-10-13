<?php

namespace App\Filament\Widgets;

use App\Models\TrainingPackage;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TrainingPackageWidget extends BaseWidget
{
    public function getColumnSpan(): int|array|string
    {
        return 1; // atau 'full', 'half', 2, dsb.
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Paket Latihan', TrainingPackage::count() . ' Paket')
                ->icon('heroicon-o-rectangle-stack')
                ->color('warning'),
        ];
    }
}
