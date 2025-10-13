<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TrainingSchedule;
use App\Models\Coach; // 1. PASTIKAN MODEL COACH DI-IMPORT
use Illuminate\Support\Facades\DB;

class TrainingScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // KOSONGKAN TABEL DULU AGAR TIDAK DUPLIKAT JIKA SEEDER DIJALANKAN ULANG
        TrainingSchedule::query()->delete();
        DB::table('coach_training_schedule')->truncate();


        // 2. AMBIL DATA PELATIH YANG SUDAH ADA.
        // Kita ambil semua pelatih, jika tidak ada, seeder tidak akan jalan.
        $coaches = Coach::all();

        if ($coaches->isEmpty()) {
            $this->command->info('Tidak ada data pelatih ditemukan. Silakan jalankan CoachSeeder terlebih dahulu.');
            return;
        }

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
            // Buat jadwal baru
            $schedule = TrainingSchedule::create($item);

            // 3. HUBUNGKAN JADWAL DENGAN PELATIH
            // Kita akan hubungkan setiap jadwal dengan pelatih secara acak sebagai contoh.
            // 'attach' akan mengisi data ke tabel pivot coach_training_schedule.
            $schedule->coaches()->attach($coaches->random()->id);
        }
    }
}