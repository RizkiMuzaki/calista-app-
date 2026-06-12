<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 🎓 LEARNING: Mood Tracking Table
 * 
 * Tabel ini menyimpan data emosi anak SEBELUM sesi belajar dimulai.
 * Berdasarkan standar UX Child Development 2026:
 * - Mood hanya dicatat 1x per hari (Daily Check-in) untuk menghindari user fatigue.
 * - Data ini dipakai orang tua untuk memantau pola emosi anak via Parent Dashboard.
 * 
 * Desain tabel mengikuti prinsip Data Minimization (COPPA Standard):
 * - Tidak menyimpan data lebih dari yang diperlukan.
 * - Index pada (anak_id + tanggal) untuk query cepat.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('moods', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel anaks (profil anak)
            $table->foreignId('anak_id')
                ->constrained('anaks')
                ->onDelete('cascade'); // Jika anak dihapus, data moodnya ikut terhapus

            // Tipe mood: 5 emosi dasar (model Paul Ekman)
            $table->enum('mood_type', [
                'senang',    // 😊 Happy
                'sedih',     // 😢 Sad
                'ceria',     // 🤩 Cheerful
                'takut',     // 😨 Afraid
                'marah',     // 😠 Angry
            ]);

            // Kolom opsional: catatan orang tua (untuk tesis → not implemented)
            $table->text('catatan')->nullable();

            // Timestamps: hanya butuh created_at untuk filter harian
            $table->timestamps();

            // 🎓 Composite Index: mempercepat query "apakah anak X sudah track mood hari ini?"
            $table->index(['anak_id', 'created_at'], 'idx_moods_anak_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('moods');
    }
};
