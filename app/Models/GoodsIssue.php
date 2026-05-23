<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function isPending(): bool   { return $this->status === 'pending'; }
    public function isCompleted(): bool { return $this->status === 'completed'; }
    public function isCancelled(): bool { return $this->status === 'cancelled'; }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function details()
    {
        return $this->hasMany(GoodsIssueDetail::class);
    }
}
