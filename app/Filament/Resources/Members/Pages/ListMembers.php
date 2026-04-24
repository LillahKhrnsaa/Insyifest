<?php

namespace App\Filament\Resources\Members\Pages;

use App\Filament\Resources\Members\MemberResource;
use App\Filament\Widgets\MemberAttendanceSummaryTable;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Utilities\Get;

class ListMembers extends ListRecords
{
    protected static string $resource = MemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('rekap_kehadiran_atlet')
                ->label('Rekap Presensi Atlet')
                ->icon('heroicon-o-clipboard-document-check')
                ->color('info')
                ->modalHeading('Laporan Kehadiran Seluruh Atlet')
                ->modalWidth('4xl')
                ->form([
                    Grid::make(2)->schema([
                        Select::make('month')
                            ->label('Bulan')
                            ->options([
                                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                                '04' => 'April', '05' => 'Mei', '06' => 'Juni',
                                '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
                                '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
                            ])
                            ->default(now()->format('m'))
                            ->live(),

                        TextInput::make('year')
                            ->label('Tahun')
                            ->numeric()
                            ->default(now()->year)
                            ->live(),
                    ]),

                    Livewire::make(MemberAttendanceSummaryTable::class, fn (Get $get) => [
                        'month' => $get('month'),
                        'year'  => $get('year'),
                    ])
                    ->key(fn($get) => 'rekap-member-' . $get('month') . '-' . $get('year'))
                    ->live(),
                ])
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Tutup'),
            Action::make('tutup_periode')
                ->label('Tutup Periode Bulan Ini')
                ->icon('heroicon-o-archive-box')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Konfirmasi Penutupan Periode')
                ->modalDescription('Apakah Anda yakin ingin menutup periode bulan ini? Semua member aktif akan diarsipkan sebagai data histori dan status mereka akan diubah menjadi tidak aktif.')
                ->modalSubmitActionLabel('Ya, Tutup Periode')
                ->action(function () {
                    $count = app(\App\Actions\ArchiveMembersAction::class)->execute();
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Periode Berhasil Ditutup')
                        ->body("$count member berhasil diarsipkan dan status telah di-reset.")
                        ->success()
                        ->send();
                })
                ->visible(fn () => auth()->user()->can('close_period.members')),
            CreateAction::make(),
        ];
    }
}
