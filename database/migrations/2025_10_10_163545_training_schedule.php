<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('training_schedules', function (Blueprint $table) {
                    $table->id();
                    $table->string('day');
                    $table->time('time')->nullable();
                    $table->string('place')->nullable();
                    $table->timestamps();
                });

                DB::statement("ALTER TABLE training_schedules ADD CONSTRAINT training_schedules_day_check CHECK (day IN ('SENIN','SELASA','RABU','KAMIS','JUMAT','SABTU','MINGGU'));");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_schedules');
    }
};
