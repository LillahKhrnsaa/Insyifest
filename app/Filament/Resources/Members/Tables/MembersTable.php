<?php

namespace App\Filament\Resources\Members\Tables;

use App\Filament\Widgets\RaportChart;
use App\Models\Raport;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Component as SchemaComponent; 
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class MembersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (\Illuminate\Database\Eloquent\Builder $query) {
                $query->leftJoin(
                    'member_training_assignments',
                    'member_training_assignments.member_id',
                    '=',
                    'members.id'
                )
                ->leftJoin(
                    'coaches',
                    'coaches.id',
                    '=',
                    'member_training_assignments.coach_id'
                )
                ->leftJoin(
                    'users as coach_users',
                    'coach_users.id',
                    '=',
                    'coaches.user_id'
                )
                ->leftJoin(
                    'users as member_users',
                    'member_users.id',
                    '=',
                    'members.user_id'
                )
                ->select('members.*')
                ->selectRaw("COALESCE(coach_users.name, '') as coach_user_name,
                             COALESCE(coach_users.id, 0) as coach_user_id")
                ->orderBy('coach_user_name', 'asc')
                ->orderBy('member_users.name', 'asc');
            })
            ->columns([
                TextColumn::make('no.')
                    ->label('No.')
                    ->state(function ($rowLoop, $livewire) {
                        return
                            ($livewire->getTablePage() - 1)
                            * $livewire->getTableRecordsPerPage()
                            + $rowLoop->iteration;
                    })
                    ->alignCenter()
                    ->sortable(false)
                    ->searchable(false),

                TextColumn::make('coach_user_name')
                    ->label('Coach')
                    ->state(fn ($record) => $record->coach_user_name ?: '—')
                    ->badge()
                    ->color('warning')
                    ->icon('heroicon-o-academic-cap')
                    ->sortable(query: fn (Builder $query, string $direction) => $query->reorder()->orderBy('coach_user_name', $direction)->orderBy('member_users.name', 'asc'))
                    ->searchable(query: fn (Builder $query, string $search) => $query->where('coach_users.name', 'like', "%{$search}%"))
                    ->wrap(),

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
                    
                TextColumn::make('user.birth_date')
                    ->label('Tgl. Lahir')
                    ->date('d M Y')
                    ->sortable(),

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

                TextColumn::make('user.father_job')
                    ->label('Pekerjaan Ayah')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-briefcase')
                    ->color('slate')
                    ->toggleable(),

                TextColumn::make('training_summary')
                    ->label('Jadwal')
                    ->state(function ($record) {
                        $coachUserId = $record->coach_user_id ?? 0;

                        // Cari coach yang sesuai dengan baris ini
                        $coach = $record->coaches->first(fn ($c) => $c->user_id == $coachUserId);

                        // Fallback: jika tidak ketemu, ambil coach pertama
                        if (! $coach && $record->coaches->isNotEmpty()) {
                            $coach = $record->coaches->first();
                        }

                        if (! $coach) return null;

                        $memberSchedules = \Illuminate\Support\Facades\DB::table('member_schedules')
                            ->join('training_schedules', 'member_schedules.training_schedule_id', '=', 'training_schedules.id')
                            ->where('member_schedules.member_id', $record->id)
                            ->where('member_schedules.coach_id', $coach->id)
                            ->select('training_schedules.*')
                            ->get();

                        // Fallback backward compat
                        if ($memberSchedules->isEmpty()) {
                            $memberSchedules = $coach->trainingSchedules ?? collect();
                        }

                        if ($memberSchedules->isEmpty()) return null;

                        return $memberSchedules->map(fn ($s) =>
                            match (strtoupper($s->day)) {
                                'MONDAY', 'SENIN'       => 'Senin',
                                'TUESDAY', 'SELASA'     => 'Selasa',
                                'WEDNESDAY', 'RABU'     => 'Rabu',
                                'THURSDAY', 'KAMIS'     => 'Kamis',
                                'FRIDAY', 'JUMAT'       => 'Jumat',
                                'SATURDAY', 'SABTU'     => 'Sabtu',
                                'SUNDAY', 'MINGGU'      => 'Minggu',
                                default                 => $s->day,
                            } . ' ' . \Carbon\Carbon::parse($s->time)->format('H:i')
                        )->implode(',');
                    })
                    ->badge()
                    ->separator(',')
                    ->color('info')
                    ->icon('heroicon-o-clock')
                    ->wrap(),

                TextColumn::make('trainingPackage.name')
                    ->label('Paket Latihan')
                    ->badge()
                    ->color('success')
                    ->default('Belum ada paket')
                    ->searchable(),
                
                ToggleColumn::make('is_active_toggle')
                    ->label('Status Aktif')
                    ->sortable(),
                    
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->hidden()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->filters([
                SelectFilter::make('coach')
                    ->label('Coach / Pelatih')
                    ->options(fn () => \App\Models\Coach::with('user')->get()->pluck('user.name', 'id'))
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['value'])) {
                            $query->whereHas('coaches', function ($query) use ($data) {
                                $query->where('coaches.id', $data['value']);
                            });
                        }
                    })
                    ->searchable()
                    ->preload(),

                SelectFilter::make('status')
                    ->label('Status Member')
                    ->options(['AKTIF' => 'Aktif', 'TIDAK_AKTIF' => 'Tidak Aktif']),
                
                SelectFilter::make('training_package_id')
                    ->label('Paket Latihan')
                    ->relationship('trainingPackage', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('active')
                    ->label('Status Akun')
                    ->options([1 => 'Aktif', 0 => 'Nonaktif'])
                    ->query(fn (Builder $query, array $data) =>
                        $data['value'] !== null ? $query->whereHas('user', fn ($q) => $q->where('active', $data['value'])) : null
                    ),
            ])

            ->recordActions([
                Action::make('lihat_coach')
                    ->label('')
                    ->tooltip('Lihat Coach')
                    ->icon('heroicon-o-users')
                    ->color('warning')
                    ->button()
                    ->modalHeading(fn ($record) => 'Coach untuk: ' . ($record->user->name ?? 'Atlet'))
                    ->modalWidth('2xl')
                    ->infolist(function ($record): array {
                        $coaches = $record->coaches;
                        
                        if ($coaches->isEmpty()) {
                            return [
                                \Filament\Schemas\Components\Section::make()
                                    ->schema([
                                        \Filament\Infolists\Components\TextEntry::make('empty')
                                            ->label('')
                                            ->state('Belum ada coach yang ditugaskan untuk atlet ini.')
                                            ->color('warning'),
                                    ]),
                            ];
                        }

                        return [
                            \Filament\Schemas\Components\Section::make('Daftar Coach')
                                ->schema([
                                    \Filament\Infolists\Components\RepeatableEntry::make('coaches')
                                        ->label('')
                                        ->schema([
                                            \Filament\Infolists\Components\ImageEntry::make('user.photo_path')
                                                ->label('Foto')
                                                ->circular(),
                                            \Filament\Infolists\Components\TextEntry::make('user.name')
                                                ->label('Nama Coach')
                                                ->weight('bold'),
                                            \Filament\Infolists\Components\TextEntry::make('user.phone')
                                                ->label('No. Telepon')
                                                ->icon('heroicon-o-phone')
                                                ->copyable(),
                                        ])
                                        ->columns(3)
                                        ->columnSpanFull(),
                                ]),
                        ];
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),
                Action::make('lihat_raport')
                    ->label('')
                    ->tooltip('Lihat Raport')
                    ->icon('heroicon-o-chart-bar')
                    ->color('info')
                    ->button()
                    ->modalHeading(fn ($record) => 'Raport Member: ' . ($record->user->name ?? 'N/A'))
                    ->modalWidth('5xl')
                    ->form(function ($record) {
                        
                        $memberId = $record->id; 
                        $refreshHandler = fn ($livewire) => $livewire->dispatch('refresh-raport-chart');
                        
                        return [
                            Section::make('Filter Grafik')
                                ->description('Pilih gaya renang dan tahun untuk melihat grafik performa')
                                ->schema([
                                    Grid::make(2)->schema([
                                        Select::make('gaya')
                                            ->label('Gaya Renang & Jarak')
                                            ->options([
                                                'gaya_bebas_50' => 'Gaya Bebas 50m',
                                                'gaya_bebas_100' => 'Gaya Bebas 100m',
                                                'gaya_bebas_200' => 'Gaya Bebas 200m',
                                                'gaya_bebas_400' => 'Gaya Bebas 400m',
                                                'gaya_bebas_800' => 'Gaya Bebas 800m',
                                                'gaya_bebas_1500' => 'Gaya Bebas 1500m',
                                                'gaya_dada_50' => 'Gaya Dada 50m',
                                                'gaya_dada_100' => 'Gaya Dada 100m',
                                                'gaya_dada_200' => 'Gaya Dada 200m',
                                                'gaya_punggung_50' => 'Gaya Punggung 50m',
                                                'gaya_punggung_100' => 'Gaya Punggung 100m',
                                                'gaya_punggung_200' => 'Gaya Punggung 200m',
                                                'gaya_kupu_50' => 'Gaya Kupu 50m',
                                                'gaya_kupu_100' => 'Gaya Kupu 100m',
                                                'gaya_kupu_200' => 'Gaya Kupu 200m',
                                                'gaya_ganti_200' => 'Gaya Ganti 200m',
                                                'gaya_ganti_400' => 'Gaya Ganti 400m',
                                            ])
                                            ->searchable()
                                            ->default('gaya_bebas_50')
                                            ->required()
                                            ->live()
                                            ->afterStateUpdated($refreshHandler),

                                        TextInput::make('year')
                                            ->label('Tahun')
                                            ->numeric()
                                            ->default(now()->year)
                                            ->minValue(2000)
                                            ->maxLength(4)
                                            ->required()
                                            ->live()
                                            ->afterStateUpdated($refreshHandler),
                                    ]),
                                ])->columnSpanFull(),
                            
                            Placeholder::make('raport_info')
                                ->label('Detail Data Raport Terpilih')
                                ->content(function ($get) use ($memberId) { 
                                    $gaya = $get('gaya') ?? 'gaya_bebas_50';
                                    $year = $get('year') ?? now()->year;

                                    $raports = Raport::where('member_id', $memberId)
                                        ->where('gaya', $gaya)
                                        ->where('year', $year)
                                        ->orderBy('month')
                                        ->get();

                                    if ($raports->isEmpty()) {
                                        return 'Tidak ada data raport untuk gaya dan tahun yang dipilih.';
                                    }

                                    $output = '<div class="space-y-2">';
                                    $output .= '<p class="text-sm font-semibold">Total Data: ' . $raports->count() . ' bulan</p>';
                                    $output .= '<div class="grid grid-cols-3 gap-4">';
                                    
                                    foreach ($raports as $raport) {
                                        $minutes = floor($raport->value / 60);
                                        $seconds = $raport->value - ($minutes * 60);
                                        $formattedTime = sprintf('%02d:%05.2f', $minutes, $seconds);
                                        
                                        $output .= '<div class="border rounded p-2">';
                                        $output .= '<p class="font-bold text-primary-600">' . ucfirst($raport->month) . '</p>';
                                        $output .= '<p class="text-sm">⏱️ Waktu: ' . $formattedTime . '</p>';
                                        $output .= '<p class="text-sm">📊 Volume: ' . ($raport->volume ?? '-') . 'm</p>';
                                        $output .= '</div>';
                                    }
                                    
                                    $output .= '</div></div>';

                                    return new HtmlString($output);
                                })
                                ->columnSpanFull(),

                                Livewire::make(\App\Filament\Widgets\RaportTable::class, fn (SchemaComponent $component, Get $get) => [
                                    'memberId' => $component->getRecord()?->id ?? 0,
                                    'gaya'     => $get('gaya') ?? 'gaya_bebas_50',
                                    'year'     => $get('year') ?? now()->year,
                                ])
                                ->key(fn($r, $get) => 'table-' . ($r?->id ?? '0') . '-' . $get('gaya') . '-' . $get('year'))
                                ->lazy()
                                ->dehydrated(false)
                                ->live(),
                                
                            Grid::make(2)->schema([

                                Livewire::make(\App\Filament\Widgets\RaportChart::class, fn (SchemaComponent $component, Get $get) => [
                                    'memberId' => $component->getRecord()?->id ?? 0,
                                    'gaya'     => $get('gaya') ?? 'gaya_bebas_50',
                                    'year'     => $get('year') ?? now()->year,
                                ])
                                ->key(fn($r, $get) => 'chart-value-' . ($r?->id ?? '0') . '-' . $get('gaya') . '-' . $get('year'))
                                ->lazy()
                                ->dehydrated(false)
                                ->live(),

                                Livewire::make(\App\Filament\Widgets\RaportVolumeChart::class, 
                                    fn($component, $get) => [
                                        'memberId' => $component->getRecord()?->id ?? 0,
                                        'gaya' => $get('gaya'),
                                        'year' => $get('year'),
                                    ]
                                )
                                ->key(fn($r, $get) => 'chart-volume-' . ($r?->id ?? '0') . '-' . $get('gaya') . '-' . $get('year'))
                                ->lazy()
                                ->dehydrated(false)
                                ->live(),
                                    
                            ])->columnSpanFull(), 

                        ];
                    })
                    ->before(function ($livewire) {
                        $livewire->dispatch('refresh-raport-chart');
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),

                ViewAction::make()->label('')->button()->tooltip('Lihat detail')->icon('heroicon-o-eye'),
                EditAction::make()->label('')->button()->tooltip('Edit Member')->icon('heroicon-o-pencil-square'),
                DeleteAction::make()->label('')->button()->tooltip('Hapus Member')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Member')
                    ->modalDescription('Yakin ingin menghapus member ini? Data user terkait juga akan terhapus!')
                    ->modalSubmitActionLabel('Ya, Hapus')
                    ->before(fn ($record) => $record->user?->delete()),
            ])
                
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Hapus Pilihan')
                        ->requiresConfirmation()
                        ->modalHeading('Hapus Member Terpilih')
                        ->modalDescription('Yakin ingin menghapus semua member yang dipilih? Data user terkait juga akan terhapus!')
                        ->before(function ($records) {
                            $userIds = $records->pluck('user_id')->filter();
                            if ($userIds->isNotEmpty()) {
                                \App\Models\User::whereIn('id', $userIds)->delete();
                            }
                        }),
                ]),
            ]);
    }
}