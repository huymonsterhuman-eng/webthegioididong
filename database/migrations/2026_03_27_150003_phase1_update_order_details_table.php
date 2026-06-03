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
            if (!Schema::hasColumn('order_details', 'product_name')) {
                $table->string('product_name')->nullable()->after('product_id');
            }
            if (!Schema::hasColumn('order_details', 'product_image')) {
                $table->string('product_image')->nullable()->after('product_name');
            }
        });

        // Backfill existing records with product data
        \DB::table('order_details')
            ->whereNull('product_name')
            ->orderBy('id')
            ->each(function ($detail) {
                $product = \DB::table('products')->find($detail->product_id);
                \DB::table('order_details')
                    ->where('id', $detail->id)
                    ->update([
                        'product_name'  => $product ? $product->name : 'Sản phẩm không tồn tại',
                        'product_image' => $product ? $product->image : null,
                    ]);
            });
    }

    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropColumn(['product_name', 'product_image']);
        });
    }
};
