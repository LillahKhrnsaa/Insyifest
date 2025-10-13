<?php

namespace App\Filament\Widgets;

use App\Models\BankAccount;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BankAccountWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $total = BankAccount::count();
        $active = BankAccount::where('is_active', true)->count();

        return [
            Stat::make('Akun Bank', "{$total} Akun")
                ->description("{$active} aktif")
                ->icon('heroicon-o-banknotes')
                ->color('success'),
        ];
    }
}
