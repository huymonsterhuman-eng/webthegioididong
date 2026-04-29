<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GoodsReceiptDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'goods_receipt_id',
        'product_id',
        'quantity',
        'remaining_quantity',
        'import_price',
    ];

    protected $casts = [
        'import_price' => 'decimal:2',
    ];

    public function goodsReceipt()
    {
        return $this->belongsTo(GoodsReceipt::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    protected static function booted()
    {
        static::creating(function ($detail) {
            if ($detail->remaining_quantity === null) {
                $detail->remaining_quantity = $detail->quantity;
            }
        });
    }
}
