<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankAccountSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('bank_accounts')->insert([
            [
                'bank_name' => 'Bank Central Asia (BCA)',
                'account_number' => '1234567890',
                'account_holder' => 'Andi Wijaya',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
