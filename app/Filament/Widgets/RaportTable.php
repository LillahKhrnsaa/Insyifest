<?php

namespace App\Filament\Widgets;

use App\Models\Coach;
use App\Models\Member;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Raport;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\TextColumn;

class RaportTable extends TableWidget
{
    // Filters
    public ?int $memberId = null;
    public ?string $gaya = 'gaya_bebas_50';
    public ?int $year = null;

    protected static ?string $heading = 'Detail Data Raport';

    public function mount(?int $memberId = null, ?string $gaya = null, ?int $year = null): void
    {
        $this->memberId = $memberId;
        $this->gaya = $gaya ?? 'gaya_bebas_50';
        $this->year = $year ?? now()->year;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function (): Builder {
                if (!$this->memberId) {
                    return Raport::query()->whereRaw('1 = 0');
                }

                return Raport::query()
                    ->where('member_id', $this->memberId)
                    ->where('gaya', $this->gaya)
                    ->where('year', $this->year)
                    ->with(['coach.user'])
                    ->orderByRaw("
                        CASE month 
                            WHEN 'januari' THEN 1 
                            WHEN 'februari' THEN 2 
                            WHEN 'maret' THEN 3 
                            WHEN 'april' THEN 4 
                            WHEN 'mei' THEN 5 
                            WHEN 'juni' THEN 6 
                            WHEN 'juli' THEN 7 
                            WHEN 'agustus' THEN 8 
                            WHEN 'september' THEN 9 
                            WHEN 'oktober' THEN 10 
                            WHEN 'november' THEN 11 
                            WHEN 'desember' THEN 12 
                            ELSE 99 
                        END
                    ");

            })

            ->columns([
                TextColumn::make('month')
                    ->label('Bulan')
                    ->badge()
                    ->formatStateUsing(fn ($state) => ucfirst($state)),

                TextColumn::make('value')
                    ->label('Waktu')
                    ->formatStateUsing(function ($state) {
                        $minutes = floor($state / 60);
                        $seconds = $state - ($minutes * 60);
                        return sprintf('%02d:%05.2f', $minutes, $seconds);
                    })
                    ->icon('heroicon-m-clock'),

                TextColumn::make('volume')
                    ->label('Volume')
                    ->suffix(' meter'),

                TextColumn::make('intensity')
                    ->label('Intensitas')
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state >= 80 => 'success',
                        $state >= 60 => 'warning',
                        default => 'danger',
                    }),

                TextColumn::make('peaking')
                    ->label('Peaking'),

                TextColumn::make('coach.user.name')
                    ->label('Coach')
                    ->default('-'),

                TextColumn::make('note')
                    ->label('Catatan')
                    ->limit(40)
                    ->tooltip(fn ($state) => $state)
                    ->default('-')
                    ->toggleable(),
            ])

            ->paginated(false)
            ->striped()

            /*
            |--------------------------------------------------------------------------
            | HEADER ACTIONS (CREATE)
            |--------------------------------------------------------------------------
            */
            ->headerActions([
                Action::make('create')
                    ->label('Tambah Data')
                    ->icon('heroicon-m-plus')
                    ->form($this->raportForm()) // Create form
                    ->action(function ($data) {
                        $data['member_id'] = $this->memberId;
                        $data['gaya'] = $this->gaya;
                        $data['year'] = $this->year;

                        Raport::create($data);
                    }),
            ])

            /*
            |--------------------------------------------------------------------------
            | ROW ACTIONS (EDIT + DELETE)
            |--------------------------------------------------------------------------
            */
            ->actions([
                EditAction::make()
                    ->label('Edit')
                    ->form($this->raportForm(isEdit: true))
                    ->using(function (Raport $record, array $data) {
                        // Lock field penting
                        $data['member_id'] = $this->memberId;
                        $data['gaya'] = $this->gaya;
                        $data['year'] = $this->year;
                        $data['month'] = $record->month;

                        // Update
                        $record->update($data);

                        return $record;
                    }),


                DeleteAction::make(),
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | FORM SCHEMA (CREATE + EDIT)
    |--------------------------------------------------------------------------
    */

    private function raportForm(bool $isEdit = false): array
    {
        // Bulan lengkap
        $allMonths = [
            'januari' => 'Januari',
            'februari' => 'Februari',
            'maret' => 'Maret',
            'april' => 'April',
            'mei' => 'Mei',
            'juni' => 'Juni',
            'juli' => 'Juli',
            'agustus' => 'Agustus',
            'september' => 'September',
            'oktober' => 'Oktober',
            'november' => 'November',
            'desember' => 'Desember'
        ];

        // Ambil bulan yang sudah dipakai oleh member + gaya + tahun ini
        $usedMonths = Raport::where('member_id', $this->memberId)
            ->where('gaya', $this->gaya)
            ->where('year', $this->year)
            ->pluck('month')
            ->toArray();

        // Bulan yang masih available
        $availableMonths = array_diff_key($allMonths, array_flip($usedMonths));

        return [

            // Hidden auto-set
            Hidden::make('member_id'),
            Hidden::make('gaya'),
            Hidden::make('year'),

            // Bulan: Select pada CREATE, Hidden saat EDIT
            $isEdit
                ? Hidden::make('month')
                : Select::make('month')
                    ->label('Bulan')
                    ->options($availableMonths)
                    ->required()
                    ->searchable(),

            // Nilai waktu
            TextInput::make('value')
                ->label('Waktu (detik)')
                ->numeric()
                ->required(),

            TextInput::make('volume')
                ->label('Volume (meter)')
                ->numeric()
                ->required(),

            TextInput::make('intensity')
                ->label('Intensitas')
                ->numeric()
                ->required(),

            TextInput::make('peaking')
                ->label('Peaking')
                ->numeric()
                ->required(),

            Select::make('coach_id')
                ->label('Coach')
                ->options(
                    Coach::with('user')->get()->mapWithKeys(fn ($c) => [
                        $c->id => $c->user->name ?? "Coach #{$c->id}"
                    ])
                )
                ->searchable()
                ->required(),

            Textarea::make('note')
                ->label('Catatan')
                ->rows(3),
        ];
    }
}