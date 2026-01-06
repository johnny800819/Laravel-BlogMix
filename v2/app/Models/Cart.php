<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
    ];

    /**
     * 關聯：購物車項目
     */
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * 計算購物車總金額
     */
    public function total()
    {
        return $this->items->sum(function ($item) {
            return $item->article ? $item->article->price * $item->quantity : 0;
        });
    }

    /**
     * 關聯：所屬會員 (可選)
     * 若為訪客 Session 購物車，此欄位可能為空
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
