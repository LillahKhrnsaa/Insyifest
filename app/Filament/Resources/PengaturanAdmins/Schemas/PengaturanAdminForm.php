<?php

namespace App\Filament\Resources\PengaturanAdmins\Schemas;

use App\Models\Coach;
use App\Models\TrainingSchedule;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PengaturanAdminForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('coach_id')
                    ->label('Nama Coach')
                    ->options(Coach::with('user')->get()->pluck('user.name', 'id'))
                    ->searchable()
                    ->required(),

                Select::make('training_schedule_id')
                    ->label('Jadwal Latihan')
                    ->options(TrainingSchedule::all()->mapWithKeys(function ($schedule) {
                        $translatedDay = match (strtoupper($schedule->day)) {
                            'MONDAY', 'SENIN' => 'Senin',
                            'TUESDAY', 'SELASA' => 'Selasa',
                            'WEDNESDAY', 'RABU' => 'Rabu',
                            'THURSDAY', 'KAMIS' => 'Kamis',
                            'FRIDAY', 'JUMAT' => 'Jumat',
                            'SATURDAY', 'SABTU' => 'Sabtu',
                            'SUNDAY', 'MINGGU' => 'Minggu',
                            default => $schedule->day,
                        };
                        return [$schedule->id => "{$translatedDay} - {$schedule->time} ({$schedule->place})"];
                    }))
                    ->searchable()
                    ->required(),

                TextInput::make('quota')
                    ->label('Kuota Maksimal')
                    ->numeric()
                    ->default(0)
                    ->required(),
            ]);
    }
}
