<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Add subtotal (pure product cost before shipping & discount)
            $table->decimal('subtotal', 15, 2)->nullable()->after('user_id');

            // Add payment status tracking
            $table->enum('payment_status', ['unpaid', 'paid', 'refunded'])
                ->default('unpaid')
                ->after('payment_method');

            // Add status timestamps for audit trail
            $table->timestamp('delivered_at')->nullable()->after('payment_status');
            $table->timestamp('cancelled_at')->nullable()->after('delivered_at');

            // Remove order_date (duplicates created_at)
            $table->dropColumn('order_date');
        });

        // Change payment_method to enum (done separately because of SQLite limitations in testing)
        // In MySQL we can do this directly
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('payment_method', ['cod', 'vnpay', 'momo'])
                ->nullable()
                ->default('cod')
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'payment_status', 'delivered_at', 'cancelled_at']);
            $table->dateTime('order_date')->nullable();
            $table->string('payment_method')->nullable()->change();
        });
    }
};
