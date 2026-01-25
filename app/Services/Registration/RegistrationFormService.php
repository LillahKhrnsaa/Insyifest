<?php

namespace App\Services\Registration;

use App\Models\RegistrationForm;
use App\Models\RegistrationSchedule;
use App\Models\ScheduleCoach;
use App\Models\RegistrationField;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class RegistrationFormService
{
    /**
     * CREATE FORM PENDAFTARAN
     */
    public function create(array $data): RegistrationForm
    {
        return DB::transaction(function () use ($data) {

            // 1. Create form utama
            $form = RegistrationForm::create(
                Arr::only($data, [
                    'title',
                    'slug',
                    'description',
                    'is_active',
                ])
            );

            // 2. Create schedules + coaches
            $this->syncSchedules($form, $data['schedules'] ?? []);

            // 3. Create fields
            $this->syncFields($form, $data['fields'] ?? []);

            return $form;
        });
    }

    /**
     * UPDATE FORM PENDAFTARAN
     */
    public function update(RegistrationForm $form, array $data): RegistrationForm
    {
        return DB::transaction(function () use ($form, $data) {

            // 1. Update form utama
            $form->update(
                Arr::only($data, [
                    'title',
                    'slug',
                    'description',
                    'is_active',
                ])
            );

            // 2. Sync ulang schedules + coaches
            $this->syncSchedules($form, $data['schedules'] ?? [], true);

            // 3. Sync ulang fields
            $this->syncFields($form, $data['fields'] ?? [], true);

            return $form->refresh();
        });
    }

    /**
     * SYNC SCHEDULES & COACHES
     */
    protected function syncSchedules(
        RegistrationForm $form,
        array $schedules,
        bool $isUpdate = false
    ): void {
        if ($isUpdate) {
            // HAPUS TOTAL → RECREATE
            // Aman karena ini SETUP DATA, bukan data user
            $form->schedules()->delete();
        }

        foreach ($schedules as $scheduleData) {

            $schedule = $form->schedules()->create([
                'day'  => $scheduleData['day'],
                'time' => $scheduleData['time'],
                'date' => $scheduleData['date'],
            ]);

            foreach ($scheduleData['coaches'] ?? [] as $coachData) {
                ScheduleCoach::create([
                    'registration_schedule_id' => $schedule->id,
                    'coach_id' => $coachData['coach_id'],
                    'quota' => $coachData['quota'],
                    'quota_used' => 0,
                ]);
            }
        }
    }

    /**
     * SYNC FORM FIELDS
     */
    protected function syncFields(
        RegistrationForm $form,
        array $fields,
        bool $isUpdate = false
    ): void {
        if ($isUpdate) {
            $form->fields()->delete();
        }

        foreach ($fields as $index => $field) {
            RegistrationField::create([
                'registration_form_id' => $form->id,
                'label' => $field['label'],
                'name' => $field['name'],
                'type' => $field['type'],
                'is_required' => $field['is_required'] ?? false,
                'options' => $field['options'] ?? null,
                'order' => $field['order'] ?? ($index + 1),
            ]);
        }
    }
}
