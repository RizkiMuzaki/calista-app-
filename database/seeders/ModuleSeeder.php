<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate dulu biar bersih (cascade ke levels & items)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('modules')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('modules')->insert([
            [
                'id'         => 1,
                'name'       => 'Kebun Binatang',
                'type'       => 'level',
                'slug'       => 'zoo',
                'foto'       => 'assets/images/game/module_1/bg_zoo.webp',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 2,
                'name'       => 'Budaya Indonesia',
                'type'       => 'level',
                'slug'       => 'culture',
                'foto'       => 'assets/images/game/module_2/bg_culture.webp',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 3,
                'name'       => 'Laut Ceria',
                'type'       => 'level',
                'slug'       => 'sea',
                'foto'       => 'assets/images/game/module_3/bg_sea.webp',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 4,
                'name'       => 'Luar Angkasa',
                'type'       => 'level',
                'slug'       => 'space',
                'foto'       => 'assets/images/game/module_4/bg_space.webp',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->command->info('✅ ModuleSeeder: 3 modul berhasil di-seed!');
    }
}
