<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Product;
use App\Models\GoodsReceiptDetail;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Reset all remaining_quantity to 0 first to start clean
        DB::table('goods_receipt_details')->update(['remaining_quantity' => 0]);

        $products = Product::all();
        foreach ($products as $product) {
            $stock = (int) $product->stock;
            if ($stock <= 0) continue;

            // Fetch batches for this product, newest first (distributed stock belongs to newest batches)
            $receipts = GoodsReceiptDetail::where('product_id', $product->id)
                ->orderBy('created_at', 'desc')
                ->get();
            
            foreach ($receipts as $receipt) {
                if ($stock <= 0) break;
                $take = min($stock, $receipt->quantity);
                $stock -= $take;
                
                // Use DB directly to avoid any issues with model events or locks
                DB::table('goods_receipt_details')
                    ->where('id', $receipt->id)
                    ->update(['remaining_quantity' => $take]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse
    }
};
