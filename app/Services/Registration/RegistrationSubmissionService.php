<?php

namespace App\Services\Registration;

use App\Models\Registration;
use App\Models\RegistrationForm;
use App\Models\ScheduleCoach;
use App\Models\RegistrationAnswer;
use App\Models\RegistrationSubmission;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RegistrationSubmissionService
{
    /**
     * SUBMIT PENDAFTARAN (PUBLIC)
     */
    public function submit(
        RegistrationForm $form,
        int $scheduleCoachId,
        array $answers
    ): RegistrationSubmission {
        return DB::transaction(function () use ($form, $scheduleCoachId, $answers) {

            /**
             * 1. LOCK schedule_coach
             */
            $scheduleCoach = ScheduleCoach::where('id', $scheduleCoachId)
                ->lockForUpdate()
                ->firstOrFail();

            /**
             * 2. VALIDASI FORM & QUOTA
             */
            if (
                $scheduleCoach->schedule->registration_form_id !== $form->id
            ) {
                throw ValidationException::withMessages([
                    'schedule' => 'Jadwal tidak valid untuk form ini.',
                ]);
            }

            if ($scheduleCoach->quota_used >= $scheduleCoach->quota) {
                throw ValidationException::withMessages([
                    'quota' => 'Kuota untuk jadwal dan coach ini sudah penuh.',
                ]);
            }

            /**
             * 3. CREATE REGISTRATION
             */
            $registration = RegistrationSubmission::create([
                'registration_form_id' => $form->id,
                'registration_schedule_id' => $scheduleCoach->registration_schedule_id,
                'schedule_coach_id' => $scheduleCoach->id,
            ]);

            /**
             * 4. SIMPAN JAWABAN FIELD
             */
            foreach ($answers as $fieldId => $value) {
                RegistrationAnswer::create([
                    'registration_submission_id' => $registration->id,
                    'registration_field_id' => $fieldId,
                    'value' => is_array($value)
                        ? json_encode($value)
                        : $value,
                ]);

            }

            /**
             * 5. INCREMENT QUOTA
             */
            $scheduleCoach->increment('quota_used');

            return $registration;
        });
    }
}
