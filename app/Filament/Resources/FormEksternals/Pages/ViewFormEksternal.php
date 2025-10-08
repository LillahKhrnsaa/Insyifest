<?php

namespace App\Filament\Resources\FormEksternals\Pages;

use App\Filament\Resources\FormEksternals\FormEksternalResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewFormEksternal extends ViewRecord
{
    protected static string $resource = FormEksternalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
