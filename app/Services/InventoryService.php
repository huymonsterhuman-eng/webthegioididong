<?php

namespace App\Services;

use App\Models\GoodsIssue;
use App\Models\GoodsIssueDetail;
use App\Models\GoodsReceiptDetail;
use Exceptions;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    /**
     * Reduces the stock for a given product by drawing from Goods Receipts using FIFO.
     * Records the deduction in GoodsIssueDetail.
     *
     * @param int $productId
     * @param int $neededQuantity
     * @param GoodsIssue $goodsIssue
     * @return array The result containing ['cogs' => COGS, 'batches' => array of details].
     * @throws \Exception if insufficient stock.
     */
    public function reduceStock(int $productId, int $neededQuantity, GoodsIssue $goodsIssue): array
    {
        $totalCogs = 0;
        $batches = [];
        $originalQuantity = $neededQuantity;

        // Ensure we are inside a transaction or caller is handling it
        DB::transaction(function () use ($productId, &$neededQuantity, &$totalCogs, &$batches, $goodsIssue, $originalQuantity) {
            // Get receipts with remaining quantity, oldest first (FIFO)
            // Use lockForUpdate to prevent race conditions during concurrent checkouts
            $receipts = GoodsReceiptDetail::where('product_id', $productId)
                ->where('remaining_quantity', '>', 0)
                ->orderBy('created_at', 'asc')
                ->lockForUpdate()
                ->get();

            /** @var GoodsReceiptDetail $receipt */
            foreach ($receipts as $receipt) {
                if ($neededQuantity <= 0) {
                    break;
                }

                $take = min($neededQuantity, $receipt->remaining_quantity);
                $neededQuantity -= $take;
                
                $receipt->remaining_quantity -= $take;
                $receipt->save();

                $totalPrice = $take * $receipt->import_price;
                $totalCogs += $totalPrice;

                GoodsIssueDetail::create([
                    'goods_issue_id' => $goodsIssue->id,
                    'goods_receipt_detail_id' => $receipt->id,
                    'product_id' => $productId,
                    'quantity' => $take,
                    'import_price' => $receipt->import_price,
                    'total_price' => $totalPrice,
                ]);

                $productName = \App\Models\Product::find($productId)?->name ?? "ID Product: {$productId}";
                $batches[] = [
                    'product_name' => $productName,
                    'receipt_detail_id' => $receipt->id,
                    'parent_receipt_id' => $receipt->goods_receipt_id,
                    'quantity_taken' => $take,
                    'import_price' => $receipt->import_price,
                ];
            }

            if ($neededQuantity > 0) {
                // Determine product name for better error logging, if needed
                $productName = \App\Models\Product::find($productId)?->name ?? "ID Product: {$productId}";
                throw new \Exception("Không đủ tồn kho cho sản phẩm: {$productName}. Còn thiếu: {$neededQuantity}");
            }
        });

        return [
            'cogs' => $totalCogs,
            'batches' => $batches
        ];
    }
}
