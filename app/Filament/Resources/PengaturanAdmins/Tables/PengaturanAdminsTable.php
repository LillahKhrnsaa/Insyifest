<?php

namespace App\Filament\Resources\PengaturanAdmins\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class PengaturanAdminsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('row_number')
                    ->label('No.')
                    ->rowIndex(),

                TextColumn::make('coach.user.name')
                    ->label('Nama Coach')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary')
                    ->icon('heroicon-o-user'),

                TextColumn::make('trainingSchedule.day')
                    ->label('Hari')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn (string $state): string => match (strtoupper($state)) {
                        'MONDAY', 'SENIN' => 'Senin',
                        'TUESDAY', 'SELASA' => 'Selasa',
                        'WEDNESDAY', 'RABU' => 'Rabu',
                        'THURSDAY', 'KAMIS' => 'Kamis',
                        'FRIDAY', 'JUMAT' => 'Jumat',
                        'SATURDAY', 'SABTU' => 'Sabtu',
                        'SUNDAY', 'MINGGU' => 'Minggu',
                        default => $state,
                    })
                    ->sortable(),

                TextColumn::make('trainingSchedule.time')
                    ->label('Jam')
                    ->icon('heroicon-o-clock')
                    ->sortable(),

                TextColumn::make('trainingSchedule.place')
                    ->label('Tempat')
                    ->icon('heroicon-o-map-pin')
                    ->wrap(),

                TextColumn::make('quota')
                    ->label('Kuota')
                    ->badge()
                    ->color('gray')
                    ->alignCenter(),

                TextColumn::make('usage_count')
                    ->label('Terisi')
                    ->badge()
                    ->state(function ($record) {
                        return \App\Models\Member::whereHas('coaches', function ($query) use ($record) {
                            $query->where('coaches.id', $record->coach_id);
                        })
                        ->where(function ($query) use ($record) {
                            $query->whereHas('trainingSchedules', function ($q) use ($record) {
                                $q->where('training_schedules.id', $record->training_schedule_id);
                            })
                            ->orWhereDoesntHave('trainingSchedules');
                        })
                        ->count();
                    })
                    ->alignCenter()
                    ->color(fn ($state, $record) => $state >= $record->quota ? 'danger' : 'success'),
            ])
            ->defaultSort('coach_id')
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('coach_id')
                    ->label('Nama Pelatih')
                    ->options(fn () => \App\Models\Coach::with('user')->get()->pluck('user.name', 'id'))
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('')
                    ->button()
                    ->tooltip('Edit data')
                    ->icon('heroicon-o-pencil-square'),

                DeleteAction::make()
                    ->label('')
                    ->button()
                    ->tooltip('Hapus data')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Data')
                    ->modalDescription('Yakin ingin menghapus data ini? Jadwal yang sudah dipilih oleh member untuk hari ini juga akan terhapus!')
                    ->modalSubmitActionLabel('Ya, Hapus')
                    ->after(function ($record) {
                        \Illuminate\Support\Facades\DB::table('member_schedules')
                            ->where('coach_id', $record->coach_id)
                            ->where('training_schedule_id', $record->training_schedule_id)
                            ->delete();
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->after(function (\Illuminate\Database\Eloquent\Collection $records) {
                            foreach ($records as $record) {
                                \Illuminate\Support\Facades\DB::table('member_schedules')
                                    ->where('coach_id', $record->coach_id)
                                    ->where('training_schedule_id', $record->training_schedule_id)
                                    ->delete();
                            }
                        }),
                ]),
            ]);
    }
}
