<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'discount_amount',
        'min_order_value',
        'max_discount',
        'expires_at',
        'usage_limit',
        'used_count',
        'is_active',
    ];

    protected $casts = [
        'discount_amount' => 'decimal:2',
        'min_order_value' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Check if the voucher is valid for a given order total.
     */
    public function isValid($orderTotal)
    {
        if (!$this->is_active) {
            return ['valid' => false, 'message' => 'Mã giảm giá này không hoạt động.'];
        }

        if ($this->expires_at && Carbon::now()->greaterThan($this->expires_at)) {
            return ['valid' => false, 'message' => 'Mã giảm giá đã hết hạn.'];
        }

        // Check user usage
        $user = auth()->user();
        if ($user) {
            $userVoucher = $this->users()->where('user_id', $user->id)->first();
            if ($userVoucher && $userVoucher->pivot->is_used) {
                return ['valid' => false, 'message' => 'Bạn đã sử dụng mã giảm giá này.'];
            }
        }

        if ($this->min_order_value > 0 && $orderTotal < $this->min_order_value) {
            return ['valid' => false, 'message' => 'Đơn hàng chưa đạt giá trị tối thiểu để sử dụng mã này.'];
        }

        return ['valid' => true, 'message' => 'Mã giảm giá hợp lệ.'];
    }

    /**
     * Calculate the discount amount based on the order total.
     */
    public function calculateDiscount($orderTotal)
    {
        $discount = 0;

        if ($this->type === 'fixed') {
            $discount = $this->discount_amount;
        } elseif ($this->type === 'percent') {
            $discount = $orderTotal * ($this->discount_amount / 100);
            if ($this->max_discount !== null && $discount > $this->max_discount) {
                $discount = $this->max_discount;
            }
        }

        return min($discount, $orderTotal); // Can't discount more than the total
    }

    /**
     * Get the users that have collected this voucher.
     */
    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_voucher')->withPivot('is_used')->withTimestamps();
    }
}
