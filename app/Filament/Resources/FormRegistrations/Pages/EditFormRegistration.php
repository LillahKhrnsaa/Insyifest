<?php

namespace App\Filament\Resources\FormRegistrations\Pages;

use App\Filament\Resources\FormRegistrations\FormRegistrationResource;
use App\Services\Registration\RegistrationFormService;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class EditFormRegistration extends EditRecord
{
    protected static string $resource = FormRegistrationResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // ✅ FIX: Refresh data sebelum fill biar dapat data terbaru
        $record = $this->record->fresh([
            'schedules.coaches.coach',
            'fields',
        ]);

        $data['use_grouping'] = $record->schedules->whereNotNull('schedule_group')->isNotEmpty();

        $data['schedules'] = $record->schedules->map(function ($schedule) {
            return [
                'id'   => $schedule->id,
                'schedule_group'  => $schedule->schedule_group, 
                'location'        => $schedule->location,
                'day'  => $schedule->day,
                'time' => $schedule->time,
                'date' => $schedule->date,

                'coaches' => $schedule->coaches->map(function ($sc) {
                    return [
                        'id'       => $sc->id,
                        'coach_id' => $sc->coach_id,
                        'quota'    => $sc->quota, // ✅ Ini ambil dari DB yang udah bener
                    ];
                })->toArray(),
            ];
        })->toArray();

        $data['fields'] = $record->fields->map(function ($field) {
            return [
                'id'          => $field->id,
                'label'       => $field->label,
                'name'        => $field->name,
                'type'        => $field->type,
                'is_required' => $field->is_required,
                'options'     => $field->options,
            ];
        })->toArray();

        return $data;
    }


    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        Log::info('=== DATA UPDATE ===', [
            'schedules' => $data['schedules'] ?? [],
            'fields' => $data['fields'] ?? [],
        ]);

        return app(RegistrationFormService::class)->update($record, $data);
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

     protected function afterSave(): void
    {
        // Refresh record biar data yang ditampilkan update
        $this->record = $this->record->fresh([
            'schedules.coaches.coach',
            'fields',
        ]);
        
        // Re-fill form dengan data baru
        $this->fillForm();
    }

}
