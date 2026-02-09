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
        Schema::create('story_images', function (Blueprint $table) {
            $table->id();
              $table->foreignId('story_page_id')
          ->constrained('story_pages')
          ->onDelete('cascade');

    // path gambar
    $table->string('image_path');

    // jenis gambar
    $table->enum('type', ['background', 'character', 'object'])
          ->default('background');

// urutan layer
    $table->integer('order')->default(1);

    // ⏱️ timing sinkron audio
    $table->integer('start_second')->default(0);
    $table->integer('end_second')->nullable();

    $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('story_images');
    }
};
