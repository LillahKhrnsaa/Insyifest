<?php

namespace App\Filament\Widgets;

use App\Models\GeneralMaterial;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PostWidget extends BaseWidget
{
    public function getColumnSpan(): int|array|string
    {
        return 1; // atau 'full', 'half', 2, dsb.
    }

    protected function getStats(): array
    {
        $total = GeneralMaterial::count();
        $active = GeneralMaterial::where('status', 'active')->count();

        return [
            Stat::make('Postingan', "{$total} Total")
                ->description("{$active} aktif")
                ->icon('heroicon-o-document-text')
                ->color('info'),
        ];
    }
}
