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
        Schema::create('registration_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_form_id')->constrained()->cascadeOnDelete();
            $table->string('day');     // SENIN
            $table->time('time');     // 15:00
            $table->date('date');     // 2025-01-23
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_schedules');
    }
};
