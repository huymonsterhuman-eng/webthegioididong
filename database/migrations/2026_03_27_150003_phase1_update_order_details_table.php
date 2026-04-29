<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            // Snapshot product info at time of purchase
            // This ensures order history is intact even if product is soft-deleted
            $table->string('product_name')->nullable()->after('product_id');
            $table->string('product_image')->nullable()->after('product_name');
        });

        // Backfill existing records with product data
        \DB::statement("
            UPDATE order_details od
            LEFT JOIN products p ON od.product_id = p.id
            SET od.product_name = COALESCE(p.name, 'Sản phẩm không tồn tại'),
                od.product_image = p.image
            WHERE od.product_name IS NULL
        ");
    }

    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropColumn(['product_name', 'product_image']);
        });
    }
};
