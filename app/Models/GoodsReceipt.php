<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function isPending(): bool   { return $this->status === 'pending'; }
    public function isCompleted(): bool { return $this->status === 'completed'; }
    public function isCancelled(): bool { return $this->status === 'cancelled'; }

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    public function supplier()
    {
        return $this->belongsTo(Partner::class, 'supplier_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(GoodsReceiptDetail::class);
    }
}
