<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('progres_anaks', function (Blueprint $table) {
            $table->unsignedInteger('current_score')->default(0)->after('score');
            $table->unsignedInteger('current_item')->default(0)->after('selesai');
            $table->unsignedInteger('total_items')->default(0)->after('current_item');
            $table->unsignedInteger('mistakes')->default(0)->after('total_items');
            $table->unsignedInteger('lives_remaining')->default(3)->after('mistakes');
            $table->unsignedInteger('duration_seconds')->default(0)->after('lives_remaining');
            $table->timestamp('last_played_at')->nullable()->after('duration_seconds');
            $table->json('metadata')->nullable()->after('last_played_at');
        });
    }

    public function down(): void
    {
        Schema::table('progres_anaks', function (Blueprint $table) {
            $table->dropColumn([
                'current_item',
                'current_score',
                'total_items',
                'mistakes',
                'lives_remaining',
                'duration_seconds',
                'last_played_at',
                'metadata',
            ]);
        });
    }
};
