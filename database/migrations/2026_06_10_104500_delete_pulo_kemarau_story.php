<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Story;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Delete story "Pulo Kemarau" using Eloquent to trigger Spatie Media cleanup and cascading deletes
        Story::where('slug', 'pulo-kemarau')
            ->orWhere('title', 'like', '%Pulo Kemarau%')
            ->orWhere('title', 'like', '%Pulau Kemarau%')
            ->get()
            ->each(function ($story) {
                $story->delete();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op
    }
};
