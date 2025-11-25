<?php

namespace App\Filament\Resources\Members\Tables;

use App\Filament\Widgets\RaportChart;
use App\Models\Raport;
use Filament\Actions\Action;
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
                Stack::make([

                    // 1. Foto Profil
                    ImageColumn::make('user.photo_path')->label('Foto')
                        ->circular()->imageHeight(40)->disk('public')
                        ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->user->name ?? 'M')),
    
                    // 2. Nama, Email, & Telepon
                    TextColumn::make('user.name')->label('Nama Member')
                        ->searchable()->sortable()->weight('bold')->color('primary')
                        ->description(fn ($record) => $record->user->email) // Tampilkan email di bawah nama
                        ->tooltip(fn ($record) => "Telepon: " . ($record->user->phone ?? '-')),
    
                    // 3. Paket Latihan
                    TextColumn::make('trainingPackage.name')->label('Paket Latihan')
                        ->badge()->color('success')->default('Belum ada paket')->searchable(),
    
                    // 4. Status Akun (User Active/Inactive) - Toggle Interaktif
                    ToggleColumn::make('user.active')->label('Akun Aktif')
                        ->tooltip('Klik untuk mengaktifkan/menonaktifkan akun user')
                        ->onColor('success')->offColor('danger'),
    
                    // 5. Status Keanggotaan (Member AKTIF/TIDAK_AKTIF) - Select Interaktif
                    SelectColumn::make('status')->label('Status Member')
                        ->options(['AKTIF' => 'Aktif', 'TIDAK_AKTIF' => 'Tidak Aktif'])
                        ->sortable(),
                    
                    // 6. Dibuat Pada (Toggleable)
                    TextColumn::make('created_at')->label('Dibuat Pada')
                        ->dateTime('d M Y')->sortable()
                        ->toggleable(isToggledHiddenByDefault: true),
                ]), 
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->filters([
                // Filter berdasarkan status keanggotaan
                SelectFilter::make('status')->label('Status Member')
                    ->options(['AKTIF' => 'Aktif', 'TIDAK_AKTIF' => 'Tidak Aktif']),
                
                // Filter berdasarkan paket latihan
                SelectFilter::make('training_package_id')->label('Paket Latihan')
                    ->relationship('trainingPackage', 'name')->searchable()->preload(),

                // Filter canggih berdasarkan status akun user
                SelectFilter::make('active')->label('Status Akun')
                    ->options([1 => 'Aktif', 0 => 'Nonaktif'])
                    ->query(fn (Builder $query, array $data) =>
                        $data['value'] !== null ? $query->whereHas('user', fn ($q) => $q->where('active', $data['value'])) : null
                    ),
            ])
            // ✅ SINTAKS BENAR: Menggunakan ->actions()
            ->recordActions([
                Action::make('lihat_raport')
                    ->label('')
                    ->button()
                    ->tooltip('Lihat Raport')
                    ->icon('heroicon-o-chart-bar')
                    ->color('info')
                    ->modalHeading(fn ($record) => 'Raport Member: ' . ($record->user->name ?? 'N/A'))
                    ->modalWidth('5xl')
                    ->form(function ($record) {
                        
                        // 1. Ambil ID member di scope ini untuk digunakan di closure lain
                        $memberId = $record->id; 
                        $refreshHandler = fn ($livewire) => $livewire->dispatch('refresh-raport-chart');
                        
                        
                        return [
                            // 1. FILTER CONTROLS
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
                                            ->afterStateUpdated($refreshHandler), // Panggil handler

                                        TextInput::make('year')
                                            ->label('Tahun')
                                            ->numeric()
                                            ->default(now()->year)
                                            ->minValue(2000)
                                            ->maxLength(4)
                                            ->required()
                                            ->live()
                                            ->afterStateUpdated($refreshHandler), // Panggil handler
                                    ]),
                                ])->columnSpanFull(),
                            
                            // 2. PLACEHOLDER (Debug/Detail Data)
                            Placeholder::make('raport_info')
                                ->label('Detail Data Raport Terpilih')
                                // 💡 Menggunakan $memberId dari scope luar
                                ->content(function ($get) use ($memberId) { 
                                    $gaya = $get('gaya') ?? 'gaya_bebas_50';
                                    $year = $get('year') ?? now()->year;

                                    // Query data
                                    $raports = Raport::where('member_id', $memberId)
                                        ->where('gaya', $gaya)
                                        ->where('year', $year)
                                        ->orderBy('month') // Anda bisa gunakan query yang lebih sederhana jika data lengkap
                                        ->get();

                                    if ($raports->isEmpty()) {
                                        return 'Tidak ada data raport untuk gaya dan tahun yang dipilih.';
                                    }

                                    // Format output (kode Placeholder Anda)
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

                            Section::make('Detail Data Raport Terpilih')
                                ->schema([
                                    Livewire::make(\App\Filament\Widgets\RaportTable::class, fn (SchemaComponent $component, Get $get) => [
                                        'memberId' => $component->getRecord()?->id ?? 0,
                                        'gaya'     => $get('gaya') ?? 'gaya_bebas_50',
                                        'year'     => $get('year') ?? now()->year,
                                    ])
                                    ->key(fn($r, $get) => 'table-' . ($r?->id ?? '0') . '-' . $get('gaya') . '-' . $get('year'))
                                    ->lazy()
                                    ->dehydrated(false)
                                    ->live()
                                ]),
                                
                            // 3. WIDGET CHARTS (Grid 2 Kolom)
                            Grid::make(2)->schema([
                                
                                // CHART 1: Waktu Tempuh
                                Section::make('Grafik Waktu Tempuh (Detik)')
                                    ->schema([
                                        Livewire::make(\App\Filament\Widgets\RaportChart::class, fn (SchemaComponent $component, Get $get) => [
                                            // ambil record dari schema/component context (record modal/action)
                                            'memberId' => $component->getRecord()?->id ?? 0,
                                            'gaya'     => $get('gaya') ?? 'gaya_bebas_50',
                                            'year'     => $get('year') ?? now()->year,
                                        ])
                                        ->key(fn($r, $get) => 'chart-value-' . ($r?->id ?? '0') . '-' . $get('gaya') . '-' . $get('year'))
                                        ->lazy()
                                        ->dehydrated(false)
                                        ->live()

                                    ]),

                                Section::make('Grafik Volume, Peaking, Intensity')
                                ->schema([
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
                                    ->live()
                                ]),
                                    
                            ])->columnSpanFull(), 

                        ];
                    })
                    ->before(function ($livewire) {
                        // Memaksa refresh pada widget saat modal akan dibuka
                        $livewire->dispatch('refresh-raport-chart');
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),

                ViewAction::make()->label('')->button()->tooltip('Lihat detail')->icon('heroicon-o-eye'),
                EditAction::make()->label('')->button()->tooltip('Edit Member')->icon('heroicon-o-pencil-square'),
                DeleteAction::make()->label('')->button()->tooltip('Hapus Member')
                    ->icon('heroicon-o-trash')->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Member')
                    ->modalDescription('Yakin ingin menghapus member ini? Data user terkait juga akan terhapus!')
                    ->modalSubmitActionLabel('Ya, Hapus')
                    // HOOK PENTING: Hapus user terkait sebelum menghapus member
                    ->before(fn ($record) => $record->user?->delete()), 
            ])
            // ✅ SINTAKS BENAR: Menggunakan ->bulkActions()
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Hapus Pilihan')
                        ->requiresConfirmation()
                        ->modalHeading('Hapus Member Terpilih')
                        ->modalDescription('Yakin ingin menghapus semua member yang dipilih? Data user terkait juga akan terhapus!')
                        // HOOK PENTING: Hapus semua user terkait
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
