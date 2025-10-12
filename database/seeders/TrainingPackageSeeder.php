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
                'price' => 200000.00,
                'description' => '4x Pertemuan/bulan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Advanced Training Package',
                'price' => 350000.00,
                'description' => '8x Pertemuan/bulan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Professional Training Package',
                'price' => 40000.00,
                'description' => '12x Pertemuan/bulan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
