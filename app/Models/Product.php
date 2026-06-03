<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Sản phẩm bán trong cửa hàng.
 *
 * - Dùng SoftDeletes để giữ lịch sử (đơn hàng cũ vẫn tham chiếu được).
 * - Trường `stock` chỉ được cập nhật qua FIFO Observer, KHÔNG sửa trực tiếp.
 * - `is_active` = false → ngừng kinh doanh (ẩn khỏi frontend & form mới).
 * - `is_featured` = true → hiển thị nổi bật trên trang chủ.
 */
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

    /** Danh mục sản phẩm */
    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /** Thương hiệu sản phẩm */
    public function brand(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /** Tất cả ảnh của sản phẩm (gallery, sắp xếp theo sort_order) */
    public function productImages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    /** Ảnh đại diện chính (is_primary = true) */
    public function primaryImage(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    /** Chi tiết các đơn hàng đã mua sản phẩm này (lịch sử) */
    public function orderDetails(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    /** Bộ sưu tập chứa sản phẩm (N:M) */
    public function collections(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Collection::class);
    }

    /** Đánh giá từ khách hàng */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /** Các batch nhập kho có chứa sản phẩm này (FIFO tracking) */
    public function goodsReceiptDetails(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(GoodsReceiptDetail::class);
    }

    /** Các batch xuất kho có chứa sản phẩm này (lịch sử xuất) */
    public function goodsIssueDetails(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(GoodsIssueDetail::class);
    }

    /** Điểm đánh giá trung bình (loại bỏ review bị ẩn) */
    public function averageRating()
    {
        return $this->reviews()->where('is_hidden', false)->avg('rating') ?? 0;
    }
}
