<?php

namespace App\Filament\Resources\TrainingSchedules;

use App\Filament\Resources\TrainingSchedules\Pages\CreateTrainingSchedule;
use App\Filament\Resources\TrainingSchedules\Pages\EditTrainingSchedule;
use App\Filament\Resources\TrainingSchedules\Pages\ListTrainingSchedules;
use App\Filament\Resources\TrainingSchedules\Pages\ViewTrainingSchedule;
use App\Filament\Resources\TrainingSchedules\Schemas\TrainingScheduleForm;
use App\Filament\Resources\TrainingSchedules\Schemas\TrainingScheduleInfolist;
use App\Filament\Resources\TrainingSchedules\Tables\TrainingSchedulesTable;
use App\Models\TrainingSchedule;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TrainingScheduleResource extends Resource
{
    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-user';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Managemen Training';
    }

    protected static ?string $model = TrainingSchedule::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return TrainingScheduleForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TrainingScheduleInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TrainingSchedulesTable::configure($table);
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
            'index' => ListTrainingSchedules::route('/'),
            'create' => CreateTrainingSchedule::route('/create'),
            'view' => ViewTrainingSchedule::route('/{record}'),
            'edit' => EditTrainingSchedule::route('/{record}/edit'),
        ];
    }
}
