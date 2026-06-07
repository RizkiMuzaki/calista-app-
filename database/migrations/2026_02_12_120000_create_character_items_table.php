<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('character_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');                // Nama baju (Baju Batik, Baju Kebaya, dll)
            $table->text('description')->nullable(); // Deskripsi baju
            $table->string('image_url');           // Path gambar baju Nusa
            $table->enum('unlock_type', ['free', 'reward', 'premium'])->default('free');
            // free = gratis untuk semua anak
            // reward = unlock setelah capai milestone belajar
            // premium = hanya untuk subscriber premium
            $table->string('reward_condition')->nullable(); // Syarat unlock reward (misal: "complete_5_levels")
            $table->integer('sort_order')->default(0);      // Urutan tampil di toko
            $table->boolean('is_active')->default(true);    // Aktif atau tidak
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('character_items');
    }
};
