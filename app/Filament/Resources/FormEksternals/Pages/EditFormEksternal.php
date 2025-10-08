<?php

namespace App\Filament\Resources\FormEksternals\Pages;

use App\Filament\Resources\FormEksternals\FormEksternalResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditFormEksternal extends EditRecord
{
    protected static string $resource = FormEksternalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
