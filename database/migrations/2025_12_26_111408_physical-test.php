<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('physical_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->foreignId('coach_id')->constrained('coaches')->onDelete('cascade');

            // Periode menggunakan format ENUM yang sama dengan Raport
            $table->integer('year')->comment('Tahun tes fisik');
            $table->enum('month', [
                'januari', 'februari', 'maret', 'april', 'mei', 'juni', 
                'juli', 'agustus', 'september', 'oktober', 'november', 'desember'
            ])->comment('Bulan tes fisik');

            // Data Komponen Fisik
            $table->double('vo2max')->nullable();
            $table->integer('bleep_level')->nullable();
            $table->integer('bleep_shuttle')->nullable();
            $table->double('sprint_20m')->nullable();
            $table->integer('push_up')->nullable();
            $table->integer('sit_up')->nullable();
            $table->double('shuttle_run')->nullable();
            $table->double('v_sit_reach')->nullable();
            $table->double('run_300m')->nullable();
            
            $table->text('note')->nullable();
            $table->timestamps();

            // Mencegah duplikasi tes fisik untuk member di periode yang sama
            $table->unique(['member_id', 'year', 'month'], 'physical_period_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('physical_tests');
    }
};