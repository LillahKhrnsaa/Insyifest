<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('member_archives', function (Blueprint $table) {
            $table->unsignedBigInteger('coach_id')->nullable()->after('coach_name');
        });
    }

    public function down(): void
    {
        Schema::table('member_archives', function (Blueprint $table) {
            $table->dropColumn('coach_id');
        });
    }
};
