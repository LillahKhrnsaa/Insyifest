<?php

namespace App\Providers;

use App\Models\PaymentHistory;
use App\Observers\PaymentHistoryObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    //  protected $observers = [
    //     PaymentHistory::class => [PaymentHistoryObserver::class],
    // ];
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
