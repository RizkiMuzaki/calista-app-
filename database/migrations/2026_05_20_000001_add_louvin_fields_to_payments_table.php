<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('provider')->nullable()->after('plan_id');
            $table->string('provider_transaction_id')->nullable()->after('provider');
            $table->string('provider_subscription_id')->nullable()->after('provider_transaction_id');
            $table->json('provider_payload')->nullable()->after('provider_subscription_id');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'provider',
                'provider_transaction_id',
                'provider_subscription_id',
                'provider_payload',
            ]);
        });
    }
};
