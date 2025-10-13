<?php

namespace App\Filament\Widgets;

use App\Models\Member;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MemberWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $total = Member::count();
        $aktif = Member::where('status', 'AKTIF')->count();
        $nonaktif = Member::where('status', 'TIDAK_AKTIF')->count();

        return [
            Stat::make('Member', "{$total} Total")
                ->description("{$aktif} aktif | {$nonaktif} tidak aktif")
                ->icon('heroicon-o-users')
                ->color('primary'),
        ];
    }
}
