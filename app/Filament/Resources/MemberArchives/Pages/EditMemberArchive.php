<?php

namespace App\Filament\Resources\MemberArchives\Pages;

use App\Filament\Resources\MemberArchives\MemberArchiveResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditMemberArchive extends EditRecord
{
    protected static string $resource = MemberArchiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
