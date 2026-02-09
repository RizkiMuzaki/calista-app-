<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('plan_id')->nullable()->constrained('plans')->onDelete('cascade'); // ✅ TAMBAH INI
            $table->string('merchant_ref'); // Merchant reference
            $table->string('payment_method'); // BRIVA, BNI, etc
            $table->string('payment_name'); // Payment method name
            $table->decimal('amount', 12, 2);
            $table->decimal('fee_merchant', 12, 2)->default(0);
            $table->decimal('fee_customer', 12, 2)->default(0);
            $table->decimal('total_fee', 12, 2)->default(0);
            $table->decimal('amount_received', 12, 2)->default(0);
            $table->string('pay_code')->nullable(); // For VA/Retail
            $table->text('pay_url')->nullable();
            $table->text('checkout_url');
            $table->enum('status', ['UNPAID', 'PAID', 'EXPIRED', 'FAILED'])->default('UNPAID');
            $table->timestamp('expired_time');
            $table->timestamps();            $table->index(['reference', 'merchant_ref']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};