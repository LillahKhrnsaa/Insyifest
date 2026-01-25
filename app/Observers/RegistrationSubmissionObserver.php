<?php

namespace App\Observers;

use App\Models\RegistrationSubmission;
use Illuminate\Support\Facades\DB;

class RegistrationSubmissionObserver
{
    /**
     * Handle the RegistrationSubmission "created" event.
     */
    public function created(RegistrationSubmission $registrationSubmission): void
    {
        //
    }

    /**
     * Handle the RegistrationSubmission "updated" event.
     */
    public function updated(RegistrationSubmission $registrationSubmission): void
    {
        //
    }

    /**
     * Handle the RegistrationSubmission "deleted" event.
     */
    public function deleted(RegistrationSubmission $submission)
    {
        DB::transaction(function () use ($submission) {
            $submission->scheduleCoach()
                ->lockForUpdate()
                ->decrement('quota_used');
        });
    }

    /**
     * Handle the RegistrationSubmission "restored" event.
     */
    public function restored(RegistrationSubmission $registrationSubmission): void
    {
        //
    }

    /**
     * Handle the RegistrationSubmission "force deleted" event.
     */
    public function forceDeleted(RegistrationSubmission $registrationSubmission): void
    {
        //
    }
}
