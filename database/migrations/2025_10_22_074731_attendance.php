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
        Schema::create('attendances', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('coach_id')->constrained('coaches')->onDelete('cascade');
                    $table->foreignId('schedule_id')->constrained('training_schedules')->onDelete('cascade');
                    $table->date('date');
                    $table->string('place')->nullable();
                    $table->string('photo_path')->nullable();
                    $table->timestamps();
                });

        Schema::create('attendance_members', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('attendance_id')->constrained('attendances')->onDelete('cascade');
                    $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
                    $table->timestamps();
                });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('attendance_members');
    }
};
