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
        Schema::create('story_choices', function (Blueprint $table) {
            $table->id();
              $table->foreignId('story_page_id')
          ->constrained('story_pages')
          ->onDelete('cascade');

    // teks pilihan
    $table->string('choice_text');

    // lanjut ke halaman mana
    $table->integer('next_page_number');
    $table->enum('type', ['proses', 'selesai'])->default('proses');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('story_choices');
    }
};
