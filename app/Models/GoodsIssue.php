<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Phiếu xuất kho. Có 2 loại (type):
 *   - auto: tự động tạo khi Order chuyển sang "shipping" → FIFO chạy ngay → status=completed.
 *   - manual: admin tạo thủ công → status=pending → admin duyệt → FIFO chạy → status=completed.
 *
 * Detail rows với goods_receipt_detail_id = NULL là "stub" tạm thời cho phiếu manual
 * đang chờ duyệt — sẽ được thay bằng record FIFO thật khi admin duyệt.
 */
class GoodsIssue extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'type',
        'author_id',
        'note',
        'total_cogs',
        'status',
    ];

    /** Đang chờ admin duyệt (chỉ manual) — stock CHƯA bị trừ */
    public function isPending(): bool   { return $this->status === 'pending'; }
    /** Đã xuất kho thành công (stock đã trừ qua FIFO) */
    public function isCompleted(): bool { return $this->status === 'completed'; }
    /** Đã từ chối / huỷ (stock không bị ảnh hưởng) */
    public function isCancelled(): bool { return $this->status === 'cancelled'; }

    /** Admin tạo phiếu (chỉ có với type=manual) */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /** Đơn hàng nguồn (chỉ có với type=auto) */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /** Chi tiết các sản phẩm xuất, mỗi dòng tham chiếu 1 batch nguồn (FIFO) */
    public function details()
    {
        return $this->hasMany(GoodsIssueDetail::class);
    }
}
