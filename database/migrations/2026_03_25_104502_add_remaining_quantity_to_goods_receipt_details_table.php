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
        Schema::table('goods_receipt_details', function (Blueprint $table) {
            $table->integer('remaining_quantity')->default(0)->after('quantity');
        });

        // Compute and distribute the existing inventory to the remaining_quantity column using FIFO (newest batches hold stock)
        $products = \App\Models\Product::all();
        foreach ($products as $product) {
            $stock = $product->stock;
            // Fetch newest receipts first
            $receipts = \App\Models\GoodsReceiptDetail::where('product_id', $product->id)
                ->orderBy('created_at', 'desc')
                ->get();
            
            foreach ($receipts as $receipt) {
                $take = min($stock, $receipt->quantity);
                $stock -= $take;
                $receipt->update(['remaining_quantity' => $take]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('goods_receipt_details', function (Blueprint $table) {
            $table->dropColumn('remaining_quantity');
        });
    }
};
