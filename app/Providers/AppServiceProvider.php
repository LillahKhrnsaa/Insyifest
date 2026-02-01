<?php

namespace App\Providers;

use App\Models\PaymentHistory;
use App\Models\RegistrationSubmission;
use App\Observers\PaymentHistoryObserver;
use App\Observers\RegistrationSubmissionObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        RegistrationSubmission::observe(RegistrationSubmissionObserver::class);
        if (\Illuminate\Support\Facades\App::environment('production') || \Illuminate\Support\Facades\App::environment('local')) {
        \Illuminate\Support\Facades\URL::forceScheme('https');
    	}
    }
}
