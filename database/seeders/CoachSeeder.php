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
                'name' => 'Budi Pelatih',
                'email' => 'budi.coach@example.com',
                'phone' => '081211112222',
                'gender' => 'MALE',
                'bio' => 'Pelatih renang gaya bebas dengan pengalaman 5 tahun.'
            ],
            [
                'name' => 'Siti Pelatih',
                'email' => 'siti.coach@example.com',
                'phone' => '081233334444',
                'gender' => 'FEMALE',
                'bio' => 'Spesialis gaya kupu-kupu dan teknik pernapasan.'
            ],
            [
                'name' => 'Agus Pelatih',
                'email' => 'agus.coach@example.com',
                'phone' => '081255556666',
                'gender' => 'MALE',
                'bio' => 'Berfokus pada pembinaan atlet junior dan pemula.'
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
                    'password' => Hash::make('password123'),
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