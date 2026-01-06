<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'article_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    /**
     * 關聯：所屬購物車
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * 關聯：商品(文章)
     */
    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
