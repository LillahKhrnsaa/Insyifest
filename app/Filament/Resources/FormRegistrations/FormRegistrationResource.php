<?php

namespace App\Filament\Resources\FormRegistrations;

use App\Filament\Resources\FormRegistrations\Pages\CreateFormRegistration;
use App\Filament\Resources\FormRegistrations\Pages\EditFormRegistration;
use App\Filament\Resources\FormRegistrations\Pages\ListFormRegistrations;
use App\Filament\Resources\FormRegistrations\Pages\ViewFormRegistration;
use App\Filament\Resources\FormRegistrations\Schemas\FormRegistrationForm;
use App\Filament\Resources\FormRegistrations\Schemas\FormRegistrationInfolist;
use App\Filament\Resources\FormRegistrations\Tables\FormRegistrationsTable;
use App\Models\RegistrationForm;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class FormRegistrationResource extends Resource
{
    protected static ?string $model = RegistrationForm::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    public static function form(Schema $schema): Schema
    {
        return FormRegistrationForm::configure($schema);
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Managemen Form';
    }

    public static function getNavigationLabel(): string
    {
        return 'Form Kelas Kilat';
    }

    public static function infolist(Schema $schema): Schema
    {
        return FormRegistrationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FormRegistrationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }


    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['schedules.coaches']);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFormRegistrations::route('/'),
            'create' => CreateFormRegistration::route('/create'),
            'view' => ViewFormRegistration::route('/{record}'),
            'edit' => EditFormRegistration::route('/{record}/edit'),
        ];
    }
}
