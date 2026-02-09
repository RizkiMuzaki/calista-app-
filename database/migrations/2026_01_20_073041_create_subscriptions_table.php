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
    Schema::create('subscriptions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('plan_id')->constrained()->onDelete('cascade');
        $table->dateTime('tanggal_mulai');
        $table->dateTime('tanggal_berakhir');
        $table->enum('status', ['aktif', 'nonaktif'])->default('nonaktif');
        $table->timestamps();
    });
}

/**P
 * Reverse the migrations.
 */
public function down(): void
{
    Schema::dropIfExists('subscriptions');
}
};
