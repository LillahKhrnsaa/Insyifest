<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Support\Facades\Auth;

// Import semua widget yang kamu punya
use App\Filament\Widgets\BankAccountWidget;
use App\Filament\Widgets\CoachWidget;
use App\Filament\Widgets\MemberWidget;
use App\Filament\Widgets\TrainingPackageWidget;
use App\Filament\Widgets\TrainingScheduleWidget;
use App\Filament\Widgets\FormWidget;
use App\Filament\Widgets\PostWidget;

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
     * Menentukan layout grid dashboard
     */
    public function getColumns(): array
    {
        // Responsif: mobile = 1 kolom, desktop = 3 kolom
        return [
            'default' => 1,
            'md' => 2,
            'xl' => 3,
        ];
    }

    /**
     * 🧩 Daftar widget dengan pengecekan permission
     */
    public function getWidgets(): array
    {
        $user = Auth::user();

        $widgets = [];

        // --- BARIS 1 ---
        if ($user?->can('viewAny.bank_accounts')) {
            $widgets[] = BankAccountWidget::class;
        }

        if ($user?->can('viewAny.coaches')) {
            $widgets[] = CoachWidget::class;
        }

        if ($user?->can('viewAny.members')) {
            $widgets[] = MemberWidget::class;
        }

        // --- BARIS 2 ---
        if ($user?->can('viewAny.training_packages')) {
            $widgets[] = TrainingPackageWidget::class;
        }

        if ($user?->can('viewAny.training_schedules')) {
            $widgets[] = TrainingScheduleWidget::class;
        }

        if ($user?->can('viewAny.form_eksternals')) {
            $widgets[] = FormWidget::class;
        }

        // --- BARIS 3 (full width) ---
        if ($user?->can('viewAny.general_materials')) {
            $widgets[] = PostWidget::class;
        }

        return $widgets;
    }

    /**
     * 🔒 Batasi akses Dashboard hanya untuk user dengan minimal satu permission
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
