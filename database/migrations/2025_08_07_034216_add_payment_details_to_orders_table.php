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
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('amount_received', 10, 2)->nullable()->after('total');
            $table->decimal('change_amount', 10, 2)->nullable()->after('amount_received');
            $table->enum('payment_method', ['cash', 'card', 'qris', 'transfer'])->default('cash')->after('change_amount');
            $table->string('receipt_number')->nullable()->after('payment_method');
            $table->timestamp('paid_at')->nullable()->after('receipt_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'amount_received',
                'change_amount', 
                'payment_method',
                'receipt_number',
                'paid_at'
            ]);
        });
    }
};
