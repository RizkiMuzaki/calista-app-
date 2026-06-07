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
        Schema::create('story_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('story_id')
                  ->constrained('stories')
                  ->onDelete('cascade');
            $table->integer('page_number');
            $table->text('story_text');
            $table->double('start_time')->default(0.0);
            $table->double('end_time')->default(0.0);
            $table->string('animation_trigger_state')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('story_pages');
    }
};
