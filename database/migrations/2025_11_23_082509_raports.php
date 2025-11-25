<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('raports', function (Blueprint $table) {
            $table->id();

            // ===================================
            // KOLOM GAYA (ENUM)
            // ===================================
            $table->enum('gaya', [
                'gaya_bebas_50', 'gaya_bebas_100', 'gaya_bebas_200', 'gaya_bebas_400', 'gaya_bebas_800', 'gaya_bebas_1500',
                'gaya_dada_50', 'gaya_dada_100', 'gaya_dada_200',
                'gaya_punggung_50', 'gaya_punggung_100', 'gaya_punggung_200',
                'gaya_kupu_50', 'gaya_kupu_100', 'gaya_kupu_200',
                'gaya_ganti_200', 'gaya_ganti_400'
            ])->comment('Kombinasi Gaya dan Jarak Renang');

            // ===================================
            // KOLOM RELASI (FOREIGN KEYS)
            // ===================================
            $table->foreignId('coach_id')->constrained('coaches')->onDelete('cascade');
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');

            // ===================================
            // DATA LAPORAN
            // ===================================
            $table->integer('year')->comment('Tahun data raport untuk grafik');
            
            // Kolom Bulan sebagai ENUM
            $table->enum('month', [
                'januari', 'februari', 'maret', 'april', 'mei', 'juni', 
                'juli', 'agustus', 'september', 'oktober', 'november', 'desember'
            ])->comment('Bulan data raport');
            
            $table->text('note')->nullable();

            // Value (Waktu) dalam detik
            $table->double('value')->comment('Waktu tempuh (misalnya dalam detik)')->nullable(); 

            // Data Latihan Tambahan
            $table->integer('volume')->comment('Volume latihan (misalnya meter)')->nullable();
            $table->double('intensity')->comment('Intensitas latihan (misalnya rating RPE atau persentase)')->nullable();
            $table->integer('peaking')->comment('Fase latihan (misalnya PEAKING)')->nullable();
            
            $table->timestamps();

            // Index untuk optimasi pencarian data berdasarkan member dan tahun (untuk grafik)
            $table->index(['member_id', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
