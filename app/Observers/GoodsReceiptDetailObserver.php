<?php

namespace App\Observers;

use App\Models\GoodsReceiptDetail;
use App\Models\GoodsReceipt;

class GoodsReceiptDetailObserver
{
    /**
     * Handle the GoodsReceiptDetail "created" event.
     */
    public function created(GoodsReceiptDetail $detail): void
    {
        if ($detail->product) {
            $detail->product->increment('stock', $detail->quantity);
        }
        $this->updateReceiptTotal($detail->goods_receipt_id);
    }

    /**
     * Handle the GoodsReceiptDetail "updated" event.
     */
    public function updated(GoodsReceiptDetail $detail): void
    {
        if ($detail->isDirty('quantity')) {
            $diff = (int) $detail->quantity - (int) $detail->getOriginal('quantity');
            if ($detail->product && $diff != 0) {
                $detail->product->increment('stock', $diff);
            }
        }
        
        // Always recalculate total if qty or price changed
        if ($detail->isDirty(['quantity', 'import_price'])) {
            $this->updateReceiptTotal($detail->goods_receipt_id);
        }
    }

    /**
     * Handle the GoodsReceiptDetail "deleted" event.
     */
    public function deleted(GoodsReceiptDetail $detail): void
    {
        if ($detail->product) {
            $detail->product->decrement('stock', $detail->quantity);
        }
        $this->updateReceiptTotal($detail->goods_receipt_id);
    }

    /**
     * Helper to recalculate the parent GoodsReceipt total amount
     */
    protected function updateReceiptTotal($receiptId): void
    {
        if (!$receiptId) return;

        $total = GoodsReceiptDetail::where('goods_receipt_id', $receiptId)
            ->get()
            ->sum(function($item) {
                return (float)$item->quantity * (float)$item->import_price;
            });

        GoodsReceipt::where('id', $receiptId)->update(['total_amount' => $total]);
    }
}
