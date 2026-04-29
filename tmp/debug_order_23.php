<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;
use App\Models\GoodsReceiptDetail;
use App\Models\Product;

$orderId = 23; // From ORD-20260325-023
$order = Order::find($orderId);

if (!$order) {
    echo "Order not found.\n";
    exit;
}

echo "Order ID: {$order->id}, Status: {$order->status}\n";
echo "Order Details:\n";
foreach ($order->orderDetails as $detail) {
    $product = $detail->product;
    echo " - Product: {$product->name} (ID: {$product->id}), Qty: {$detail->quantity}, Current Stock: {$product->stock}\n";
    
    $receipts = GoodsReceiptDetail::where('product_id', $product->id)
        ->where('remaining_quantity', '>', 0)
        ->get();
    
    echo "   Available Receipts (remaining_quantity > 0):\n";
    if ($receipts->isEmpty()) {
        echo "     NONE FOUND!\n";
        // Check ALL receipts for this product
        $allReceipts = GoodsReceiptDetail::where('product_id', $product->id)->get();
        foreach ($allReceipts as $r) {
            echo "     * Receipt #PN{$r->goods_receipt_id}: Qty: {$r->quantity}, Remaining: {$r->remaining_quantity}, Created: {$r->created_at}\n";
        }
    } else {
        foreach ($receipts as $r) {
            echo "     * Receipt #PN{$r->goods_receipt_id}: Remaining: {$r->remaining_quantity}\n";
        }
    }
}
