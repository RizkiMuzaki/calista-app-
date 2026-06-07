<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'nama_paket' => 'Calista Edu Plan Mingguan',
                'durasi_bulan' => 0,
                'harga_jual' => 26000,
                'deskripsi' => "Paket coba 7 hari untuk membuka wardrobe premium Nusa, gift Nusa Elephant Ranger, dan laporan progress dasar.",
            ],
            [
                'nama_paket' => 'Calista Plus Bulanan',
                'durasi_bulan' => 1,
                'harga_jual' => 130000,
                'deskripsi' => "Paket fleksibel keluarga aktif: semua benefit Mingguan, wardrobe premium penuh, dan insight perkembangan anak lebih lengkap.",
            ],
            [
                'nama_paket' => 'Calista Plus Tahunan',
                'durasi_bulan' => 12,
                'harga_jual' => 866000,
                'deskripsi' => "Paket 12 bulan penuh untuk belajar stabil, akses premium panjang, dan prioritas fitur Nusa terbaru.",
            ],
        ];

        foreach ($plans as $plan) {
            DB::table('plans')->updateOrInsert(
                ['nama_paket' => $plan['nama_paket']],
                [
                    'durasi_bulan' => $plan['durasi_bulan'],
                    'harga_jual' => $plan['harga_jual'],
                    'deskripsi' => $plan['deskripsi'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
