<?php

namespace App\Filament\Widgets;

use App\Models\TrainingPackage;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TrainingPackageWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Paket Latihan', TrainingPackage::count() . ' Paket')
                ->icon('heroicon-o-rectangle-stack')
                ->color('warning'),
        ];
    }
}
