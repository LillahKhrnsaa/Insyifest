<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TrainingSchedule;

class TrainingScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['day' => 'SENIN',  'time' => '14:00:00', 'place' => 'Pucung'],
            ['day' => 'SELASA', 'time' => '15:30:00', 'place' => 'Pucung'],
            ['day' => 'RABU',   'time' => '14:00:00', 'place' => 'Tirta Santika'],
            ['day' => 'KAMIS',  'time' => '15:30:00', 'place' => 'Tirta Santika'],
            ['day' => 'JUMAT',  'time' => '15:30:00', 'place' => 'Pucung'],
            ['day' => 'SABTU',  'time' => '13:30:00', 'place' => 'Pucung'],
            ['day' => 'MINGGU', 'time' => '08:00:00', 'place' => 'Pucung'],
            ['day' => 'MINGGU', 'time' => '15:30:00', 'place' => 'Pucung'],
            ['day' => 'SELASA', 'time' => '15:30:00', 'place' => 'Tirta Sari (Kelas Prestasi dan Pro)'],
            ['day' => 'KAMIS',  'time' => '15:30:00', 'place' => 'Tirta Sari (Kelas Prestasi dan Pro)'],
            ['day' => 'MINGGU', 'time' => '13:00:00', 'place' => 'Tirta Sari (Kelas Prestasi dan Pro)'],
            ['day' => 'SABTU',  'time' => '15:30:00', 'place' => 'Tirta Sari (Kelas Prestasi dan Pro)'],
            ['day' => 'MINGGU', 'time' => '15:30:00', 'place' => 'Tirta Sari (Kelas Mahir)'],
        ];

        foreach ($data as $item) {
            TrainingSchedule::create($item);
        }
    }
}
