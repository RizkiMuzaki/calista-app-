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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('user_story_progress');
        Schema::dropIfExists('story_choices');
        Schema::dropIfExists('story_images');
        Schema::dropIfExists('story_pages');
        Schema::dropIfExists('page_books');
        Schema::dropIfExists('books');
        Schema::enableForeignKeyConstraints();

        Schema::create('stories', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->double('rating')->default(5.0);
            $table->string('age_group')->nullable();
            $table->string('duration')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_premium')->default(false);
            $table->integer('order')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stories');
    }
};
