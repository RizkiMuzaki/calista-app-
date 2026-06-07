<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE anaks MODIFY limit_detik INT NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE anaks MODIFY sisa_detik INT NOT NULL DEFAULT 0');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE anaks MODIFY limit_detik INT NOT NULL DEFAULT 3600');
        DB::statement('ALTER TABLE anaks MODIFY sisa_detik INT NOT NULL DEFAULT 3600');
    }
};
