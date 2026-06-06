<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\GoodsIssue;
use App\Models\GoodsIssueDetail;
use App\Models\GoodsReceiptDetail;

/**
 * Đơn hàng của khách hàng.
 *
 * Lifecycle: pending → confirmed → preparing → shipping → delivered (hoặc cancelled).
 * Khi chuyển sang `preparing`: tự động tạo GoodsIssue(pending) + stub details. Kho xử lý & bàn giao ĐVVC.
 * Khi GoodsIssue → completed (kho duyệt): FIFO chạy, stock trừ, order → shipping.
 * Khi `cancelled` sau khi GoodsIssue=completed: tự động hoàn lại remaining_quantity cho batch.
 * Khi `cancelled` khi GoodsIssue=pending: chỉ cancel phiếu, không ảnh hưởng stock.
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
        'preparing_at',
    ];

    protected $casts = [
        'subtotal'       => 'decimal:2',
        'total'          => 'decimal:2',
        'discount_amount'=> 'decimal:2',
        'shipping_fee'   => 'decimal:2',
        'delivered_at'   => 'datetime',
        'cancelled_at'   => 'datetime',
        'preparing_at'   => 'datetime',
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

    /** Phiếu xuất kho tự động được tạo khi đơn chuyển sang preparing */
    public function goodsIssue(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(GoodsIssue::class)->where('type', 'auto');
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
            // Auto-set payment_status = paid cho đơn chuyển khoản online
            if (in_array($order->payment_method, ['vnpay', 'momo']) && empty($order->payment_status)) {
                $order->payment_status = 'paid';
            }

            // Set timestamps khi status được preset lúc tạo đơn
            if ($order->status === 'preparing') {
                $order->preparing_at = now();
            } elseif ($order->status === 'delivered') {
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
                if ($order->status === 'preparing') {
                    $order->preparing_at = now();
                } elseif ($order->status === 'delivered') {
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
     * - preparing: tạo GoodsIssue(pending) + stub details để kho xử lý.
     *   FIFO chưa chạy, stock chưa bị trừ tại bước này.
     *   Kho duyệt phiếu xuất (GoodsIssue → completed) → FIFO chạy → order → shipping.
     *
     * - cancelled: hoàn voucher; nếu GoodsIssue=completed thì hoàn lại stock (Observer xử lý);
     *   nếu GoodsIssue=pending thì chỉ cancel phiếu, không ảnh hưởng stock.
     */
    public function handleStatusChange(): void
    {
        if (!($this->wasChanged('status') || $this->wasRecentlyCreated)) {
            return;
        }

        // 1. Handle Cancellation
        if ($this->status === 'cancelled') {
            // Hoàn voucher
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

            // Nếu GoodsIssue đã completed (FIFO đã chạy, stock đã trừ) → hoàn lại stock
            $completedIssue = GoodsIssue::where('order_id', $this->id)->where('status', 'completed')->first();
            if ($completedIssue) {
                $completedIssue->update(['status' => 'cancelled']);
                foreach ($completedIssue->details as $detail) {
                    $receiptDetail = GoodsReceiptDetail::find($detail->goods_receipt_detail_id);
                    if ($receiptDetail) {
                        $receiptDetail->increment('remaining_quantity', $detail->quantity);
                        // Observer.updated() sẽ tự gọi Product.increment('stock', diff)
                    }
                }
            }

            // Nếu GoodsIssue đang pending (FIFO chưa chạy, stock chưa bị trừ) → chỉ cancel phiếu
            $pendingIssue = GoodsIssue::where('order_id', $this->id)->where('status', 'pending')->first();
            if ($pendingIssue) {
                $pendingIssue->update(['status' => 'cancelled']);
            }
        }

        // 2. Handle Preparing — tạo GoodsIssue(pending) + stub details cho kho
        if ($this->status === 'preparing') {
            $existingIssue = GoodsIssue::where('order_id', $this->id)
                ->whereIn('status', ['pending', 'completed'])
                ->first();

            if (!$existingIssue && $this->orderDetails()->count() > 0) {
                $goodsIssue = GoodsIssue::create([
                    'order_id'   => $this->id,
                    'type'       => 'auto',
                    'total_cogs' => 0,
                    'status'     => 'pending', // Kho cần duyệt trước khi stock bị trừ
                ]);

                // Tạo stub details để kho thấy danh sách sản phẩm cần đóng gói
                foreach ($this->orderDetails as $orderDetail) {
                    GoodsIssueDetail::create([
                        'goods_issue_id'           => $goodsIssue->id,
                        'goods_receipt_detail_id'  => null, // Stub — FIFO chưa chạy
                        'product_id'               => $orderDetail->product_id,
                        'quantity'                 => $orderDetail->quantity,
                        'import_price'             => 0,
                        'total_price'              => 0,
                    ]);
                }

                \App\Services\ActivityLogService::log(
                    'order_preparing',
                    "Đơn hàng #{$this->id} chuyển sang chuẩn bị hàng. Phiếu xuất #{$goodsIssue->id} tạo chờ kho duyệt.",
                    'inventory',
                    $goodsIssue,
                    ['order_id' => $this->id]
                );
            }
        }
    }
}
