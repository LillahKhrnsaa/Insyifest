<?php

namespace App\Filament\Resources\Coaches;

use App\Filament\Resources\Coaches\Pages\CreateCoach;
use App\Filament\Resources\Coaches\Pages\EditCoach;
use App\Filament\Resources\Coaches\Pages\ListCoaches;
use App\Filament\Resources\Coaches\Pages\ViewCoach;
use App\Filament\Resources\Coaches\Schemas\CoachForm;
use App\Filament\Resources\Coaches\Schemas\CoachInfolist;
use App\Filament\Resources\Coaches\Tables\CoachesTable;
use App\Models\Coach;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
class CoachResource extends Resource
{

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-user';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Managemen Training';
    }
    protected static ?string $model = Coach::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return CoachForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CoachInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CoachesTable::configure($table);
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
            ->with(['user']);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCoaches::route('/'),
        ];
    }
}
