<?php

namespace App\Filament\Resources\FormRegistrations\Pages;

use App\Filament\Resources\FormRegistrations\FormRegistrationResource;
use App\Services\Registration\RegistrationFormService;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditFormRegistration extends EditRecord
{
    protected static string $resource = FormRegistrationResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return app(RegistrationFormService::class)->update($record, $data);
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->record->load([
            'schedules.coaches',
            'fields',
        ]);

        $data['schedules'] = $record->schedules->map(function ($schedule) {
            return [
                'day' => $schedule->day,
                'time' => $schedule->time,
                'date' => $schedule->date,
                'coaches' => $schedule->coaches->map(function ($coach) {
                    return [
                        'coach_id' => $coach->coach_id,
                        'quota' => $coach->quota,
                    ];
                })->toArray(),
            ];
        })->toArray();

        $data['fields'] = $record->fields
            ->sortBy('order')
            ->map(function ($field) {
                return [
                    'label' => $field->label,
                    'name' => $field->name,
                    'type' => $field->type,
                    'is_required' => $field->is_required,
                    'options' => $field->options,
                ];
            })
            ->toArray();

        return $data;
    }

}
