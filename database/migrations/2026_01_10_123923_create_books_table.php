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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            // Relasi ke modules
            $table->foreignId('module_id')
                  ->constrained('modules')
                  ->onDelete('cascade');
            $table->string('cover_image');
            $table->string('title');
            $table->string('slug')->unique();
            $table->integer('order')->default(1);
            $table->text('description')->nullable();
            $table->enum('type', ['cerita', 'membaca'])->default('cerita');

            // 🔥 FLAG TAMBAHAN
            $table->boolean('is_active')->default(true);   // aktif / nonaktif
            $table->boolean('is_premium')->default(false); // premium / gratis
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
