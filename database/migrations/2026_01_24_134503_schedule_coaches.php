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
        Schema::create('schedule_coaches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_schedule_id')->constrained()->cascadeOnDelete();
            $table->foreignId('coach_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('quota');
            $table->unsignedInteger('quota_used')->default(0);
            $table->timestamps();

            $table->unique(['registration_schedule_id', 'coach_id']);
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
