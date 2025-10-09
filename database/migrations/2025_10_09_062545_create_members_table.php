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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->foreignId('training_package_id')->nullable()->constrained('training_packages')->onDelete('set null');
            $table->string('status', 15)->default('AKTIF');
            $table->timestamp('start_date');
            $table->timestamp('end_date')->nullable();
            $table->timestamps();
        });

        DB::statement("ALTER TABLE members ADD CONSTRAINT members_status_check CHECK (status IN ('AKTIF','TIDAK_AKTIF'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
        });
        
        Schema::dropIfExists('members');
    }
};