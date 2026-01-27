<?php

namespace App\Filament\Resources\FormRegistrations\Pages;

use App\Filament\Resources\FormRegistrations\FormRegistrationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFormRegistrations extends ListRecords
{
    protected static string $resource = FormRegistrationResource::class;

    public function getTitle(): string
    {
        return 'Form Kelas Kilat';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
