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
                'name' => '4x Pertemuan/bulan',
                'price' => 200000.00,
                'description' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '8x Pertemuan/bulan',
                'price' => 350000.00,
                'description' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '12x Pertemuan/bulan',
                'price' => 400000.00,
                'description' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
