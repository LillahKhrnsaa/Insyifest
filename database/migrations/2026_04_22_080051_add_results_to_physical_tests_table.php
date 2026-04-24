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
        Schema::table('physical_tests', function (Blueprint $table) {
            $table->json('results')->nullable()->after('run_300m');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('physical_tests', function (Blueprint $table) {
            $table->dropColumn('results');
        });
    }
};
