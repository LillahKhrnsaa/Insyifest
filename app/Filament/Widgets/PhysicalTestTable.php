<?php

namespace App\Filament\Widgets;

use App\Models\PhysicalTest;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section as ComponentsSection;
use Illuminate\Database\Eloquent\Builder;

class PhysicalTestTable extends TableWidget
{
    protected static ?string $heading = 'Riwayat Tes Fisik Atlet';
    public ?int $memberId = null;
    public ?int $year = null;

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                if (!$this->memberId) {
                    return PhysicalTest::query()->whereRaw('1 = 0');
                }

                return PhysicalTest::query()
                    ->where('member_id', $this->memberId)
                    ->where('year', $this->year ?? now()->year)
                    ->orderByRaw("
                        CASE month 
                            WHEN 'januari' THEN 1 WHEN 'februari' THEN 2 WHEN 'maret' THEN 3 
                            WHEN 'april' THEN 4 WHEN 'mei' THEN 5 WHEN 'juni' THEN 6 
                            WHEN 'juli' THEN 7 WHEN 'agustus' THEN 8 WHEN 'september' THEN 9 
                            WHEN 'oktober' THEN 10 WHEN 'november' THEN 11 WHEN 'desember' THEN 12 
                            ELSE 13 END
                    ");
            })
            ->columns([
                Tables\Columns\TextColumn::make('month')
                    ->label('Bulan')
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('vo2max')
                    ->label('VO2 Max')
                    ->numeric(2)
                    ->suffix(' ml/kg')
                    ->color('success')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('sprint_20m')->label('Sprint')->suffix(' s'),
                Tables\Columns\TextColumn::make('push_up')->label('P.Up'),
                Tables\Columns\TextColumn::make('sit_up')->label('S.Up'),
                Tables\Columns\TextColumn::make('shuttle_run')->label('Agility')->suffix(' s'),
            ])
            ->headerActions([
                // MENGGUNAKAN ACTION() SEBAGAI PENGGANTI MUTATE
                CreateAction::make()
                    ->label('Input Tes Fisik')
                    ->form($this->getPhysicalFormSchema())
                    ->action(function (array $data): void {
                        // Gabungkan data dari form dengan ID Member & Tahun dari filter
                        $data['member_id'] = $this->memberId;
                        $data['year'] = $this->year ?? now()->year;
                        
                        PhysicalTest::create($data);
                    })
                    ->successNotificationTitle('Data tes fisik berhasil disimpan'),
            ])
            ->actions([
                EditAction::make()
                    ->form($this->getPhysicalFormSchema()),
                DeleteAction::make(),
            ])
            ->paginated(false);
    }

    protected function getPhysicalFormSchema(): array
    {
        return [
            Section::make('Informasi Periode')
                ->schema([
                    Select::make('month')
                        ->label('Bulan Tes')
                        ->options([
                            'januari' => 'Januari', 'februari' => 'Februari', 'maret' => 'Maret',
                            'april' => 'April', 'mei' => 'Mei', 'juni' => 'Juni',
                            'juli' => 'Juli', 'agustus' => 'Agustus', 'september' => 'September',
                            'oktober' => 'Oktober', 'november' => 'November', 'desember' => 'Desember',
                        ])
                        ->required(),
                ]),

            Section::make('Hasil Pengukuran Fisik')
                ->schema([
                    Grid::make(3)->schema([
                        TextInput::make('bleep_level')->label('Level')->numeric()->live()->required(),
                        TextInput::make('bleep_shuttle')->label('Shuttle')->numeric()->live()->required(),
                        TextInput::make('vo2max')->label('VO2Max (Auto)')->placeholder('Otomatis')->disabled()->dehydrated(false),
                    ]),

                    Grid::make(4)->schema([
                        TextInput::make('sprint_20m')->label('Sprint 20m')->numeric(),
                        TextInput::make('push_up')->label('Push Up')->numeric(),
                        TextInput::make('sit_up')->label('Sit Up')->numeric(),
                        TextInput::make('shuttle_run')->label('Agility')->numeric(),
                    ]),

                    Grid::make(2)->schema([
                        TextInput::make('v_sit_reach')->label('Flexibility (cm)')->numeric(),
                        TextInput::make('run_300m')->label('Run 300m (s)')->numeric(),
                    ]),

                    Textarea::make('note')->label('Catatan')->rows(2),
                ]),
        ];
    }
}