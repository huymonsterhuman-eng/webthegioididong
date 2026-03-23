<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('total', 15, 2)->change();
            $table->decimal('shipping_fee', 15, 2)->change();
        });

        Schema::table('order_details', function (Blueprint $table) {
            $table->decimal('price_at_purchase', 15, 2)->change();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->decimal('price', 15, 2)->change();
            $table->decimal('sale_price', 15, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('total', 10, 2)->change();
            $table->decimal('shipping_fee', 12, 2)->change();
        });

        Schema::table('order_details', function (Blueprint $table) {
            $table->decimal('price_at_purchase', 10, 2)->change();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->change();
            $table->decimal('sale_price', 10, 2)->nullable()->change();
        });
    }
};
