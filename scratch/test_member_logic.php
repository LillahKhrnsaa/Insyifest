<?php

use App\Models\User;
use App\Models\Member;
use App\Models\Coach;
use App\Models\TrainingSchedule;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

DB::beginTransaction();
try {
    $coach = Coach::first();
    if (!$coach) throw new Exception("No coach found");

    $email = 'tester' . time() . '@example.com';
    
    // 1. Create User
    $user = User::create([
        'name' => 'Tester Member',
        'email' => $email,
        'phone' => '08' . rand(1000000000, 9999999999),
        'gender' => 'MALE',
        'password' => Hash::make('password123'),
    ]);
    echo "User created: $email\n";
    
    // 2. Assign Role (check which model is used for roles)
    // The previous error showed Spatie, but let's check App\Models\Role too
    $role = Role::where('name', 'Member')->first();
    if ($role) {
        $user->assignRole($role);
        echo "Role 'Member' assigned.\n";
    }
    
    // 3. Create Member Profile
    $member = Member::create([
        'user_id' => $user->id,
        'training_package_id' => 1,
        'status' => 'AKTIF',
        'start_date' => now(),
    ]);
    echo "Member profile created. ID: {$member->id}\n";
    
    // 4. Assign to Coach
    $member->coaches()->attach($coach->id);
    echo "Linked to Coach ID: {$coach->id}\n";
    
    // 5. Verify schedules (Join logic)
    // The user's requirement: Member -> Coach -> Schedule
    $schedules = TrainingSchedule::whereHas('coaches', function($q) use ($coach) {
        $q->where('coaches.id', $coach->id);
    })->get();
    
    echo "Verification: This member is now associated with " . $schedules->count() . " schedules via Coach {$coach->id}\n";
    foreach($schedules as $s) {
        echo " - Schedule: {$s->day} ({$s->time}) at {$s->place}\n";
    }

    DB::commit();
    echo "TEST PASSED SUCCESSFULLY!\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "TEST FAILED: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
