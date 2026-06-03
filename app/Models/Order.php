<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\GoodsIssue;
use App\Models\GoodsIssueDetail;
use App\Models\GoodsReceiptDetail;

/**
 * Đơn hàng của khách hàng.
 *
 * Lifecycle: pending → confirmed → shipping → delivered (hoặc cancelled).
 * Khi chuyển sang `shipping`: tự động tạo GoodsIssue + chạy FIFO trừ kho.
 * Khi `cancelled` sau khi đã ship: tự động hoàn lại remaining_quantity cho batch (Observer cộng lại stock).
 * `shipping_provider_name` là snapshot — không đổi khi đối tác vận chuyển đổi tên.
 */
class Order extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'user_id',
        'subtotal',
        'total',
        'shipping_name',
        'shipping_address',
        'shipping_phone',
        'status',
        'payment_method',
        'payment_status',
        'shipping_method',
        'shipping_fee',
        'partner_id',
        'shipping_provider_name',  // snapshot — không thay đổi khi partner bị sửa tên
        'tracking_number',
        'voucher_id',
        'discount_amount',
        'delivered_at',
        'cancelled_at',
    ];

    protected $casts = [
        'subtotal'       => 'decimal:2',
        'total'          => 'decimal:2',
        'discount_amount'=> 'decimal:2',
        'shipping_fee'   => 'decimal:2',
        'delivered_at'   => 'datetime',
        'cancelled_at'   => 'datetime',
        'payment_status' => 'string',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Voucher khách áp dụng cho đơn hàng */
    public function voucher(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Voucher::class);
    }

    /** Danh sách sản phẩm trong đơn (kèm snapshot tên, ảnh, giá) */
    public function orderDetails(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    /** Đơn vị vận chuyển (FK đến bảng partners type=shipping_provider) */
    public function partner(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    /** Lịch sử thao tác trên đơn hàng này (polymorphic) */
    public function activities(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(ActivityLog::class, 'subject');
    }

    /**
     * Mã đơn hàng được tạo tự động dạng "ORD-YYYYMMDD-NNN" (NNN là id padded 3 số).
     * Đây là accessor — không có cột thật trong DB.
     */
    protected function orderCode(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn() => 'ORD-' . \Carbon\Carbon::parse($this->created_at)->format('Ymd') . '-' . str_pad($this->id, 3, '0', STR_PAD_LEFT),
        );
    }

    /**
     * Lifecycle hooks: auto-set delivered_at/cancelled_at theo trạng thái,
     * và trigger handleStatusChange() sau mỗi lần create/update.
     */
    protected static function booted()
    {
        static::creating(function ($order) {
            // Set timestamps when status is preset
            if ($order->status === 'shipping') {
                // Usually doesn't happen on creation but good for consistency
            }
            if ($order->status === 'delivered') {
                $order->delivered_at = now();
            } elseif ($order->status === 'cancelled') {
                $order->cancelled_at = now();
            }
        });

        static::created(function ($order) {
            $order->handleStatusChange();
        });

        static::updating(function ($order) {
            // Set timestamps when status changes
            if ($order->isDirty('status')) {
                if ($order->status === 'delivered') {
                    $order->delivered_at = now();
                } elseif ($order->status === 'cancelled') {
                    $order->cancelled_at = now();
                }
            }
        });

        static::updated(function ($order) {
            $order->handleStatusChange();
        });
    }

    /**
     * Xử lý nghiệp vụ khi trạng thái đơn hàng thay đổi.
     *
     * - cancelled: hoàn voucher; nếu đã có GoodsIssue (đã ship) thì hoàn lại
     *   remaining_quantity cho batch — Observer sẽ tự cộng lại stock.
     * - shipping: tự động tạo GoodsIssue và chạy FIFO trừ kho qua InventoryService.
     */
    public function handleStatusChange(): void
    {
        // 1. Handle Cancellation (Restock vouchers & Goods Issue)
        if ($this->wasChanged('status') || $this->wasRecentlyCreated) {
            if ($this->status === 'cancelled') {
                if ($this->voucher_id && $this->user_id) {
                    $voucher = Voucher::find($this->voucher_id);
                    if ($voucher) {
                        if ($voucher->used_count > 0) {
                            $voucher->decrement('used_count');
                        }
                        $userVoucher = $voucher->users()->where('user_id', $this->user_id)->first();
                        if ($userVoucher && $userVoucher->pivot->is_used) {
                            $voucher->users()->updateExistingPivot($this->user_id, ['is_used' => false]);
                        }
                    }
                }

                // Nếu đơn đã shipping (có GoodsIssue), hoàn lại remaining_quantity.
                // Observer sẽ tự cộng stock khi remaining_quantity tăng — KHÔNG tự increment stock ở đây
                // để tránh double increment.
                // Nếu đơn chỉ pending/confirmed (chưa shipping), stock chưa bao giờ bị trừ → không cần restore.
                $goodsIssue = GoodsIssue::where('order_id', $this->id)->where('status', 'completed')->first();
                if ($goodsIssue) {
                    $goodsIssue->update(['status' => 'cancelled']);
                    foreach ($goodsIssue->details as $detail) {
                        $receiptDetail = GoodsReceiptDetail::find($detail->goods_receipt_detail_id);
                        if ($receiptDetail) {
                            $receiptDetail->increment('remaining_quantity', $detail->quantity);
                            // Observer.updated() sẽ tự gọi Product.increment('stock', diff)
                        }
                    }
                }
            }

            // 2. Handle Shipping (Auto Goods Issue)
            if ($this->status === 'shipping') {
                $existingIssue = GoodsIssue::where('order_id', $this->id)->where('status', 'completed')->first();
                if (!$existingIssue) {
                    // Important: If this is called in 'created' event from Filament, 
                    // orderDetails might not be saved yet. 
                    // However, manual admin status changes usually happen via 'updated'
                    if ($this->orderDetails()->count() === 0) {
                        return; // Wait for details to be available (usually in RelationManager or post-create)
                    }

                    $goodsIssue = GoodsIssue::create([
                        'order_id' => $this->id,
                        'type' => 'auto',
                        'total_cogs' => 0,
                        'status' => 'completed',
                    ]);

                    $allBatches = [];
                    $inventoryService = new \App\Services\InventoryService();

                    try {
                        foreach ($this->orderDetails as $orderDetail) {
                            $result = $inventoryService->reduceStock(
                                $orderDetail->product_id,
                                $orderDetail->quantity,
                                $goodsIssue
                            );
                            $allBatches = array_merge($allBatches, $result['batches']);
                        }

                        // Tính total_cogs trực tiếp từ DB sau khi tất cả details đã được lưu
                        // (tránh lỗi closure-reference với DB::transaction)
                        $totalCogs = $goodsIssue->details()->sum('total_price');
                        $goodsIssue->update(['total_cogs' => $totalCogs]);

                        \App\Services\ActivityLogService::log(
                            'auto_goods_issue',
                            "Hệ thống tự động tạo phiếu xuất kho #{$goodsIssue->id} cho Đơn hàng #{$this->id}.",
                            'inventory',
                            $goodsIssue,
                            [
                                'order_id' => $this->id,
                                'total_cogs' => $totalCogs,
                                'detailed_batches' => $allBatches
                            ]
                        );
                    } catch (\Exception $e) {
                        // Xóa phiếu xuất rỗng nếu reduceStock thất bại (tránh orphan record)
                        $goodsIssue->details()->delete();
                        $goodsIssue->delete();
                        \Illuminate\Support\Facades\Log::error("Goods Issue Auto-creation Failed for Order #{$this->id}: " . $e->getMessage());
                    }
                }
            }
        }
    }
}
