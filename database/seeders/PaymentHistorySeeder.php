<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Member;
use App\Models\PaymentHistory;

class PaymentHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = ['PENDING', 'TERKONFIRMASI', 'GAGAL'];

        // ===================================================================
        // BAGIAN 1: Buat pembayaran untuk member yang SUDAH punya paket
        // ===================================================================
        $membersWithPackage = Member::whereNotNull('training_package_id')
                                    ->with('trainingPackage', 'user') // Eager load relasi
                                    ->get();

        if ($membersWithPackage->isNotEmpty()) {
            $this->command->info('Membuat data pembayaran untuk member dengan paket...');
            foreach ($membersWithPackage as $member) {
                // Buat 1 atau 2 data pembayaran acak untuk setiap member
                for ($i = 0; $i < rand(1, 2); $i++) {
                    $status = $statuses[array_rand($statuses)];
                    PaymentHistory::create([
                        'member_id' => $member->id,
                        'amount' => $member->trainingPackage->price, // Ambil harga dari paket
                        'description' => 'Pembayaran Paket: ' . $member->trainingPackage->name, // Deskripsi sesuai paket
                        'payment_date' => now()->subDays(rand(1, 180)),
                        'status' => $status,
                        'proof_path' => $status === 'TERKONFIRMASI' ? 'proofs/sample_proof.jpg' : null,
                    ]);
                }
            }
        }

        // ===================================================================
        // BAGIAN 2: Buat pembayaran untuk member yang BELUM punya paket
        // ===================================================================
        $membersWithoutPackage = Member::whereNull('training_package_id')->get();
        
        if ($membersWithoutPackage->isNotEmpty()) {
            $this->command->info('Membuat data pendaftaran untuk member tanpa paket...');
            foreach ($membersWithoutPackage as $member) {
                $status = $statuses[array_rand($statuses)];
                PaymentHistory::create([
                    'member_id' => $member->id,
                    'amount' => 100000, // Anggap biaya pendaftaran awal
                    'description' => 'Biaya Pendaftaran Awal',
                    'payment_date' => now()->subDays(rand(181, 365)),
                    'status' => $status,
                    'proof_path' => $status === 'TERKONFIRMASI' ? 'proofs/registration.jpg' : null,
                ]);
            }
        }
    }
}