<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Salary;

class SalarySeeder extends Seeder
{
    public function run(): void
    {
        $months = ['August', 'September', 'October'];

        foreach ([1, 2, 3] as $coachId) {
            foreach ($months as $month) {
                Salary::create([
                    'coach_id' => $coachId,
                    'amount' => rand(5000000, 8000000),
                    'month' => $month,
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);
            }
        }
    }
}
