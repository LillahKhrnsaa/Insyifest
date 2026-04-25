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
                TextColumn::make('coach.user.name')
                    ->label('Nama Coach')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('trainingSchedule.day')
                    ->label('Hari')
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
                    ->sortable(),

                TextColumn::make('trainingSchedule.place')
                    ->label('Tempat'),

                TextColumn::make('quota')
                    ->label('Kuota')
                    ->alignCenter(),

                TextColumn::make('usage_count')
                    ->label('Terisi')
                    ->state(function ($record) {
                        return \App\Models\Member::whereHas('coaches', function ($query) use ($record) {
                            $query->where('coaches.id', $record->coach_id);
                        })->count();
                    })
                    ->alignCenter()
                    ->color(fn ($state, $record) => $state >= $record->quota ? 'danger' : 'success'),
            ])
            ->defaultSort('coach_id')
            ->recordActions([
                ActionGroup::make([
                    EditAction::make()
                        ->label('Edit')
                        ->tooltip('Edit data')
                        ->icon('heroicon-o-pencil-square')
                        ->color('primary')
                        ->size('sm')
                        ->extraAttributes([
                            'class' => 'border border-blue-300 text-blue-700 bg-white hover:bg-blue-50 rounded-lg px-3 py-2'
                        ]),

                    DeleteAction::make()
                        ->label('Hapus')
                        ->tooltip('Hapus data')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->size('sm')
                        ->requiresConfirmation()
                        ->modalHeading('Hapus Data')
                        ->modalDescription('Yakin ingin menghapus data ini?')
                        ->modalSubmitActionLabel('Hapus')
                        ->modalCancelActionLabel('Batal')
                        ->extraAttributes([
                            'class' => 'border border-red-300 text-red-700 bg-white hover:bg-red-50 rounded-lg px-3 py-2'
                        ]),
                ])
                ->icon('heroicon-o-bars-4')
                ->label('')
                ->button()
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
