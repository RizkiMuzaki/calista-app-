<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'ELANORA',
            'email' => 'elanoraadmin@gmail.com',
            'password' => bcrypt('09425863'),
        ]);

    $this->call([
        PlanSeeder::class,
        CharacterItemSeeder::class, // Baju/Avatar
        // 🎮 Game Content Seeders — urutan PENTING: Module → Level
        ModuleSeeder::class,
        LevelSeeder::class,
        StoryContentSeeder::class,
        
        // Data Anak & Premium (Seeder ini mungkin butuh Module/Levels jadi ditaruh di bawah)
        ChildSeeder::class,
    ]);
    
    }

    
}
