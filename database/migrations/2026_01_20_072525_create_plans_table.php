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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_paket', 30); // contoh: 1 Bulan, 3 Bulan, 6 Bulan
            $table->integer('durasi_bulan'); // contoh: 1, 3, 6
            $table->decimal('harga_jual', 10, 2)->nullable();
            $table->text('deskripsi')->nullable(); // bisa isi info kelebihannya
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
    */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
