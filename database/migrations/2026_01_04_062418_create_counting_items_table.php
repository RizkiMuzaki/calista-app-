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
        Schema::create('counting_items', function (Blueprint $table) {
                       $table->id();

            // Relasi ke level
            $table->foreignId('level_id')
                  ->constrained('levels')
                  ->cascadeOnDelete();
            $table->string('nama_objek');      // lupis
            $table->string('gambar_objek');    // lupis.png

            // Datwa soal menghitung
            $table->enum('jenis_operasi', ['tambah', 'kurang'])
                  ->default('tambah');

            $table->unsignedInteger('nilai_kiri');   // 2
            $table->unsignedInteger('nilai_kanan');  // 3
            $table->unsignedInteger('hasil');         // 5
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('counting_items');
    }
};
