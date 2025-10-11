<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
                    $table->string('status')->default('AKTIF');
                    $table->date('start_date')->nullable();
                    $table->date('end_date')->nullable();
                    $table->timestamps();
                });

                // status check constraint
                DB::statement("ALTER TABLE members ADD CONSTRAINT members_status_check CHECK (status IN ('AKTIF','TIDAK_AKTIF'));");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
