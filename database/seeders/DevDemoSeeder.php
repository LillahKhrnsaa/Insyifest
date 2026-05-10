<?php

namespace Database\Seeders;

use App\Models\Coach;
use App\Models\Member;
use App\Models\TrainingPackage;
use App\Models\TrainingSchedule;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

use Illuminate\Support\Facades\Schema;

class DevDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Bersihkan Data Lama (Opsional tapi disarankan buat Dev)
        $this->command->info('Membersihkan data lama...');
        Schema::disableForeignKeyConstraints();
        
        User::truncate();
        Coach::truncate();
        Member::truncate();
        TrainingPackage::truncate();
        TrainingSchedule::truncate();
        DB::table('member_training_assignments')->truncate();
        DB::table('coach_training_schedule')->truncate();
        
        Schema::enableForeignKeyConstraints();

        // 2. Jalankan Role & Permission
        $this->command->info('Menjalankan Role & Permission Seeder...');
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
        ]);

        // 3. Buat User Inti (Owner & Staff)
        $this->command->info('Membuat User Inti...');
        $owner = User::create([
            'name' => 'Luthfi (Owner)',
            'email' => 'owner@cikampekswimming.gmail.com',
            'phone' => '0811111111',
            'gender' => 'MALE',
            'password' => Hash::make('1234567890'),
        ]);
        $owner->assignRole('owner');

        $staff = User::create([
            'name' => 'IT Team (Staff)',
            'email' => 'it@cikampekswimming.gmail.com',
            'phone' => '0855555555',
            'gender' => 'MALE',
            'password' => Hash::make('1234567890'),
        ]);
        $staff->assignRole('staff');

        // 4. Buat Training Packages
        $this->command->info('Membuat Training Packages...');
        $packages = [
            ['name' => '4x Pertemuan', 'price' => 200000, 'description' => '4x Pertemuan/bulan'],
            ['name' => '8x Pertemuan', 'price' => 350000, 'description' => '8x Pertemuan/bulan'],
            ['name' => '12x Pertemuan', 'price' => 400000, 'description' => '12x Pertemuan/bulan'],
        ];
        foreach ($packages as $pkg) {
            TrainingPackage::create($pkg);
        }
        $allPackages = TrainingPackage::all();

        // 5. Buat 5 Coach
        $this->command->info('Membuat 5 Coach...');
        $coaches = [];
        for ($i = 1; $i <= 5; $i++) {
            $userCoach = User::create([
                'name' => "Coach Demo $i",
                'email' => "coach$i@demo.com",
                'phone' => "08222222220$i",
                'gender' => ($i % 2 == 0) ? 'FEMALE' : 'MALE',
                'password' => Hash::make('1234567890'),
            ]);
            $userCoach->assignRole('coach');
            
            $coaches[] = Coach::create([
                'user_id' => $userCoach->id,
            ]);
        }

        // 6. Buat Jadwal Latihan
        $this->command->info('Membuat Jadwal Latihan...');
        $days = ['SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT', 'SABTU', 'MINGGU'];
        foreach ($days as $index => $day) {
            $schedule = TrainingSchedule::create([
                'day' => $day,
                'time' => '16:00:00',
                'place' => ($index % 2 == 0) ? 'Pucung' : 'Tirta Santika',
            ]);
            
            // Assign coach secara berurutan agar semua coach kebagian jadwal
            $coach = $coaches[$index % 5];
            $schedule->coaches()->attach($coach->id, ['quota' => 5]); // Set Quota 5
        }
        $allSchedules = TrainingSchedule::all();

        // 7. Buat 5 Member & Hubungkan ke Coach
        $this->command->info('Membuat 5 Member & Assign ke Coach + Jadwal...');
        $pemulaPackage = TrainingPackage::where('name', '4x Pertemuan')->first();
        
        for ($i = 1; $i <= 5; $i++) {
            $userMember = User::create([
                'name' => "Member Demo $i",
                'email' => "member$i@demo.com",
                'phone' => "08333333330$i",
                'gender' => ($i % 2 == 0) ? 'FEMALE' : 'MALE',
                'password' => Hash::make('1234567890'),
            ]);
            $userMember->assignRole('member');

            $member = Member::create([
                'user_id' => $userMember->id,
                'training_package_id' => $pemulaPackage->id, // Semua Pemula
                'status' => 'AKTIF',
                'start_date' => now()->startOfMonth(),
            ]);

            // Assign ke coach (1 member per coach)
            $coach = $coaches[$i-1];
            $member->coaches()->attach($coach->id);

            // Cari jadwal yang dimiliki coach tersebut untuk di-assign ke member (member_schedules)
            $coachSchedule = DB::table('coach_training_schedule')
                ->where('coach_id', $coach->id)
                ->first();
            
            if ($coachSchedule) {
                DB::table('member_schedules')->insert([
                    'member_id' => $member->id,
                    'coach_id' => $coach->id,
                    'training_schedule_id' => $coachSchedule->training_schedule_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('DevDemoSeeder Berhasil Dijalankan!');
        $this->command->warn('Login Staff: 0855555555 / 1234567890');
        $this->command->warn('Login Owner: 0811111111 / 1234567890');
        $this->command->warn('Semua password demo diset ke: 1234567890');
    }
}
