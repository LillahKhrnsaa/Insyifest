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
            [
                'bank_name' => 'Bank Mandiri',
                'account_number' => '9876543210',
                'account_holder' => 'Siti Nurhaliza',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'bank_name' => 'Bank Negara Indonesia (BNI)',
                'account_number' => '5558883332',
                'account_holder' => 'Budi Santoso',
                'is_active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
