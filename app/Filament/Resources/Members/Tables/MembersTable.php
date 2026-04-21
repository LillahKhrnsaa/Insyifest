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
                    ->label('Coach & Jadwal')
                    ->state(function ($record) {
                        $schedules = \App\Models\MemberSchedule::where('member_id', $record->id)
                            ->with(['coach.user', 'trainingSchedule'])
                            ->get();
                        
                        if ($schedules->isEmpty()) return 'Belum diatur';

                        return $schedules->map(fn($s) => 
                            "{$s->coach->user->name}: " . 
                            match (strtoupper($s->trainingSchedule->day)) {
                                'MONDAY', 'SENIN' => 'Senin',
                                'TUESDAY', 'SELASA' => 'Selasa',
                                'WEDNESDAY', 'RABU' => 'Rabu',
                                'THURSDAY', 'KAMIS' => 'Kamis',
                                'FRIDAY', 'JUMAT' => 'Jumat',
                                'SATURDAY', 'SABTU' => 'Sabtu',
                                'SUNDAY', 'MINGGU' => 'Minggu',
                                default => $s->trainingSchedule->day,
                            } . " " . \Carbon\Carbon::parse($s->trainingSchedule->time)->format('H:i')
                        )->implode(', ');
                    })
                    ->wrap()
                    ->color('info')
                    ->size('xs'),

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
                ActionGroup::make([
                        Action::make('lihat_raport')
                            ->label('Lihat Rapor')
                            ->tooltip('Lihat Raport')
                            ->icon('heroicon-o-chart-bar')
                            ->color('info')
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

                        ViewAction::make()
                            ->label('Lihat Detail')
                            ->tooltip('Lihat detail')
                            ->icon('heroicon-o-eye')
                            ->extraAttributes([
                                'class' => 'border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 rounded-lg px-3 py-2']),

                        EditAction::make()
                            ->label('Edit Member')
                            ->tooltip('Edit Member')
                            ->icon('heroicon-o-pencil-square')
                            ->extraAttributes([
                                'class' => 'border border-blue-300 text-blue-700 bg-white hover:bg-blue-50 rounded-lg px-3 py-2'
                            ]),

                        DeleteAction::make()
                            ->label('Hapus Member')
                            ->tooltip('Hapus Member')
                            ->icon('heroicon-o-trash')
                            ->color('danger')
                            ->requiresConfirmation()
                            ->modalHeading('Hapus Member')
                            ->modalDescription('Yakin ingin menghapus member ini? Data user terkait juga akan terhapus!')
                            ->modalSubmitActionLabel('Ya, Hapus')
                            ->extraAttributes([
                                'class' => 'border border-red-300 text-red-700 bg-white hover:bg-red-50 rounded-lg px-3 py-2'
                            ])
                            ->before(fn ($record) => $record->user?->delete()),
                        ])
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
            ])
            ->defaultSort('created_at', 'desc');
    }
}