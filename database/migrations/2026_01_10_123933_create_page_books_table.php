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
    Schema::create('page_books', function (Blueprint $table) {
        $table->id();
        $table->foreignId('book_id')
        ->constrained('books')
        ->onDelete('cascade');

$table->integer('page_number');
$table->string('nama_benda');   // LUPIS
$table->string('suku_kata');    // LU-PIS
$table->string('image_path');
$table->string('audio_kata');
$table->string('audio_path');
$table->text('explanation');

$table->boolean('is_active')->default(true);
$table->boolean('is_premium')->default(false);

        $table->timestamps();
    });
}

/**
 * Reverse the migrations.
 */
public function down(): void
{
    Schema::dropIfExists('page_books');
}
};
