<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Phiếu nhập kho từ nhà cung cấp.
 *
 * Workflow:
 *   - pending: vừa tạo, stock CHƯA cộng vào kho.
 *   - completed: admin xác nhận → stock được cộng + remaining_quantity = quantity.
 *   - cancelled: huỷ phiếu (chỉ huỷ được khi đang pending).
 *
 * `supplier_name` là snapshot — bảo toàn tên NCC lúc tạo phiếu, không đổi khi
 * NCC sau này thay tên trong bảng partners.
 */
class GoodsReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'supplier_name',    // snapshot — không thay đổi khi partner bị sửa tên
        'user_id',
        'total_amount',
        'note',
        'status',
    ];

    /** Đang chờ admin xác nhận nhập kho */
    public function isPending(): bool   { return $this->status === 'pending'; }
    /** Đã nhập kho thành công (stock đã được cộng) */
    public function isCompleted(): bool { return $this->status === 'completed'; }
    /** Đã huỷ phiếu (stock không bị ảnh hưởng) */
    public function isCancelled(): bool { return $this->status === 'cancelled'; }

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    /** Nhà cung cấp (FK đến partners.id, restrictOnDelete) */
    public function supplier()
    {
        return $this->belongsTo(Partner::class, 'supplier_id');
    }

    /** Người tạo phiếu (admin) */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Các dòng sản phẩm trong phiếu nhập (mỗi dòng = 1 batch FIFO) */
    public function details()
    {
        return $this->hasMany(GoodsReceiptDetail::class);
    }
}
