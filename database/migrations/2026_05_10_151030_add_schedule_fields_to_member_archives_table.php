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
        Schema::table('member_archives', function (Blueprint $table) {
            $table->string('training_day')->nullable()->after('coach_id');
            $table->string('training_time')->nullable()->after('training_day');
            $table->integer('training_day_index')->nullable()->after('training_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('member_archives', function (Blueprint $table) {
            $table->dropColumn(['training_day', 'training_time', 'training_day_index']);
        });
    }
};
