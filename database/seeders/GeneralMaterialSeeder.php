<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class GeneralMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('general_materials')->insert([
            [
                'title' => 'Panduan Penggunaan Sistem',
                'description' => 'Dokumen panduan untuk memahami fitur-fitur utama dalam sistem.',
                'file_path' => 'uploads/materials/panduan_penggunaan.pdf',
                'uploaded_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Kebijakan Privasi',
                'description' => 'File berisi aturan privasi dan perlindungan data pengguna.',
                'file_path' => 'uploads/materials/kebijakan_privasi.pdf',
                'uploaded_at' => Carbon::now()->subDays(2),
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'title' => 'Peraturan Organisasi',
                'description' => 'Berisi tata tertib dan ketentuan yang berlaku dalam organisasi.',
                'file_path' => 'uploads/materials/peraturan_organisasi.pdf',
                'uploaded_at' => Carbon::now()->subDays(5),
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5),
            ],
        ]);
    }
}
