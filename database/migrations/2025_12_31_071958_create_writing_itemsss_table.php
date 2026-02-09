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
        Schema::create('writing_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('level_id')->constrained()->cascadeOnDelete();
            $table->string('text');        // ANNISA, ANGKLUNG
            $table->string('image_path')->nullable();
            $table->string('audio_path')->nullable();

            $table->enum('type', ['letter', 'word'])->default('word');        
            $table->timestamps();});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('writing_items');
    }
};
