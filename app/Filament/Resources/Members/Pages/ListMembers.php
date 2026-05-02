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
            Action::make('buat_arsip')
                ->label('Buat Arsip (Backup)')
                ->icon('heroicon-o-cloud-arrow-up')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Konfirmasi Backup Data')
                ->modalDescription('Apakah Anda yakin ingin membuat arsip data member saat ini? Data akan disimpan ke riwayat arsip bulanan, namun status member akan TETAP AKTIF.')
                ->modalSubmitActionLabel('Ya, Buat Arsip')
                ->action(function () {
                    $count = app(\App\Actions\ArchiveMembersAction::class)->execute(false);
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Backup Berhasil')
                        ->body("$count record berhasil diarsipkan. Status member tetap aktif.")
                        ->success()
                        ->send();
                })
                ->visible(fn () => auth()->user()->can('close_period.members')),

            Action::make('tutup_periode')
                ->label('Tutup Periode (Off Semua)')
                ->icon('heroicon-o-archive-box-x-mark')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('⚠️ PERHATIAN: KONFIRMASI TUTUP PERIODE')
                ->modalDescription(new \Illuminate\Support\HtmlString('
                    <div style="background-color: #fef2f2; border-left: 4px solid #ef4444; padding: 12px; margin-bottom: 10px;">
                        <p style="color: #991b1b; font-weight: bold; margin: 0;">TINDAKAN INI HANYA UNTUK RESET STATUS!</p>
                        <ul style="color: #b91c1c; font-size: 0.875rem; margin-top: 5px; padding-left: 20px;">
                            <li>Semua status member yang sedang <strong>AKTIF</strong> akan otomatis diubah menjadi <strong>TIDAK AKTIF (OFF)</strong>.</li>
                            <li>Tindakan ini <strong>TIDAK</strong> akan membuat arsip data baru.</li>
                        </ul>
                        <p style="color: #7f1d1d; font-size: 0.875rem; margin-top: 10px;">Pastikan Anda sudah melakukan <strong>Backup Arsip</strong> terlebih dahulu jika diperlukan.</p>
                    </div>
                '))
                ->modalSubmitActionLabel('Ya, Matikan Semua Member (Off)')
                ->action(function () {
                    $count = \App\Models\Member::where('status', 'AKTIF')->count();
                    \App\Models\Member::where('status', 'AKTIF')->update(['status' => 'TIDAK_AKTIF']);
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Periode Berhasil Ditutup')
                        ->body("$count member telah diubah statusnya menjadi Tidak Aktif (Off).")
                        ->success()
                        ->send();
                })
                ->visible(fn () => auth()->user()->can('close_period.members')),
            CreateAction::make()->label('Buat ' . static::getResource()::getNavigationLabel()),
        ];
    }
}
