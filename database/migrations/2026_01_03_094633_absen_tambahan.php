<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            // Mengubah schedule_id menjadi nullable
            $table->foreignId('schedule_id')->nullable()->change();
            
            // Menambah kolom jam aktual dan catatan
            $table->time('time')->nullable()->after('date');
            $table->text('notes')->nullable()->after('photo_path');
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
