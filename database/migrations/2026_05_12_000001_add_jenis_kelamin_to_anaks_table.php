<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('anaks', function (Blueprint $table) {
            $table->enum('jenis_kelamin', ['L', 'P'])->default('L')->after('tanggal_lahir');
        });
    }

    public function down(): void
    {
        Schema::table('anaks', function (Blueprint $table) {
            $table->dropColumn('jenis_kelamin');
        });
    }
};
