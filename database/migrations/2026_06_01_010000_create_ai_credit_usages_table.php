<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_credit_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('plan_code', 24)->default('free');
            $table->string('feature', 48)->default('nusa_tts');
            $table->unsignedInteger('credits');
            $table->unsignedInteger('characters');
            $table->date('period_start');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'plan_code', 'period_start']);
            $table->index(['feature', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_credit_usages');
    }
};
