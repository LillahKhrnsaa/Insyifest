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
                'email' => 'asri.suci@cikampekswimming.gmail.com',
                'phone' => '085219301750',
                'gender' => 'FEMALE',
                'bio' => ''
            ],
            [
                'name' => 'Febri Maharatri Kharisma Amalia Suci',
                'email' => 'febri.maharatri@cikampekswimming.gmail.com',
                'phone' => '081291846070',
                'gender' => 'FEMALE',
                'bio' => ''
            ],
            [
                'name' => 'Dony Adhi Nugroho Hidayat',
                'email' => 'dony.adhi@cikampekswimming.gmail.com',
                'phone' => '081292626315',
                'gender' => 'MALE',
                'bio' => ''
            ],
            [
                'name' => 'Fabiyan Fahliyansyah',
                'email' => 'fabiyan.fahliyansyah@cikampekswimming.gmail.com',
                'phone' => '089638534624',
                'gender' => 'MALE',
                'bio' => ''
            ],
            [
                'name' => 'Fauzan Noer Afrizal',
                'email' => 'fauzan.noer@cikampekswimming.gmail.com',
                'phone' => '08972703645',
                'gender' => 'MALE',
                'bio' => ''
            ],
            [
                'name' => 'Endah Khairun Nissa',
                'email' => 'endah.khairun@cikampekswimming.gmail.com',
                'phone' => '08978683958',
                'gender' => 'FEMALE',
                'bio' => ''
            ],
            [
                'name' => 'Mohammad Hafid Siddik',
                'email' => 'mohammad.hafid@cikampekswimming.gmail.com',
                'phone' => '089657439609',
                'gender' => 'MALE',
                'bio' => ''
            ],
            [
                'name' => 'Salsa Ramdiyani Eki Putri',
                'email' => 'salsa.ramdiyani@cikampekswimming.gmail.com',
                'phone' => '089539724070',
                'gender' => 'FEMALE',
                'bio' => ''
            ],
            [
                'name' => 'Iman Fala Handoko',
                'email' => 'iman.fala@cikampekswimming.gmail.com',
                'phone' => '082297010357',
                'gender' => 'MALE',
                'bio' => ''
            ],
            [
                'name' => 'Juan Njawi Wandhira',
                'email' => 'juan.njawi@cikampekswimming.gmail.com',
                'phone' => '089632305678',
                'gender' => 'MALE',
                'bio' => ''
            ],
            [
                'name' => 'Rindy Antika',
                'email' => 'rindy.antika@cikampekswimming.gmail.com',
                'phone' => '085322328887',
                'gender' => 'FEMALE',
                'bio' => ''
            ],
            [
                'name' => 'Muhammad Tegar Satrio',
                'email' => 'muhammad.tegar@cikampekswimming.gmail.com',
                'phone' => '085886515053',
                'gender' => 'MALE',
                'bio' => ''
            ],
            [
                'name' => 'Arsad Fany Ibrahim',
                'email' => 'arsad.fany@cikampekswimming.gmail.com',
                'phone' => '0895348074062',
                'gender' => 'MALE',
                'bio' => ''
            ],
            [
                'name' => 'Alif Ikrar Prabu',
                'email' => 'alif.ikrar@cikampekswimming.gmail.com',
                'phone' => '083804665952',
                'gender' => 'MALE',
                'bio' => ''
            ],
            [
                'name' => 'Adinda Putri Larasati',
                'email' => 'adinda.putri@cikampekswimming.gmail.com',
                'phone' => '085885060925',
                'gender' => 'FEMALE',
                'bio' => ''
            ],
            [
                'name' => 'Moh Lutfi Adistira Wirawan',
                'email' => 'moh.luthfi@cikampekswimming.gmail.com',
                'phone' => '081293438506',
                'gender' => 'MALE',
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