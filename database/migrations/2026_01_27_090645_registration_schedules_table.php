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
        Schema::table('registration_schedules', function (Blueprint $table) {
            $table->string('schedule_group')->nullable()->after('registration_form_id');
            $table->string('location')->nullable()->after('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registration_schedules', function (Blueprint $table) {
            $table->dropColumn(['schedule_group', 'location']);
        });
    }
};
