<?php

namespace Database\Seeders;

use App\Models\PaymentHistory;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            PermissionSeeder::class,
            BankAccountSeeder::class,
            TrainingPackageSeeder::class,
            GeneralMaterialSeeder::class,
            MemberSeeder::class,
            PaymentHistorySeeder::class,
        ]);
    }
}
