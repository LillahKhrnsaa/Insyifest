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
}
