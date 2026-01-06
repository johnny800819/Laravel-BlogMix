<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // 訂單狀態常數
    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'user_id',
        'status',
        'total_amount',
        'receiver_name',
        'receiver_phone',
        'shipping_address',
        'payment_method',
        'trade_no',
        'paid_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    /**
     * 關聯：訂單項目
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * 關聯：購買會員
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
