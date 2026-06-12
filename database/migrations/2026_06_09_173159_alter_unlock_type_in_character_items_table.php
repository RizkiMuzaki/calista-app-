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
        // Alter enum unlock_type agar mendukung 'star_reward'
        Schema::table('character_items', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE character_items MODIFY COLUMN unlock_type ENUM('free', 'reward', 'premium', 'star_reward') NOT NULL DEFAULT 'free'");
        });

        // Hapus kepemilikan baju 'Nusa Elephant Ranger' (ID 4) dari database agar statusnya terkunci lagi
        try {
            $item = \Illuminate\Support\Facades\DB::table('character_items')
                ->where('name', 'Nusa Elephant Ranger')
                ->first();
            if ($item) {
                \Illuminate\Support\Facades\DB::table('child_items')
                    ->where('character_item_id', $item->id)
                    ->delete();
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Gagal menghapus child_items Nusa Elephant Ranger: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('character_items', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE character_items MODIFY COLUMN unlock_type ENUM('free', 'reward', 'premium') NOT NULL DEFAULT 'free'");
        });
    }
};
