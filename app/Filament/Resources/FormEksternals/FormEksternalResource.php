<?php

namespace App\Filament\Resources\FormEksternals;

use App\Filament\Resources\FormEksternals\Pages\CreateFormEksternal;
use App\Filament\Resources\FormEksternals\Pages\EditFormEksternal;
use App\Filament\Resources\FormEksternals\Pages\ListFormEksternals;
use App\Filament\Resources\FormEksternals\Pages\ViewFormEksternal;
use App\Filament\Resources\FormEksternals\Schemas\FormEksternalForm;
use App\Filament\Resources\FormEksternals\Schemas\FormEksternalInfolist;
use App\Filament\Resources\FormEksternals\Tables\FormEksternalsTable;
use App\Models\FormEksternal;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FormEksternalResource extends Resource
{
    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-user';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Managemen Form';
    }
    protected static ?string $model = FormEksternal::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return FormEksternalForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return FormEksternalInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FormEksternalsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFormEksternals::route('/'),
            'create' => CreateFormEksternal::route('/create'),
            'view' => ViewFormEksternal::route('/{record}'),
            'edit' => EditFormEksternal::route('/{record}/edit'),
        ];
    }
}
