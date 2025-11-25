<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use App\Models\TrainingSchedule;
use Filament\Widgets\TableWidget;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

class AttendanceTable extends TableWidget
{
    protected static ?string $heading = 'Kehadiran Coach';

    public ?int $coachId = null; // Admin passing coachId

    public function mount(?int $coachId = null): void
    {
        $this->coachId = $coachId;
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->query(fn () =>
                Attendance::query()
                    ->where('coach_id', $this->coachId)
                    ->with('schedule')
                    ->orderByDesc('date')
            )

            ->columns([
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('schedule.day')
                    ->label('Hari')
                    ->badge(),

                TextColumn::make('schedule.time')
                    ->label('Waktu')
                    ->badge(),

                TextColumn::make('place')
                    ->label('Tempat')
                    ->default('-'),

                ImageColumn::make('photo_path')
                    ->label('Foto')
                    ->circular(),
            ]);
    }

}
