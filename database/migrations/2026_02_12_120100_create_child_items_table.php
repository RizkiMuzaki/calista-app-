<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('child_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anak_id')->constrained('anaks')->onDelete('cascade');
            $table->foreignId('character_item_id')->constrained('character_items')->onDelete('cascade');
            $table->boolean('is_equipped')->default(false); // Apakah sedang dipakai
            $table->timestamp('unlocked_at')->nullable();   // Kapan di-unlock
            $table->timestamps();

            // Satu anak tidak bisa punya item yang sama 2x
            $table->unique(['anak_id', 'character_item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('child_items');
    }
};
