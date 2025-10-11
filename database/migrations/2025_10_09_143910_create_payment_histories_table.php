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
        Schema::create('payment_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->text('description')->nullable();
            $table->timestamp('payment_date')->useCurrent();
            $table->string('status')->default('PENDING');
            $table->string('proof_path')->nullable();
            $table->timestamps();
        });

        DB::statement("ALTER TABLE payment_histories ADD CONSTRAINT payment_histories_status_check CHECK (status IN ('PENDING','TERKONFIRMASI','GAGAL'));");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_histories');
    }
};
