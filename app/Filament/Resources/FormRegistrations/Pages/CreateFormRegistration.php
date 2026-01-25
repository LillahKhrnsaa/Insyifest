<?php

namespace App\Filament\Resources\FormRegistrations\Pages;

use App\Filament\Resources\FormRegistrations\FormRegistrationResource;
use App\Services\Registration\RegistrationFormService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateFormRegistration extends CreateRecord
{
    protected static string $resource = FormRegistrationResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(RegistrationFormService::class)->create($data);
    }
}
