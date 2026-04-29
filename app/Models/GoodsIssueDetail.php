<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GoodsIssueDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'goods_issue_id',
        'goods_receipt_detail_id',
        'product_id',
        'quantity',
        'import_price',
        'total_price',
    ];

    public function goodsIssue()
    {
        return $this->belongsTo(GoodsIssue::class);
    }

    public function goodsReceiptDetail()
    {
        return $this->belongsTo(GoodsReceiptDetail::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
