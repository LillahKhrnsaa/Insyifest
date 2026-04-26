<?php

namespace App\Filament\Resources\Coaches\Pages;

use App\Filament\Resources\Coaches\CoachResource;
use App\Filament\Widgets\AttendanceSummaryTable;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Utilities\Get;

class ListCoaches extends ListRecords
{
    protected static string $resource = CoachResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('rekap_seluruh_coach')
                ->label('Rekap Absensi Total')
                ->icon('heroicon-o-document-chart-bar')
                ->color('success')
                ->modalHeading('Laporan Kehadiran Seluruh Coach')
                ->modalWidth('4xl')
                ->form([
                    Grid::make(2)->schema([
                        Select::make('month')
                            ->label('Filter Bulan')
                            ->options([
                                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                                '04' => 'April', '05' => 'Mei', '06' => 'Juni',
                                '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
                                '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
                            ])
                            ->default(now()->format('m'))
                            ->live(), // Memicu re-render Livewire::make

                        TextInput::make('year')
                            ->label('Tahun')
                            ->numeric()
                            ->default(now()->year)
                            ->live(),
                    ]),

                    Livewire::make(AttendanceSummaryTable::class, fn (Get $get) => [
                        'month' => $get('month'),
                        'year'  => $get('year'),
                    ])
                    // Key unik sangat penting agar widget di-refresh saat filter berubah
                    ->key(fn($get) => 'rekap-absensi-' . $get('month') . '-' . $get('year'))
                    ->live(),
                ])
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Tutup'),
            CreateAction::make()->label('Buat ' . static::getResource()::getNavigationLabel()),
        ];
    }
}
