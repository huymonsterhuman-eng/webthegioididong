<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory, \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'price',
        'sale_price',
        'image',
        'description',
        'screen',
        'chip',
        'camera',
        'battery',
        'os',
        'brand_id',
        'category_id',
        'stock',
        'weight',
        'is_featured',
        'is_active',
        'views',
    ];

    protected $casts = [
        'price'       => 'decimal:2',
        'sale_price'  => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active'   => 'boolean',
    ];

    /**
     * Scope: chỉ lấy sản phẩm đang kinh doanh (is_active = true)
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Stock khả dụng = stock - đang bị giữ bởi đơn hàng pending/confirmed
     *                        - đang bị giữ bởi phiếu xuất thủ công pending
     * Dùng để validate khi tạo đơn hàng hoặc phiếu xuất mới.
     */
    public function getAvailableStockAttribute(): int
    {
        $pendingOrderQty = OrderDetail::where('product_id', $this->id)
            ->whereHas('order', fn ($q) => $q->whereIn('status', ['pending', 'confirmed']))
            ->sum('quantity');

        $pendingIssueQty = GoodsIssueDetail::where('product_id', $this->id)
            ->whereNull('goods_receipt_detail_id') // stub pending records
            ->whereHas('goodsIssue', fn ($q) => $q->where('status', 'pending'))
            ->sum('quantity');

        return max(0, $this->stock - (int) $pendingOrderQty - (int) $pendingIssueQty);
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function productImages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function orderDetails(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function collections(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Collection::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function goodsReceiptDetails(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(GoodsReceiptDetail::class);
    }

    public function goodsIssueDetails(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(GoodsIssueDetail::class);
    }

    public function averageRating()
    {
        return $this->reviews()->where('is_hidden', false)->avg('rating') ?? 0;
    }
}
