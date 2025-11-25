<?php

namespace App\Filament\Widgets;

use App\Models\Raport;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Log;

class RaportVolumeChart extends ChartWidget
{
    protected ?string $heading = 'Grafik Volume, Peaking, & Intensity';

    protected static ?int $sort = 2;

    public ?int $memberId = null;
    public ?string $gaya = null;
    public ?int $year = null;

    protected function getListeners(): array
    {
        return [
            'refresh-raport-chart' => '$refresh',
        ];
    }

    protected function getData(): array
    {
        Log::info('RaportVolumeChart called', [
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

        return [
            'datasets' => [
                [
                    'label' => 'Volume (meter)',
                    'data' => $raports->pluck('volume')->map(fn($v) => (float) $v),
                    'borderColor' => 'rgb(34, 197, 94)',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.2)',
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Peaking (%)',
                    'data' => $raports->pluck('peaking')->map(fn($v) => (float) $v),
                    'borderColor' => 'rgb(236, 72, 153)',
                    'backgroundColor' => 'rgba(236, 72, 153, 0.2)',
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Intensity (%)',
                    'data' => $raports->pluck('intensity')->map(fn($v) => (float) $v),
                    'borderColor' => 'rgb(234, 179, 8)',
                    'backgroundColor' => 'rgba(234, 179, 8, 0.2)',
                    'tension' => 0.4,
                ],
            ],
            'labels' => $raports->pluck('month')->map(fn($m) => ucfirst($m)),
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
                    'title' => [
                        'display' => true,
                        'text' => 'Nilai',
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
