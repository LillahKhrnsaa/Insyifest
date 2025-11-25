<?php

namespace App\Filament\Widgets;

use App\Models\Raport;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Reactive;

class RaportChart extends ChartWidget
{


    protected ?string $heading = 'Grafik Waktu Tempuh';

    protected static ?int $sort = 1;
    
    public ?int $memberId = null; 
    public ?string $gaya = null; // Ganti default menjadi null
    public ?int $year = null;


    protected function getListeners(): array
    {
        // Ketika 'refresh-raport-chart' dipicu, panggil metode $refresh
        return [
            'refresh-raport-chart' => '$refresh',
        ];
    }

    protected function getData(): array
    {
        Log::info('RaportChart called', [
            'memberId' => $this->memberId,
            'gaya' => $this->gaya,
            'year' => $this->year,
        ]);
        if (!$this->memberId) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $this->year = $this->year ?? now()->year;

        $raports = Raport::where('member_id', $this->memberId)
            ->where('gaya', $this->gaya)
            ->where('year', $this->year)
            // 💡 Tambahkan filter untuk data null atau nol
            ->whereNotNull('value')
            ->where('value', '>', 0)
            ->orderByRaw("CASE month 
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
                ELSE 13 END")
            ->get();

        if ($raports->isEmpty()) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        // 💡 KOREKSI UTAMA: Lakukan map eksplisit untuk casting float
        $dataValues = $raports->pluck('value')->map(fn($v) => (float) $v)->toArray();

        Log::info('RaportChart final data', ['values' => $dataValues]);

        return [
            'datasets' => [
                [
                    'label' => 'Waktu (detik)',
                    'data' => $dataValues, // Menggunakan array yang sudah di-cast
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'tension' => 0.4,
                ],
            ],
            'labels' => $raports->pluck('month')->map(fn($m) => ucfirst($m))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'reverse' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Waktu (detik)',
                    ],
                ],
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Bulan',
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
        ];
    }
}