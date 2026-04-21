<?php

namespace App\Filament\Resources\Coaches\Tables;

use App\Filament\Widgets\AttendanceTable;
use App\Filament\Widgets\AttendanceTableFilamentCoach;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use App\Models\Coach;
use App\Models\TrainingSchedule;
use Illuminate\Support\Facades\Auth;
use App\Models\Member;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Actions\ActionGroup;
use Filament\Schemas\Components\Livewire;
use Filament\Tables\Columns\Layout\Split;

class CoachesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')
                    ->label('No')
                    ->state(function ($rowLoop, $livewire) {
                        return
                            ($livewire->getTablePage() - 1)
                            * $livewire->getTableRecordsPerPage()
                            + $rowLoop->iteration;
                    })
                    ->alignCenter()
                    ->sortable(false)
                    ->searchable(false),

                ImageColumn::make('user.photo_path')
                    ->label('Foto')
                    ->circular()
                    ->alignCenter()
                    ->imageHeight(40)
                    ->width(40)
                    ->disk('public'),

                TextColumn::make('user.name')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary')
                    ->icon('heroicon-o-user-circle')
                    ->tooltip(fn ($state) => $state)
                    ->wrap(),

                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-envelope')
                    ->iconColor('blue')
                    ->copyable()
                    ->copyMessage('Email disalin!')
                    ->tooltip(fn ($state) => $state)
                    ->wrap(),

                TextColumn::make('user.phone')
                    ->label('Nomor Telepon')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-phone')
                    ->color('success')
                    ->copyable()
                    ->copyMessage('Nomor telepon disalin!')
                    ->tooltip(fn ($state) => $state)
                    ->wrap(),

                TextColumn::make('user.gender')
                    ->label('Gender')
                    ->badge()
                    ->alignCenter()
                    ->sortable()
                    ->color(fn (string $state): string => match ($state) {
                        'MALE' => 'blue',
                        'FEMALE' => 'pink',
                        default => 'gray',
                    }),

                TextColumn::make('user.birth_date')
                    ->label('Tgl. Lahir')
                    ->date('d M Y')
                    ->alignCenter()
                    ->sortable(),
            ])

            ->filters([
                SelectFilter::make('active')
                    ->label('Status Akun')
                    ->options([
                        1 => 'Aktif',
                        0 => 'Nonaktif',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (isset($data['value'])) {
                            $query->whereHas('user', function ($q) use ($data) {
                                $q->where('active', $data['value']);
                            });
                        }
                    }),

                SelectFilter::make('gender')
                    ->label('Jenis Kelamin')
                    ->options([
                        'MALE' => 'Laki-laki',
                        'FEMALE' => 'Perempuan',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (isset($data['value'])) {
                            $query->whereHas('user', function ($q) use ($data) {
                                $q->where('gender', $data['value']);
                            });
                        }
                    }),
            ])

            ->recordActions([
                ActionGroup::make([
                    Action::make('lihat_kehadiran')
                        ->label('Lihat Kehadiran')
                        ->tooltip('Lihat Kehadiran Coach')
                        ->icon('heroicon-o-calendar-days')
                        ->color('info')
                        ->modalHeading(fn ($record) => 'Riwayat Kehadiran: ' . $record->user->name)
                        ->modalWidth('5xl') 
                        ->form([
                            Livewire::make(
                                AttendanceTableFilamentCoach::class,
                                fn ($record) => [
                                    'coachId' => $record->id,
                                ]
                            )
                            ->key(fn ($record) => 'attendance-table-' . $record->id)
                        ])
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Tutup')
                        ->extraAttributes([
                            'class' => 'border border-yellow-300 text-yellow-700 bg-white hover:bg-yellow-50 rounded-lg px-3 py-2',
                        ]),





                    ViewAction::make()
                        ->label('Lihat Detail')
                        ->tooltip('Lihat detail')
                        ->icon('heroicon-o-eye')
                        ->extraAttributes([
                            'class' => 'border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 rounded-lg px-3 py-2']),

                    EditAction::make()
                        ->label('Edit Coach')
                        ->tooltip('Edit coach')
                        ->icon('heroicon-o-pencil-square')
                        ->color('primary')
                        ->size('sm')
                        ->extraAttributes([
                            'class' => 'border border-blue-300 text-blue-700 bg-white hover:bg-blue-50 rounded-lg px-3 py-2'
                        ]),

                    DeleteAction::make()
                        ->label('Hapus Coach')
                        ->tooltip('Hapus coach')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->size('sm')
                        ->requiresConfirmation()
                        ->modalHeading('Hapus Coach')
                        ->modalDescription('Yakin ingin menghapus coach ini? Data user terkait juga akan terhapus!')
                        ->modalSubmitActionLabel('Hapus')
                        ->modalCancelActionLabel('Batal')
                        ->extraAttributes([
                            'class' => 'border border-red-300 text-red-700 bg-white hover:bg-red-50 rounded-lg px-3 py-2'
                        ])
                        ->before(function ($record) {
                            $record->user?->delete();
                        }),
                        

                ])
                ->icon('heroicon-o-bars-4')
                ->label('')
                ->button()
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Hapus Pilihan')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Hapus Beberapa Coach')
                        ->modalDescription('Yakin ingin menghapus {count} coach yang dipilih? Data user terkait juga akan terhapus!')
                        ->modalSubmitActionLabel('Hapus')
                        ->modalCancelActionLabel('Batal')
                        ->before(function ($records) {
                            $userIds = $records->pluck('user_id')->toArray();
                            \App\Models\User::whereIn('id', $userIds)->delete();
                        }),
                ])
                ->dropdownWidth('w-48')
                ->button()
                ->label('')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->size('sm'),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
