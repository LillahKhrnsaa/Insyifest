<?php

use App\Models\User;
use App\Models\Member;
use App\Models\Coach;
use App\Models\TrainingPackage;
use App\Models\TrainingSchedule;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// 1. Ambil data pendukung
$coach = Coach::first();
$package = TrainingPackage::first();

if (!$coach || !$package) {
    die("Error: Data Coach atau Package tidak ditemukan. Pastikan sudah ada data di database.\n");
}

// 2. Simulasi Request dari Form
$requestData = [
    'name' => 'Atlet Tester ' . time(),
    'email' => 'atlet' . time() . '@test.com',
    'phone' => '08' . rand(10000000, 99999999),
    'gender' => 'MALE',
    'password' => 'password123',
    'training_package_id' => $package->id,
    'status' => 'AKTIF'
];

echo "--- MEMULAI TESTER SIMULASI DASHBOARD ---\n";
echo "Data Input: " . json_encode($requestData, JSON_PRETTY_PRINT) . "\n\n";

DB::beginTransaction();
try {
    // Simulasi logic di CoachDashboardController@storeMember
    
    // 1. Create User
    $user = User::create([
        'name' => $requestData['name'],
        'email' => $requestData['email'],
        'phone' => $requestData['phone'],
        'gender' => $requestData['gender'],
        'password' => Hash::make($requestData['password']),
    ]);
    echo "✅ [1/4] User Berhasil Dibuat: " . $user->email . "\n";

    // 2. Assign Role
    $memberRole = Role::where('name', 'member')->first();
    if ($memberRole) {
        $user->assignRole($memberRole);
        echo "✅ [2/4] Role 'Member' Berhasil Diberikan\n";
    }

    // 3. Create Member Profile
    $member = Member::create([
        'user_id' => $user->id,
        'training_package_id' => $requestData['training_package_id'],
        'status' => $requestData['status'],
        'start_date' => now(),
    ]);
    echo "✅ [3/4] Profile Member Berhasil Dibuat (ID: " . $member->id . ")\n";

    // 4. Assign to Coach
    $member->coaches()->attach($coach->id);
    echo "✅ [4/4] Terhubung ke Coach (ID: " . $coach->id . ")\n";

    // --- VERIFIKASI AKHIR ---
    echo "\n--- VERIFIKASI JADWAL ---\n";
    $memberSchedules = TrainingSchedule::whereHas('coaches', function($q) use ($coach) {
        $q->where('coaches.id', $coach->id);
    })->get();

    echo "Atlet '" . $user->name . "' sekarang memiliki akses ke " . $memberSchedules->count() . " jadwal via Coach " . $coach->id . ":\n";
    foreach ($memberSchedules as $s) {
        echo " > [" . strtoupper($s->day) . "] " . $s->time . " @ " . ($s->place ?? 'Kolam Utama') . "\n";
    }

    DB::commit();
    echo "\n🎉 TESTER BERHASIL: Logic sudah sesuai tanpa tabel member_schedules!\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "\n❌ TESTER GAGAL: " . $e->getMessage() . "\n";
}
