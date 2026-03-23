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
            $table->string('shipping_method')->nullable()->after('payment_method');
            $table->decimal('shipping_fee', 12, 2)->default(0)->after('shipping_method');
            $table->foreignId('partner_id')->nullable()->constrained('partners')->nullOnDelete()->after('shipping_fee');
            $table->string('tracking_number')->nullable()->after('partner_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['partner_id']);
            $table->dropColumn(['shipping_method', 'shipping_fee', 'partner_id', 'tracking_number']);
        });
    }
};
