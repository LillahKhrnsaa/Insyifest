<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\View as ViewFacade;
use App\Filament\Widgets\BankAccountWidget;
use App\Filament\Widgets\CoachWidget;
use App\Filament\Widgets\MemberWidget;
use App\Filament\Widgets\TrainingPackageWidget;
use App\Filament\Widgets\TrainingScheduleWidget;
use App\Filament\Widgets\FormWidget;
use App\Filament\Widgets\PostWidget;
use App\Models\Coach;

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

    public function getColumns(): array
    {
        return [
            'default' => 1,
            'md' => 2,
            'xl' => 3,
        ];
    }

    public function getWidgets(): array
    {
        $user = Auth::user();
        $widgets = [];

        if ($user?->can('viewAny.bank_accounts')) {
            $widgets[] = BankAccountWidget::class;
        }
        if ($user?->can('viewAny.coaches')) {
            $widgets[] = CoachWidget::class;
        }
        if ($user?->can('viewAny.members')) {
            $widgets[] = MemberWidget::class;
        }
        if ($user?->can('viewAny.training_packages')) {
            $widgets[] = TrainingPackageWidget::class;
        }
        if ($user?->can('viewAny.training_schedules')) {
            $widgets[] = TrainingScheduleWidget::class;
        }
        if ($user?->can('viewAny.general_materials')) {
            $widgets[] = PostWidget::class;
        }
        if ($user?->can('viewAny.form_eksternals')) {
            $widgets[] = FormWidget::class;
        }

        return $widgets;
    }

    public static function canAccess(): bool
    {
        return Auth::check();
    }
}