<?php

namespace App\Filament\Resources\MemberArchives\Pages;

use App\Filament\Resources\MemberArchives\MemberArchiveResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMemberArchive extends ViewRecord
{
    protected static string $resource = MemberArchiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
