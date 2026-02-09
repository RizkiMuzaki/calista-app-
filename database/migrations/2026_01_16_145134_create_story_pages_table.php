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
        Schema::create('story_pages', function (Blueprint $table) {
            $table->id();
             $table->foreignId('book_id')
          ->constrained('books')
          ->onDelete('cascade');

    // urutan halaman
    $table->integer('page_number');

    // teks cerita
    $table->text('story_text');

    // audio hasil TTS
    $table->string('audio_path')->nullable();

    // pertanyaan interaktif (voice agent)
    $table->string('question')->nullable();

    // estimasi durasi audio (detik)
    $table->integer('duration')->nullable();

    $table->boolean('is_active')->default(true);
    $table->unique(['book_id', 'page_number']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('story_pages');
    }
};
