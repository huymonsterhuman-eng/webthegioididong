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
        'payment_method'
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'total' => 'decimal:2',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderDetails(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }
}
