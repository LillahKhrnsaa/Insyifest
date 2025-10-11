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
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();

            // Relasi ke pelatih
            $table->foreignId('coach_id')->constrained('coaches')->onDelete('cascade');

            // Komponen perhitungan
            $table->integer('training_sessions')->default(0); // Jumlah pertemuan latihan
            $table->decimal('transport_fee', 12, 2)->default(0);
            $table->decimal('per_meeting_fee', 12, 2)->default(0);
            $table->decimal('per_member_fee', 12, 2)->default(0);
            $table->decimal('health_fee', 12, 2)->default(0);
            $table->decimal('bonus', 12, 2)->default(0);

            // Jumlah total (hasil kalkulasi)
            $table->decimal('total_amount', 14, 2)->default(0);

            // Info tambahan
            $table->string('month')->nullable();
            $table->string('status')->nullable(); // misal: pending, paid
            $table->date('paid_at')->nullable();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
