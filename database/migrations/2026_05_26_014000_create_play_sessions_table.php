<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('play_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('anak_id')->constrained('anaks')->cascadeOnDelete();
            $table->foreignId('level_id')->nullable()->constrained('levels')->nullOnDelete();
            $table->foreignId('module_id')->nullable()->constrained('modules')->nullOnDelete();
            $table->string('session_uuid', 120)->nullable()->unique();
            $table->string('source', 40)->default('mobile_game');
            $table->string('module_slug', 80)->nullable();
            $table->string('module_name', 120)->nullable();
            $table->string('level_title', 180)->nullable();
            $table->string('status', 20)->default('partial');
            $table->unsignedInteger('score')->default(0);
            $table->unsignedInteger('current_score')->default(0);
            $table->unsignedTinyInteger('bintang')->default(0);
            $table->unsignedInteger('current_item')->default(0);
            $table->unsignedInteger('total_items')->default(0);
            $table->unsignedInteger('mistakes')->default(0);
            $table->unsignedTinyInteger('lives_remaining')->default(0);
            $table->unsignedInteger('duration_seconds')->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->date('played_on')->index();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'anak_id', 'played_on']);
            $table->index(['anak_id', 'module_slug', 'played_on']);
            $table->index(['anak_id', 'status', 'played_on']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('play_sessions');
    }
};
