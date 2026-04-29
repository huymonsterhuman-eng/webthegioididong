<?php

namespace App\Observers;

use App\Models\GoodsReceiptDetail;
use App\Models\GoodsReceipt;

class GoodsReceiptDetailObserver
{
    /**
     * Handle the GoodsReceiptDetail "updating" event.
     */
    public function updating(GoodsReceiptDetail $detail): void
    {
        if ($detail->isDirty('quantity')) {
            $diff = (int) $detail->quantity - (int) $detail->getOriginal('quantity');
            if ($diff < 0 && $detail->product) {
                if ($detail->product->stock + $diff < 0) {
                    \Filament\Notifications\Notification::make()
                        ->title('Thao tác không hợp lệ')
                        ->body("Không thể giảm số lượng nhập vì sản phẩm {$detail->product->name} đã được bán.")
                        ->danger()
                        ->send();

                    throw new \Filament\Support\Exceptions\Halt();
                }
            }
        }
    }

    /**
     * Handle the GoodsReceiptDetail "created" event.
     */
    public function created(GoodsReceiptDetail $detail): void
    {
        if ($detail->product) {
            $detail->product->increment('stock', $detail->quantity);
        }
        $this->updateReceiptTotal($detail->goods_receipt_id);

        // \App\Services\ActivityLogService::log(
        //     'create_receipt_detail',
        //     "Đã thêm sản phẩm " . ($detail->product->name ?? "ID: {$detail->product_id}") . " vào phiếu nhập #{$detail->goods_receipt_id} (SL: {$detail->quantity}).",
        //     'inventory',
        //     $detail,
        //     [
        //         'product_id' => $detail->product_id,
        //         'product_name' => $detail->product->name ?? 'N/A',
        //         'quantity' => $detail->quantity,
        //         'import_price' => $detail->import_price,
        //         'parent_receipt_id' => $detail->goods_receipt_id
        //     ]
        // );
    }

    /**
     * Handle the GoodsReceiptDetail "updated" event.
     */
    public function updated(GoodsReceiptDetail $detail): void
    {
        if ($detail->isDirty('quantity')) {
            $oldQty = (int) $detail->getOriginal('quantity');
            $newQty = (int) $detail->quantity;
            $diff = $newQty - $oldQty;

            if ($detail->product && $diff != 0) {
                $detail->product->increment('stock', $diff);
            }

            \App\Services\ActivityLogService::log(
                'update_receipt_detail_qty',
                "Đã cập nhật số lượng dòng hàng #{$detail->id} (Sản phẩm: " . ($detail->product->name ?? 'N/A') . ") từ {$oldQty} thành {$newQty}.",
                'inventory',
                $detail,
                [
                    'old_quantity' => $oldQty,
                    'new_quantity' => $newQty,
                    'difference' => $diff,
                    'parent_receipt_id' => $detail->goods_receipt_id
                ]
            );
        }

        if ($detail->wasChanged('remaining_quantity')) {
            $oldRemaining = (int) $detail->getOriginal('remaining_quantity');
            $newRemaining = (int) $detail->remaining_quantity;
            $diff = $newRemaining - $oldRemaining;

            if ($detail->product && $diff != 0) {
                $detail->product->increment('stock', $diff);
            }

            \App\Services\ActivityLogService::log(
                'update_receipt_detail_remaining',
                "Đã cập nhật số lượng tồn kho của lô hàng #{$detail->id} (Sản phẩm: " . ($detail->product->name ?? 'N/A') . ") từ {$oldRemaining} thành {$newRemaining}.",
                'inventory',
                $detail,
                [
                    'old_remaining' => $oldRemaining,
                    'new_remaining' => $newRemaining,
                    'difference' => $diff,
                    'parent_receipt_id' => $detail->goods_receipt_id
                ]
            );
        }
        
        // Always recalculate total if qty or price changed
        if ($detail->isDirty(['quantity', 'import_price'])) {
            $this->updateReceiptTotal($detail->goods_receipt_id);
        }
    }

    /**
     * Handle the GoodsReceiptDetail "deleting" event.
     */
    public function deleting(GoodsReceiptDetail $detail): void
    {
        if ($detail->product) {
            if ($detail->product->stock - $detail->quantity < 0) {
                \Filament\Notifications\Notification::make()
                    ->title('Thao tác không hợp lệ')
                    ->body("Không thể xóa sản phẩm {$detail->product->name} khỏi phiếu nhập vì đã được bán.")
                    ->danger()
                    ->send();

                throw new \Filament\Support\Exceptions\Halt();
            }
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

        \App\Services\ActivityLogService::log(
            'delete_receipt_detail',
            "Đã xóa dòng sản phẩm " . ($detail->product->name ?? 'N/A') . " khỏi phiếu nhập #{$detail->goods_receipt_id}.",
            'inventory',
            $detail,
            [
                'product_id' => $detail->product_id,
                'quantity' => $detail->quantity,
                'parent_receipt_id' => $detail->goods_receipt_id
            ]
        );
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
