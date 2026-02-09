<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'nama_paket' => '1 Bulan',
                'durasi_bulan' => 1,
                'harga_jual' => 91000,
                'deskripsi' => "🎒 Paket CALISTA 1 Bulan — Belajar Calistung bersama AI\n
📅 Durasi: 30 hari\n
🤖 Akses AI Interaktif untuk belajar membaca, menulis, dan berhitung\n
🔤 Materi dasar: Pengenalan huruf & angka\n
🧠 Latihan adaptif sesuai kemampuan anak\n
👶 Cocok untuk anak usia 5–10 tahun",
            ],
            [
                'nama_paket' => '3 Bulan',
                'durasi_bulan' => 3,
                'harga_jual' => 273000,
                'deskripsi' => "🌟 Paket CALISTA 3 Bulan — Solusi belajar konsisten dan hemat\n
📅 Durasi: 90 hari\n
🤖 Akses AI Interaktif tanpa batas\n
🔤 Materi lengkap calistung dasar\n
📊 Evaluasi perkembangan belajar anak\n
🎮 Pembelajaran interaktif & menyenangkan\n
👶 Direkomendasikan untuk anak usia 5–10 tahun",
            ],
        ];

        DB::table('plans')->insert($plans);
    }
}
