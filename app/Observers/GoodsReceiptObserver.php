<?php

namespace App\Observers;

use App\Models\GoodsReceipt;
use App\Services\ActivityLogService;

class GoodsReceiptObserver
{
    /**
     * Handle the GoodsReceipt "created" event.
     */
    public function created(GoodsReceipt $goodsReceipt): void
    {
        // ActivityLogService::log(
        //     'create_receipt',
        //     "Đã tạo phiếu nhập kho #{$goodsReceipt->id} với tổng giá trị " . number_format((float)$goodsReceipt->total_amount, 0, ',', '.') . " ₫.",
        //     'inventory',
        //     $goodsReceipt,
        //     [
        //         'supplier_id' => $goodsReceipt->supplier_id,
        //         'total_amount' => $goodsReceipt->total_amount,
        //     ]
        // );
    }

    /**
     * Handle the GoodsReceipt "updated" event.
     */
    public function updated(GoodsReceipt $goodsReceipt): void
    {
        if ($goodsReceipt->isDirty(['supplier_id', 'total_amount', 'note'])) {
            ActivityLogService::log(
                'update_receipt',
                "Đã cập nhật thông tin phiếu nhập kho #{$goodsReceipt->id}.",
                'inventory',
                $goodsReceipt,
                [
                    'old_total' => $goodsReceipt->getOriginal('total_amount'),
                    'new_total' => $goodsReceipt->total_amount,
                ]
            );
        }
    }
}
