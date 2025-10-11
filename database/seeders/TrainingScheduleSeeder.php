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
        $days = ['SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT', 'SABTU', 'MINGGU'];

        foreach ($days as $day) {
            TrainingSchedule::create([
                'day' => $day,
                'time' => '08:00:00',
                'place' => 'Lapangan Utama', // ubah sesuai kebutuhan
            ]);
        }
    }
}
