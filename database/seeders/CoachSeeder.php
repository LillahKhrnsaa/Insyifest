<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Coach;
use Illuminate\Support\Facades\Hash;

class CoachSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data untuk 3 coach baru
        $coachesData = [
            [
                'name' => 'Asri Suci Alfiani',
                'email' => 'asri.suci.alfiani@cikampekswimming.gmail.com',
                'phone' => '085219301750',
                'gender' => 'FEMALE',
                'bio' => ''
            ],
            [
                'name' => 'Kharisma',
                'email' => 'kharisma@cikampekswimming.gmail.com',
                'phone' => '081291846070',
                'gender' => 'FEMALE',
                'bio' => ''
            ],
            [
                'name' => 'Dony',
                'email' => 'dony@cikampekswimming.gmail.com',
                'phone' => '081292626315',
                'gender' => 'MALE',
                'bio' => ''
            ],
            [
                'name' => 'Fabiyan',
                'email' => 'fabiyan@cikampekswimming.gmail.com',
                'phone' => '089638534624',
                'gender' => 'MALE',
                'bio' => ''
            ],
            [
                'name' => 'Fauzan',
                'email' => 'fauzan@cikampekswimming.gmail.com',
                'phone' => '085723836894',
                'gender' => 'MALE',
                'bio' => ''
            ],
            [
                'name' => 'Endah',
                'email' => 'endah@cikampekswimming.gmail.com',
                'phone' => '08978683958',
                'gender' => 'FEMALE',
                'bio' => ''
            ],
            [
                'name' => 'Hafeed',
                'email' => 'hafeed@cikampekswimming.gmail.com',
                'phone' => '089657439609',
                'gender' => 'MALE',
                'bio' => ''
            ],
            [
                'name' => 'Salsa',
                'email' => 'Salsa@cikampekswimming.gmail.com',
                'phone' => '089539724070',
                'gender' => 'FEMALE',
                'bio' => ''
            ],
            [
                'name' => 'Iman Fala',
                'email' => 'iman.fala@cikampekswimming.gmail.com',
                'phone' => '082297010357',
                'gender' => 'MALE',
                'bio' => ''
            ],
            [
                'name' => 'Juan',
                'email' => 'Juan@cikampekswimming.gmail.com',
                'phone' => '089632305678',
                'gender' => 'MALE',
                'bio' => ''
            ],
            [
                'name' => 'Rindy',
                'email' => 'Rindy@cikampekswimming.gmail.com',
                'phone' => '085322328887',
                'gender' => 'FEMALE',
                'bio' => ''
            ],
            [
                'name' => 'Tegar',
                'email' => 'tegar@cikampekswimming.gmail.com',
                'phone' => '085886515053',
                'gender' => 'MALE',
                'bio' => ''
            ],
            [
                'name' => 'Riana',
                'email' => 'Riana@cikampekswimming.gmail.com',
                'phone' => '081292807172',
                'gender' => 'FEMALE',
                'bio' => ''
            ],
            

        ];

        foreach ($coachesData as $data) {
            // 1. Buat record di tabel users
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'phone' => $data['phone'],
                    'gender' => $data['gender'],
                    'password' => Hash::make('1234567890'),
                ]
            );

            // 2. Assign role 'coach' ke user tersebut
            $user->assignRole('coach');

            // 3. Buat record di tabel coaches yang berelasi
            Coach::create([
                'user_id' => $user->id,
                'bio' => $data['bio'],
            ]);
        }
        
        // --- FUNGSI TAMBAHAN SESUAI PERMINTAAN ---
        // Assign user dengan ID=3 (yang sudah ada dari UserSeeder)
        // untuk dibuatkan profil coach-nya juga.
        
        $existingUser = User::find(3);

        if ($existingUser) {
            // Menggunakan firstOrCreate untuk membuat profil jika belum ada,
            // dan mencegah duplikasi jika seeder dijalankan lagi.
            Coach::firstOrCreate(
                ['user_id' => $existingUser->id],
                ['bio' => 'Pelatih utama yang datanya sudah ada di tabel user.']
            );
        }
    }
}