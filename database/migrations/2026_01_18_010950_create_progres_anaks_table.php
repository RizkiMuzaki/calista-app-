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
        Schema::create('progres_anaks', function (Blueprint $table) {
            $table->id();
             $table->foreignId('anak_id')
        ->constrained('anaks')
        ->cascadeOnDelete();

    $table->foreignId('level_id')
        ->constrained('levels')
        ->cascadeOnDelete();

    // HASIL BELAJAR
    $table->integer('score')->default(0);
    $table->integer('bintang')->default(0); // ⭐ 1–5
    $table->boolean('selesai')->default(false);
    $table->unique(['anak_id', 'level_id']);
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progres_anaks');
    }
};
