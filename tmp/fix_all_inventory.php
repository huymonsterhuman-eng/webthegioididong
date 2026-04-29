<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;
use App\Models\GoodsIssue;
use App\Models\GoodsIssueDetail;
use App\Models\GoodsReceiptDetail;
use App\Models\Product;

// 1. Fix remaining_quantity for all receipts if they were 0 but stock exists
$products = Product::all();
foreach ($products as $product) {
    $stock = $product->stock;
    $receipts = GoodsReceiptDetail::where('product_id', $product->id)
        ->orderBy('created_at', 'desc')
        ->get();
    
    foreach ($receipts as $receipt) {
        $take = min($stock, $receipt->quantity);
        $stock -= $take;
        $receipt->remaining_quantity = $take;
        $receipt->save();
    }
}
echo "Populated remaining_quantity.\n";

// 2. Fix Shipping Orders with no details
$shippingOrders = Order::where('status', 'shipping')->get();
foreach ($shippingOrders as $order) {
    $goodsIssue = GoodsIssue::where('order_id', $order->id)->where('status', 'completed')->first();
    if ($goodsIssue && $goodsIssue->details()->count() == 0) {
        echo "Fixing Order #{$order->id}...\n";
        $totalCogs = 0;
        foreach ($order->orderDetails as $orderDetail) {
            $neededQuantity = $orderDetail->quantity;
            $receipts = GoodsReceiptDetail::where('product_id', $orderDetail->product_id)
                ->where('remaining_quantity', '>', 0)
                ->orderBy('created_at', 'asc')
                ->get();

            foreach ($receipts as $receipt) {
                if ($neededQuantity <= 0) break;
                $take = min($neededQuantity, $receipt->remaining_quantity);
                $neededQuantity -= $take;
                $receipt->decrement('remaining_quantity', $take);
                $totalPrice = $take * $receipt->import_price;
                $totalCogs += $totalPrice;
                GoodsIssueDetail::create([
                    'goods_issue_id' => $goodsIssue->id,
                    'goods_receipt_detail_id' => $receipt->id,
                    'product_id' => $orderDetail->product_id,
                    'quantity' => $take,
                    'import_price' => $receipt->import_price,
                    'total_price' => $totalPrice,
                ]);
            }
        }
        $goodsIssue->update(['total_cogs' => $totalCogs]);
    }
}
echo "Done fixing shipping orders.\n";
