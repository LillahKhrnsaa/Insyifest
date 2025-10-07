<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrainingPackageSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('training_packages')->insert([
            [
                'name' => 'Basic Training Package',
                'price' => 199000.00,
                'description' => 'Cocok untuk pemula yang ingin memahami dasar-dasar pelatihan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Advanced Training Package',
                'price' => 499000.00,
                'description' => 'Tingkatkan kemampuan dengan materi lanjutan dan sesi praktik intensif.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Professional Training Package',
                'price' => 999000.00,
                'description' => 'Program eksklusif untuk profesional yang ingin sertifikasi dan mentoring pribadi.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
