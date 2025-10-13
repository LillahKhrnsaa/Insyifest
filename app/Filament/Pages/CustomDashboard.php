<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Icons\Heroicon;

class CustomDashboard extends BaseDashboard
{

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-home';
    }

    public static function getNavigationLabel(): string
    {
        return 'Dashboard';
    }


    /**
     * 🧩 Menentukan daftar widget yang muncul di dashboard
     * berdasarkan permission Spatie.
     */
    public function getWidgets(): array
    {
        $user = Auth::user();
        $widgets = [];

        // Cek permission per widget
        if ($user?->can('viewAny.bank_accounts')) {
            $widgets[] = \App\Filament\Widgets\BankAccountWidget::class;
        }

        if ($user?->can('viewAny.coaches')) {
            $widgets[] = \App\Filament\Widgets\CoachWidget::class;
        }

        if ($user?->can('viewAny.members')) {
            $widgets[] = \App\Filament\Widgets\MemberWidget::class;
        }

        if ($user?->can('viewAny.training_packages')) {
            $widgets[] = \App\Filament\Widgets\TrainingPackageWidget::class;
        }

        if ($user?->can('viewAny.training_schedules')) {
            $widgets[] = \App\Filament\Widgets\TrainingScheduleWidget::class;
        }

        if ($user?->can('viewAny.form_eksternals')) {
            $widgets[] = \App\Filament\Widgets\FormWidget::class;
        }

        if ($user?->can('viewAny.general_materials')) {
            $widgets[] = \App\Filament\Widgets\PostWidget::class;
        }

        return $widgets;
    }

    /**
     * 🔒 (Opsional) Tentukan siapa yang bisa akses dashboard ini.
     * Misal: hanya user yang punya minimal satu permission viewAny.
     */
    public static function canAccess(): bool
    {
        $user = Auth::user();

        return $user && $user->hasAnyPermission([
            'viewAny.bank_accounts',
            'viewAny.coaches',
            'viewAny.members',
            'viewAny.training_packages',
            'viewAny.training_schedules',
            'viewAny.form_eksternals',
            'viewAny.general_materials',
        ]);
    }
}
