<?php

namespace App\Filament\Resources\FormEksternals\Pages;

use App\Filament\Resources\FormEksternals\FormEksternalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFormEksternals extends ListRecords
{
    protected static string $resource = FormEksternalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
