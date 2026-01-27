<?php

namespace App\Services\Registration;

use App\Models\RegistrationForm;
use App\Models\RegistrationSchedule;
use App\Models\ScheduleCoach;
use App\Models\RegistrationField;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

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
            $this->createSchedules($form, $data['schedules'] ?? []);

            // 3. Create fields
            $this->createFields($form, $data['fields'] ?? []);

            return $form;
        });
    }

    /**
     * UPDATE FORM PENDAFTARAN
     */
    public function update(RegistrationForm $form, array $data): RegistrationForm
    {
        return DB::transaction(function () use ($form, $data) {

            // 1️⃣ Update metadata form
            $form->update(
                Arr::only($data, [
                    'title',
                    'slug',
                    'description',
                    'is_active',
                ])
            );

            // 2️⃣ UPDATE / TAMBAH schedules & coach (JANGAN DELETE!)
            $this->updateSchedules($form, $data['schedules'] ?? []);

            // 3️⃣ UPDATE / TAMBAH fields (JANGAN DELETE!)
            $this->updateFields($form, $data['fields'] ?? []);

            return $form->refresh();
        });
    }

    /* ======================================================
     | CREATE HELPERS
     ====================================================== */
    protected function createSchedules(RegistrationForm $form, array $schedules): void
    {
        foreach ($schedules as $scheduleData) {

            $schedule = $form->schedules()->create([
                'schedule_group' => $scheduleData['schedule_group'] ?? null,
                'location'       => $scheduleData['location'] ?? null, 
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

    protected function createFields(RegistrationForm $form, array $fields): void
    {
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

    /* ======================================================
     | UPDATE HELPERS - AMAN UNTUK DATA YANG UDAH DIPAKE
     ====================================================== */
    protected function updateSchedules(RegistrationForm $form, array $schedules): void
    {
        foreach ($schedules as $scheduleData) {
            
            // 🔄 UPDATE schedule yang sudah ada
            if (!empty($scheduleData['id'])) {
                $schedule = RegistrationSchedule::findOrFail($scheduleData['id']);
                
                $schedule->update([
                    'schedule_group' => $scheduleData['schedule_group'] ?? null, 
                    'location'       => $scheduleData['location'] ?? null,         
                    'day'  => $scheduleData['day'],
                    'time' => $scheduleData['time'],
                    'date' => $scheduleData['date'],
                ]);
            } 
            // 🆕 CREATE schedule baru
            else {
                $schedule = $form->schedules()->create([
                    'schedule_group' => $scheduleData['schedule_group'] ?? null, 
                    'location'       => $scheduleData['location'] ?? null,
                    'day'  => $scheduleData['day'],
                    'time' => $scheduleData['time'],
                    'date' => $scheduleData['date'],
                ]);
            }

            // Handle coaches untuk schedule ini
            $this->updateScheduleCoaches($schedule, $scheduleData['coaches'] ?? []);
        }
    }

    protected function updateScheduleCoaches(RegistrationSchedule $schedule, array $coaches): void
    {
        foreach ($coaches as $coachData) {
            
            // 🔄 UPDATE coach yang sudah ada
            if (!empty($coachData['id'])) {
                $scheduleCoach = ScheduleCoach::findOrFail($coachData['id']);
                
                // ⚠️ VALIDASI: Jangan ubah coach_id kalau udah ada submission
                if ($scheduleCoach->quota_used > 0 && $scheduleCoach->coach_id != $coachData['coach_id']) {
                    throw ValidationException::withMessages([
                        'coaches' => 'Tidak bisa mengubah coach yang sudah memiliki peserta terdaftar.',
                    ]);
                }
                
                $scheduleCoach->update([
                    'coach_id' => $coachData['coach_id'],
                    'quota' => $coachData['quota'],
                ]);
            } 
            // 🆕 CREATE coach baru
            else {
                ScheduleCoach::create([
                    'registration_schedule_id' => $schedule->id,
                    'coach_id' => $coachData['coach_id'],
                    'quota' => $coachData['quota'],
                    'quota_used' => 0,
                ]);
            }
        }
    }

    protected function updateFields(RegistrationForm $form, array $fields): void
    {
        foreach ($fields as $index => $field) {
            
            // 🔄 UPDATE field yang sudah ada
            if (!empty($field['id'])) {
                $existingField = RegistrationField::findOrFail($field['id']);
                
                // ⚠️ VALIDASI: Jangan ubah type/name kalau udah ada jawaban
                $hasAnswers = $existingField->answers()->exists();
                
                if ($hasAnswers && ($existingField->type != $field['type'] || $existingField->name != $field['name'])) {
                    throw ValidationException::withMessages([
                        'fields' => 'Tidak bisa mengubah tipe atau name field yang sudah memiliki jawaban.',
                    ]);
                }
                
                $existingField->update([
                    'label' => $field['label'],
                    'is_required' => $field['is_required'] ?? false,
                    'options' => $field['options'] ?? null,
                    'order' => $field['order'] ?? ($index + 1),
                ]);
            } 
            // 🆕 CREATE field baru
            else {
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

    /* ======================================================
     | DELETE HELPERS - DENGAN SAFETY CHECK
     ====================================================== */
    
    /**
     * Delete schedule (cek dulu ada submission atau nggak)
     */
    public function deleteSchedule(RegistrationSchedule $schedule): void
    {
        DB::transaction(function () use ($schedule) {
            // Cek apakah ada submission
            $hasSubmissions = $schedule->coaches()->where('quota_used', '>', 0)->exists();
            
            if ($hasSubmissions) {
                throw ValidationException::withMessages([
                    'schedule' => 'Tidak bisa menghapus jadwal yang sudah memiliki peserta terdaftar.',
                ]);
            }
            
            $schedule->delete();
        });
    }

    /**
     * Delete coach (cek dulu ada submission atau nggak)
     */
    public function deleteScheduleCoach(ScheduleCoach $scheduleCoach): void
    {
        DB::transaction(function () use ($scheduleCoach) {
            if ($scheduleCoach->quota_used > 0) {
                throw ValidationException::withMessages([
                    'coach' => 'Tidak bisa menghapus coach yang sudah memiliki peserta terdaftar.',
                ]);
            }
            
            $scheduleCoach->delete();
        });
    }

    /**
     * Delete field (cek dulu ada jawaban atau nggak)
     */
    public function deleteField(RegistrationField $field): void
    {
        DB::transaction(function () use ($field) {
            if ($field->answers()->exists()) {
                throw ValidationException::withMessages([
                    'field' => 'Tidak bisa menghapus field yang sudah memiliki jawaban.',
                ]);
            }
            
            $field->delete();
        });
    }
}