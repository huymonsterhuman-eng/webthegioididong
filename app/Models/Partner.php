<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Đối tác — dùng chung cho cả nhà cung cấp và đơn vị vận chuyển.
 * Phân biệt bằng cột `type`:
 *   - 'supplier': nhà cung cấp hàng (gắn với goods_receipts)
 *   - 'shipping_provider': đơn vị vận chuyển (gắn với orders)
 *
 * `is_active` = false để tạm dừng đối tác mà không xoá lịch sử.
 */
class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'phone',
        'email',
        'address',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /** Đơn hàng đã giao qua đối tác này (chỉ khi type=shipping_provider) */
    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Order::class);
    }

    /** Phiếu nhập kho từ đối tác này (chỉ khi type=supplier) */
    public function goodsReceipts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(GoodsReceipt::class, 'supplier_id');
    }
}
