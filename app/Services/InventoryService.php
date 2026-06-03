<?php

namespace App\Services;

use App\Models\GoodsIssue;
use App\Models\GoodsIssueDetail;
use App\Models\GoodsReceiptDetail;
use Exceptions;
use Illuminate\Support\Facades\DB;

/**
 * Service quản lý kho theo FIFO (First-In-First-Out).
 *
 * Lấy hàng từ những batch (GoodsReceiptDetail) được nhập kho sớm nhất trước,
 * tới khi đủ số lượng cần. Mỗi lần trừ kho sẽ ghi vào GoodsIssueDetail để
 * theo dõi giá vốn (COGS) chính xác từng lô hàng.
 */
class InventoryService
{
    /**
     * Trừ kho theo FIFO cho 1 sản phẩm và ghi nhận vào GoodsIssueDetail.
     *
     * Dùng `lockForUpdate()` để tránh race condition khi nhiều đơn cùng xuất một lúc.
     * Nếu không đủ tồn kho → ném exception (transaction caller sẽ rollback).
     *
     * @param int $productId       ID sản phẩm cần xuất kho
     * @param int $neededQuantity  Số lượng cần xuất
     * @param GoodsIssue $goodsIssue Phiếu xuất cha (để link các detail)
     * @return array ['cogs' => float, 'batches' => array]  — tổng giá vốn và danh sách batch đã dùng
     * @throws \Exception nếu tồn kho không đủ
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
