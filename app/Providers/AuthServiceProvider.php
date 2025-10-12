<?php

namespace App\Providers;

use App\Models\Salary;
use App\Policies\SalaryPolicy;
use App\Models\Member;
use App\Policies\MemberPolicy;
use App\Models\PaymentHistory;
use App\Policies\PaymentHistoryPolicy;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Salary::class => SalaryPolicy::class,
        Member::class => MemberPolicy::class,
        PaymentHistory::class => PaymentHistoryPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Gate untuk mengatur akses ke Filament Admin Panel
        Gate::define('access-admin-panel', function (User $user) {
            // Semua user yang login boleh mengakses admin panel
            return true;
        });

        // Gate untuk menentukan role-based access jika diperlukan di masa depan
        Gate::define('is-admin', function (User $user) {
            // Untuk sementara, semua user dianggap bisa mengakses
            // Anda bisa menambahkan logic role-based di sini nanti
            return true;
        });

        Gate::define('is-coach', function (User $user) {
            // Cek apakah user adalah coach
            return $user->coach()->exists();
        });

        Gate::define('is-member', function (User $user) {
            // Cek apakah user adalah member
            return $user->member()->exists();
        });

        // Default gate untuk akses umum
        Gate::define('view-admin', function (User $user) {
            return true; // Semua user yang login bisa melihat admin
        });
    }
}