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
        Schema::create('form_eksternals', function (Blueprint $table) {
            $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('slug')->unique();
                $table->jsonb('fields');
                $table->string('status')->default('INACTIVE');
                $table->timestamps();
        });

                DB::statement("ALTER TABLE form_eksternals ADD CONSTRAINT form_eksternals_status_check CHECK (status IN ('ACTIVE','INACTIVE'));");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_eksternals');
    }
};
