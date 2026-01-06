<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'article_id',
        'quantity',
        'price_at_purchase',
    ];

    protected $casts = [
        'price_at_purchase' => 'decimal:2',
        'quantity' => 'integer',
    ];

    /**
     * 關聯：所屬訂單
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * 關聯：商品(文章)
     */
    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
