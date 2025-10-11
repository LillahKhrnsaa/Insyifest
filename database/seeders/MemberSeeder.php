<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Member;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data untuk user yang akan dijadikan member
        $membersData = [
            [
                'name' => 'Reihan', 
                'email' => 'member@cikampekswimming.gmail.com',
                'phone' => '0844444444', 
                'gender' => 'FEMALE'
            ],
            [
                'name' => 'Budi Santoso', 
                'email' => 'budi.s@example.com',
                'phone' => '081234567891', 
                'gender' => 'MALE'
            ],
            [
                'name' => 'Siti Aminah', 
                'email' => 'siti.a@example.com',
                'phone' => '081234567892', 
                'gender' => 'FEMALE'
            ],
            [
                'name' => 'Joko Widodo', 
                'email' => 'joko.w@example.com',
                'phone' => '081234567893', 
                'gender' => 'MALE'
            ],
        ];

        // Looping untuk membuat setiap user dan data membernya
        foreach ($membersData as $data) {
            // 1. Buat atau cari user terlebih dahulu
            // Menggunakan firstOrCreate untuk menghindari duplikat jika seeder dijalankan ulang
            $user = User::firstOrCreate(
                ['phone' => $data['phone']], // Kunci unik untuk mencari user
                [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'gender' => $data['gender'],
                    'password' => Hash::make('1234567890'), // Password default
                ]
            );

            // 2. Assign role 'member' ke user tersebut
            // Cek dulu jika user belum punya role ini
            if (!$user->hasRole('member')) {
                $user->assignRole('member');
            }

            // 3. Buat data member yang terhubung dengan user di atas
            Member::firstOrCreate(
                ['user_id' => $user->id], // Kunci unik untuk mencari member
                [
                    'training_package_id' => rand(1, 3), // ID paket acak antara 1, 2, atau 3
                    'status' => 'AKTIF',
                    'start_date' => Carbon::today()->subDays(rand(0, 15)), // Tanggal mulai acak
                    'end_date' => Carbon::today()->addMonths(rand(1, 3)), // Tanggal selesai acak 1-3 bulan dari sekarang
                ]
            );
        }
    }
}

