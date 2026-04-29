<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\GoodsIssue;
use App\Models\GoodsIssueDetail;
use App\Models\GoodsReceiptDetail;

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

    public function voucher(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Voucher::class);
    }

    public function orderDetails(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function partner(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function activities(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(ActivityLog::class, 'subject');
    }

    /**
     * Get the formatted order code attribute.
     */
    protected function orderCode(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn() => 'ORD-' . \Carbon\Carbon::parse($this->created_at)->format('Ymd') . '-' . str_pad($this->id, 3, '0', STR_PAD_LEFT),
        );
    }

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
     * Handle logic when order status changes (Inventory, Vouchers, etc.)
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

                $goodsIssue = GoodsIssue::where('order_id', $this->id)->where('status', 'completed')->first();
                if ($goodsIssue) {
                    $goodsIssue->update(['status' => 'cancelled']);
                    foreach ($goodsIssue->details as $detail) {
                        $receiptDetail = GoodsReceiptDetail::find($detail->goods_receipt_detail_id);
                        if ($receiptDetail) {
                            $receiptDetail->increment('remaining_quantity', $detail->quantity);
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

                    $totalCogs = 0;
                    $allBatches = [];
                    $inventoryService = new \App\Services\InventoryService();

                    try {
                        foreach ($this->orderDetails as $orderDetail) {
                            $result = $inventoryService->reduceStock(
                                $orderDetail->product_id,
                                $orderDetail->quantity,
                                $goodsIssue
                            );
                            $totalCogs += $result['cogs'];
                            $allBatches = array_merge($allBatches, $result['batches']);
                        }
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
                        \Illuminate\Support\Facades\Log::error("Goods Issue Auto-creation Failed: " . $e->getMessage());
                    }
                }
            }
        }
    }
}
