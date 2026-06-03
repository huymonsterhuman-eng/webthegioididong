<?php

namespace App\Observers;

use App\Models\GoodsReceiptDetail;
use App\Models\GoodsReceipt;

/**
 * Observer cho GoodsReceiptDetail — đồng bộ tự động cột `products.stock` và
 * `goods_receipts.total_amount` mỗi khi detail thay đổi.
 *
 * Quan trọng:
 *   - Stock chỉ được cộng/trừ khi receipt đã `completed`. Khi pending: chỉ lưu data,
 *     stock sẽ được cộng một lần khi admin xác nhận phiếu.
 *   - `remaining_quantity` thay đổi (do FIFO xuất kho hoặc hoàn kho) → tự cộng/trừ stock.
 *   - Ngăn giảm quantity xuống dưới mức đã bán; ngăn xoá detail khi sẽ làm stock âm.
 */
class GoodsReceiptDetailObserver
{
    /**
     * Trước khi cập nhật detail: chặn giảm quantity nếu sẽ làm stock < 0.
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
     * Sau khi tạo detail: cộng stock NẾU phiếu nhập đã completed.
     * Nếu phiếu vẫn pending, stock sẽ được cộng khi admin xác nhận.
     */
    public function created(GoodsReceiptDetail $detail): void
    {
        // Chỉ cộng stock nếu phiếu nhập đã ở trạng thái completed
        // (pending = chờ xác nhận, stock sẽ được cộng khi admin xác nhận)
        $receipt = GoodsReceipt::find($detail->goods_receipt_id);
        if ($receipt && $receipt->status === 'completed' && $detail->product) {
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
     * Sau khi update detail:
     *   - Nếu quantity thay đổi & phiếu completed: điều chỉnh stock theo diff.
     *   - Nếu remaining_quantity thay đổi (FIFO xuất kho hoặc hoàn kho): điều chỉnh stock.
     *   - Luôn tính lại total_amount của phiếu nhập.
     */
    public function updated(GoodsReceiptDetail $detail): void
    {
        if ($detail->isDirty('quantity')) {
            $oldQty = (int) $detail->getOriginal('quantity');
            $newQty = (int) $detail->quantity;
            $diff = $newQty - $oldQty;

            // Chỉ điều chỉnh stock khi phiếu nhập đã completed
            // Khi pending, confirm_receipt action sẽ cộng đúng số lượng sau
            $receipt = GoodsReceipt::find($detail->goods_receipt_id);
            if ($detail->product && $diff != 0 && $receipt && $receipt->status === 'completed') {
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
     * Trước khi xoá detail: chặn nếu sẽ làm stock của sản phẩm xuống âm.
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
     * Sau khi xoá detail: giảm stock của sản phẩm tương ứng và cập nhật lại total của phiếu.
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
     * Tính lại total_amount của phiếu nhập = sum(quantity × import_price) của tất cả details.
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
