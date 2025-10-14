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

    // Ganti dari protected jadi PUBLIC
    public function getView(): string
    {
        $user = Auth::user();

        if ($user->hasRole('coach')) {
            return 'filament.pages.coach-dashboard';
        }

        if ($user->hasRole('member')) {
            return 'filament.pages.dashboard-member';
        }

        return parent::getView();
    }

    // Ganti dari protected jadi PUBLIC
    public function getViewData(): array
    {
        $user = Auth::user();

        if ($user->hasRole('coach')) {
            $coach = Coach::with(['user', 'members.user', 'trainingSchedules'])
                ->where('user_id', $user->id)
                ->firstOrFail();

            return [
                'coach' => $coach,
                'totalMembers' => $coach->members->count(),
                'activeMembers' => $coach->members->where('status', 'AKTIF')->count(),
                'inactiveMembers' => $coach->members->where('status', 'TIDAK_AKTIF')->count(),
                'totalSchedules' => $coach->trainingSchedules->count(),
            ];
        }

        if ($user->hasRole('member')) {
            return [];
        }

        return parent::getViewData();
    }
}