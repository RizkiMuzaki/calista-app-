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
        Schema::create('anaks', function (Blueprint $table) {
            $table->id();
       $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('nama_anak');
            $table->date('tanggal_lahir');
            
            // ⏱️ TIMER BELAJAR (RESET HARIAN)
            $table->integer('limit_detik')->default(3600); // 1 jam
            $table->integer('sisa_detik')->default(3600);
            $table->date('tanggal_reset')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamp('timer_started_at')->nullable(); // Waktu mulai timer
            $table->timestamp('timer_last_updated')->nullable(); // Waktu terakhir update
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anaks');
    }
};
