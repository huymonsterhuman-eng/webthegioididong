<?php

namespace App\Observers;

use App\Models\Order;
use App\Services\ActivityLogService;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        ActivityLogService::log(
            'created_order',
            "Đơn hàng mới #{$order->id} đã được tạo.",
            'order',
            $order,
            ['total' => $order->total]
        );
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        if ($order->isDirty('status')) {
            $oldStatus = $order->getOriginal('status');
            $newStatus = $order->status;
            
            $statusLabels = [
                'pending' => 'Chờ xử lý',
                'confirmed' => 'Đã xác nhận',
                'shipping' => 'Đang giao hàng',
                'delivered' => 'Đã giao thành công',
                'cancelled' => 'Đã hủy',
            ];

            $oldLabel = $statusLabels[$oldStatus] ?? $oldStatus;
            $newLabel = $statusLabels[$newStatus] ?? $newStatus;

            ActivityLogService::log(
                $this->getActionFromStatus($newStatus),
                "Trạng thái đơn hàng #{$order->id} thay đổi từ '{$oldLabel}' sang '{$newLabel}'.",
                'order',
                $order,
                [
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                ]
            );
        }

        if ($order->isDirty('tracking_number')) {
            ActivityLogService::log(
                'updated_tracking',
                "Cập nhật mã vận đơn cho đơn hàng #{$order->id}: {$order->tracking_number}.",
                'order',
                $order,
                ['tracking_number' => $order->tracking_number]
            );
        }
    }

    /**
     * Map status to action key.
     */
    private function getActionFromStatus(string $status): string
    {
        return match ($status) {
            'confirmed' => 'confirmed_order',
            'shipping' => 'shipping_order',
            'delivered' => 'delivered_order',
            'cancelled' => 'cancelled_order',
            default => 'updated_status',
        };
    }
}
