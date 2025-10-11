<?php

namespace App\Filament\Resources\Coaches\Tables;

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
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;

class CoachesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // 1. Foto Profil
                ImageColumn::make('user.photo_path')
                    ->label('Foto')
                    ->circular()
                    ->alignCenter()
                    ->imageHeight(40)
                    ->width(40)
                    ->disk('public')
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->user->name ?? 'Coach')),

                // 2. Nama Lengkap Coach
                TextColumn::make('user.name')
                    ->label('Nama Coach')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary')
                    ->icon('heroicon-o-user-circle')
                    ->tooltip(fn ($record) => $record->user?->name)
                    ->wrap(),

                // 3. Email
                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-envelope')
                    ->iconColor('blue')
                    ->copyable()
                    ->copyMessage('Email disalin!')
                    ->tooltip(fn ($record) => $record->user?->email)
                    ->wrap(),

                // 4. Nomor Telepon
                TextColumn::make('user.phone')
                    ->label('Nomor Telepon')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-phone')
                    ->color('success')
                    ->copyable()
                    ->copyMessage('Nomor telepon disalin!')
                    ->tooltip(fn ($record) => $record->user?->phone)
                    ->wrap(),

                // 5. Jenis Kelamin
                TextColumn::make('user.gender')
                    ->label('Gender')
                    ->badge()
                    ->alignCenter()
                    ->sortable()
                    ->color(fn (string $state): string => match ($state) {
                        'MALE' => 'blue',
                        'FEMALE' => 'pink',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'MALE' => 'Laki-laki',
                        'FEMALE' => 'Perempuan',
                        default => $state,
                    }),

                // 6. Tanggal Lahir
                TextColumn::make('user.birth_date')
                    ->label('Tgl. Lahir')
                    ->date('d M Y')
                    ->alignCenter()
                    ->sortable(),

                // 7. Bio Coach (snippet)
                TextColumn::make('bio')
                    ->label('Biografi')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->bio)
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),

                // 8. Status Akun
                ToggleColumn::make('user.active')
                    ->label('Aktif')
                    ->alignCenter()
                    ->tooltip('Klik untuk aktif/nonaktif')
                    ->onColor('success')
                    ->offColor('danger')
                    ->onIcon('heroicon-o-check-circle')
                    ->offIcon('heroicon-o-x-circle')
                    ->sortable()
                    ->beforeStateUpdated(function ($record, $state) {
                        // Update status di user
                        $record->user->update(['active' => $state]);
                    }),

                // 9. Role (selalu Coach)
                TextColumn::make('user.role')
                    ->label('Role')
                    ->badge()
                    ->alignCenter()
                    ->color('warning')
                    ->default('coach')
                    ->formatStateUsing(fn () => 'Coach'),

                // 10. Dibuat
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->alignCenter()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // 11. Diupdate
                TextColumn::make('updated_at')
                    ->label('Diupdate')
                    ->dateTime('d M Y')
                    ->alignCenter()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filter berdasarkan status aktif
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

                // Filter berdasarkan gender
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
                Action::make('viewMembers')
                    ->label('Lihat Member')
                    ->icon('heroicon-o-users')
                    ->color('info')
                    ->modalHeading(fn ($record) => 'Member yang Di-assign')
                    ->modalDescription(fn ($record) => 'Coach: ' . ($record->name ?? '-'))
                    ->infolist(function ($record): array {
                        if ($record->members->isEmpty()) {
                            return [
                                Section::make()
                                    ->schema([
                                        TextEntry::make('empty')
                                            ->label('')
                                            ->state('Belum ada member yang di-assign ke coach ini.')
                                            ->color('warning'),
                                    ]),
                            ];
                        }

                        return [
                            Section::make('Daftar Member')
                                ->schema([
                                    RepeatableEntry::make('members')
                                        ->label('')
                                        ->schema([
                                            TextEntry::make('user.name')
                                                ->label('Nama Member')
                                                ->weight('bold')
                                                ->icon('heroicon-o-user')
                                                ->default('-'),
                                            TextEntry::make('user.email')
                                                ->label('Email')
                                                ->icon('heroicon-o-envelope')
                                                ->copyable()
                                                ->default('-'),
                                            TextEntry::make('created_at')
                                                ->label('Bergabung Sejak')
                                                ->icon('heroicon-o-calendar')
                                                ->dateTime('d M Y')
                                                ->default('-'),
                                        ])
                                        ->columns(3)
                                        ->columnSpanFull(),
                                ])
                                ->collapsible()
                                ->collapsed(false),
                        ];
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->slideOver()
                    ->visible(fn () => Auth::user()?->hasAnyRole(['staff', 'admin', 'owner','coach'])),
                Action::make('assignMember')
                    ->label('Atur Member')
                    ->icon('heroicon-o-users')
                    ->form([
                        Select::make('members')
                            ->label('Member')
                            ->placeholder('Pilih member yang akan dihandle')
                            ->options(Member::query()
                                ->with('user')
                                ->get()
                                ->mapWithKeys(function ($member) {
                                    // Tampilkan nama member dengan email
                                    $label = $member->user?->name ?? "Member #{$member->id}";
                                    
                                    if ($member->user?->email) {
                                        $label .= " ({$member->user->email})";
                                    }
                                    
                                    return [$member->id => $label];
                                })
                            )
                            ->multiple() // Relasi Many-to-Many
                            ->preload()
                            ->searchable()
                            // ✅ DEFAULT VALUE dari member yang sudah di-assign
                            ->default(fn (Coach $record) => $record->members->pluck('id')->toArray()),
                    ])
                    ->action(function (Coach $record, array $data): void {
                        // Sinkronkan (sync) relasi members dengan data yang dipilih
                        $record->members()->sync($data['members']);
                    })
                    ->visible(fn () => Auth::user()?->hasAnyRole(['staff', 'admin', 'owner'])),
                ViewAction::make()
                    ->label('')
                    ->button()
                    ->tooltip('Lihat detail')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->size('sm')
                    ->extraAttributes([
                        'class' => 'border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 rounded-lg px-3 py-2'
                    ]),

                EditAction::make()
                    ->label('')
                    ->button()
                    ->tooltip('Edit coach')
                    ->icon('heroicon-o-pencil-square')
                    ->color('primary')
                    ->size('sm')
                    ->extraAttributes([
                        'class' => 'border border-blue-300 text-blue-700 bg-white hover:bg-blue-50 rounded-lg px-3 py-2'
                    ]),

                DeleteAction::make()
                    ->label('')
                    ->button()
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
                        // Hapus user terkait juga
                        $record->user?->delete();
                    }),

                Action::make('assignSchedule')
                    ->label('Atur Jadwal')
                    ->icon('heroicon-o-calendar-days')
                    ->form([
                        Select::make('schedules')
                            ->label('Jadwal Latihan')
                            ->placeholder('Pilih jadwal yang diajar')
                            ->options(TrainingSchedule::pluck('day', 'id')->map(function ($day, $id) {
                                // Tampilkan hari dengan terjemahan (seperti di table)
                                $translatedDay = match ($day) {
                                    'MONDAY' => 'Senin',
                                    'TUESDAY' => 'Selasa',
                                    'WEDNESDAY' => 'Rabu',
                                    'THURSDAY' => 'Kamis',
                                    'FRIDAY' => 'Jumat',
                                    'SATURDAY' => 'Sabtu',
                                    'SUNDAY' => 'Minggu',
                                    default => $day,
                                };
                                // Tambahkan ID dan Place untuk kejelasan di Select
                                $schedule = TrainingSchedule::find($id);
                                return "{$translatedDay} - {$schedule->time} ({$schedule->place})";
                            }))
                            ->multiple() // Relasi Many-to-Many
                            ->preload()
                            // ✅ PERBAIKAN: DEFAULT VALUE
                            ->default(fn (Coach $record) => $record->schedules->pluck('id')->toArray()),
                    ])
                    ->action(function (Coach $record, array $data): void {
                        // Sinkronkan (sync) relasi schedules dengan data yang dipilih
                        $record->schedules()->sync($data['schedules']);
                    })
                    // ✅ PERBAIKAN: VISIBLE DENGAN MULTIPLE ROLES
                    ->visible(fn () => Auth::user()?->hasAnyRole(['staff', 'admin', 'owner'])),
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
                            // Hapus semua user terkait
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
