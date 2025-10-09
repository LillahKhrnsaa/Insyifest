<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('members')->delete();

        $members = [
            [
                'user_id' => 1,
                'training_package_id' => 1,
                'status' => 'AKTIF',
                'start_date' => '2024-01-15 08:00:00',
                'end_date' => '2025-01-15 08:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'training_package_id' => 2,
                'status' => 'AKTIF',
                'start_date' => '2024-03-10 09:00:00',
                'end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'training_package_id' => 1,
                'status' => 'TIDAK_AKTIF',
                'start_date' => '2023-05-20 10:00:00',
                'end_date' => '2024-05-20 10:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('members')->insert($members);
    }
}