<?php
 
namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
 
class ChildSeeder extends Seeder
{
    public function run(): void
    {
        // Dikosongkan agar database bersih tanpa anak dummy bawaan seeder.
        $this->command->info('ChildSeeder: Skipped seeding child dummy profiles.');
    }
}
