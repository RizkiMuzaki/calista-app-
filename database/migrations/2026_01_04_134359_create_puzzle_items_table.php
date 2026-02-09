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
        Schema::create('puzzle_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('level_id')->constrained()->cascadeOnDelete();
            $table->string('title');           // Puzzle 1, Puzzle 2
            $table->string('image');           // path gambar puzzle
            $table->unsignedTinyInteger('grid_size'); // 3 = 3x3, 4 = 4x4
            $table->unsignedInteger('order_number');  // urutan puzzle
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('puzzle_items');
    }
};
