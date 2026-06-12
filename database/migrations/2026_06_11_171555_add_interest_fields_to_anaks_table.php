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
        Schema::table('anaks', function (Blueprint $table) {
            $table->string('cita_cita')->nullable()->after('tanggal_lahir');
            $table->string('hobi')->nullable()->after('cita_cita');
            $table->string('makanan_favorit')->nullable()->after('hobi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anaks', function (Blueprint $table) {
            $table->dropColumn(['cita_cita', 'hobi', 'makanan_favorit']);
        });
    }
};
