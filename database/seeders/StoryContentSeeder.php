<?php
 
namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
 
class StoryContentSeeder extends Seeder
{
    public function run(): void
    {
        // Dikosongkan agar Boss bisa langsung unggah dongeng baru via Filament
        $this->command->info('StoryContentSeeder: Skipped seeding (ready for manual upload).');
    }
}
