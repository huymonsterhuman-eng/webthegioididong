<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'user_id',
        'order_date',
        'total',
        'shipping_name',
        'shipping_address',
        'shipping_phone',
        'status',
        'payment_method',
        'shipping_method',
        'shipping_fee',
        'partner_id',
        'tracking_number',
        'voucher_id',
        'discount_amount'
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'total' => 'decimal:2',
        'discount_amount' => 'decimal:2',
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
        static::updated(function ($order) {
            // Check if status was changed to cancelled
            if ($order->isDirty('status') && $order->status === 'cancelled') {
                if ($order->voucher_id && $order->user_id) {
                    $voucher = Voucher::find($order->voucher_id);
                    if ($voucher) {
                        // Decrement used_count
                        if ($voucher->used_count > 0) {
                            $voucher->decrement('used_count');
                        }
                        // Update pivot table to false
                        $userVoucher = $voucher->users()->where('user_id', $order->user_id)->first();
                        if ($userVoucher && $userVoucher->pivot->is_used) {
                            $voucher->users()->updateExistingPivot($order->user_id, ['is_used' => false]);
                        }
                    }
                }
            }
        });
    }
}
