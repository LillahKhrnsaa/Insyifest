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
                'account_number' => '3781449341',
                'account_holder' => 'Moh Luthfi Adistira',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'bank_name' => 'Bank Rakyat Indonesia (BRI)',
                'account_number' => '326001028392539',
                'account_holder' => 'Moh Luthfi Adistira',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'bank_name' => 'Bank Rakyat Indonesia (BRI)',
                'account_number' => '326001000006650',
                'account_holder' => 'Moh Luthfi Adistira',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
