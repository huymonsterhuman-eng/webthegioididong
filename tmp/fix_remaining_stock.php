<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use App\Models\GoodsReceiptDetail;

$products = Product::all();
foreach ($products as $product) {
    $stock = $product->stock;
    echo "Product: {$product->name}, Stock: {$stock}\n";

    // FIFO: Newest batches hold current stock
    $receipts = GoodsReceiptDetail::where('product_id', $product->id)
        ->orderBy('created_at', 'desc')
        ->get();
    
    foreach ($receipts as $receipt) {
        $take = min($stock, $receipt->quantity);
        $stock -= $take;
        
        $receipt->remaining_quantity = $take;
        $receipt->save();
        
        echo " - Batch #PN{$receipt->goods_receipt_id}, Take: {$take}\n";
    }
}

echo "Done populating remaining_quantity.\n";
