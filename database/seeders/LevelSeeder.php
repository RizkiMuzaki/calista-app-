<?php
 
namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
 
class LevelSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('levels')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
 
        $levels = [
            // ── MEMBACA (reading) ───────────────────────────────────
            [
                'module_id'     => 1,
                'activity_type' => 'reading',
                'order_number'  => 1,
                'title'         => 'Suara Hewan',
                'local_level_id'=> 'reading-zoo-1',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'module_id'     => 1,
                'activity_type' => 'reading',
                'order_number'  => 2,
                'title'         => 'Suku Kata Awal',
                'local_level_id'=> 'reading-zoo-2',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'module_id'     => 1,
                'activity_type' => 'reading',
                'order_number'  => 3,
                'title'         => 'Rangkai Nama',
                'local_level_id'=> 'reading-zoo-3',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'module_id'     => 1,
                'activity_type' => 'reading',
                'order_number'  => 4,
                'title'         => 'Baca dan Temukan',
                'local_level_id'=> 'reading-zoo-4',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'module_id'     => 1,
                'activity_type' => 'reading',
                'order_number'  => 5,
                'title'         => 'Suara Penjelajah',
                'local_level_id'=> 'reading-zoo-5',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
 
            // ── MENULIS (writing) ───────────────────────────────────
            [
                'module_id'     => 1,
                'activity_type' => 'writing',
                'order_number'  => 1,
                'title'         => 'Huruf A - E',
                'local_level_id'=> 'writing-zoo-1',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'module_id'     => 1,
                'activity_type' => 'writing',
                'order_number'  => 2,
                'title'         => 'Huruf F - J',
                'local_level_id'=> 'writing-zoo-2',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'module_id'     => 1,
                'activity_type' => 'writing',
                'order_number'  => 3,
                'title'         => 'Huruf K - O',
                'local_level_id'=> 'writing-zoo-3',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'module_id'     => 1,
                'activity_type' => 'writing',
                'order_number'  => 4,
                'title'         => 'Huruf P - T',
                'local_level_id'=> 'writing-zoo-4',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'module_id'     => 1,
                'activity_type' => 'writing',
                'order_number'  => 5,
                'title'         => 'Huruf U - Z',
                'local_level_id'=> 'writing-zoo-5',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
 
            // ── BERHITUNG (counting) ─────────────────────────────────
            [
                'module_id'     => 1,
                'activity_type' => 'counting',
                'order_number'  => 1,
                'title'         => 'Kandang Ceria',
                'local_level_id'=> 'counting-zoo-1',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'module_id'     => 1,
                'activity_type' => 'counting',
                'order_number'  => 2,
                'title'         => 'Teman Baru',
                'local_level_id'=> 'counting-zoo-2',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'module_id'     => 1,
                'activity_type' => 'counting',
                'order_number'  => 3,
                'title'         => 'Kandang Ramai',
                'local_level_id'=> 'counting-zoo-3',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'module_id'     => 1,
                'activity_type' => 'counting',
                'order_number'  => 4,
                'title'         => 'Barisan Besar',
                'local_level_id'=> 'counting-zoo-4',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'module_id'     => 1,
                'activity_type' => 'counting',
                'order_number'  => 5,
                'title'         => 'Pesta Kandang',
                'local_level_id'=> 'counting-zoo-5',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
 
            // ── PUZZLE (puzzle) ─────────────────────────────────────
            [
                'module_id'     => 1,
                'activity_type' => 'puzzle',
                'order_number'  => 1,
                'title'         => 'Puzzle Singa',
                'local_level_id'=> 'puzzle-zoo-1',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'module_id'     => 1,
                'activity_type' => 'puzzle',
                'order_number'  => 2,
                'title'         => 'Puzzle Teman Zoo',
                'local_level_id'=> 'puzzle-zoo-2',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'module_id'     => 1,
                'activity_type' => 'puzzle',
                'order_number'  => 3,
                'title'         => 'Puzzle Petualangan',
                'local_level_id'=> 'puzzle-zoo-3',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ];
 
        DB::table('levels')->insert($levels);
        $this->command->info('✅ LevelSeeder: 18 level game mobile berhasil di-seed!');
    }
}
