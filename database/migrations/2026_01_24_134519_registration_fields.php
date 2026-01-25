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
        Schema::create('registration_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_form_id')->constrained()->cascadeOnDelete();
            $table->string('label');
            $table->string('name');
            $table->string('type');
            $table->boolean('is_required')->default(false);
            $table->json('options')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_fields');
    }
};
