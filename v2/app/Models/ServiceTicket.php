<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Category;
use App\Models\ServiceTicketReply;

class ServiceTicket extends Model
{
    use HasFactory;

    // 工單狀態常數
    const STATUS_OPEN = 'open';       // 開啟中
    const STATUS_REPLIED = 'replied'; // 已回覆
    const STATUS_CLOSED = 'closed';   // 已結案

    protected $fillable = [
        'user_id',
        'subject',
        'content',
        'status',
        'reply_content', // (Legacy) 舊版單一回覆欄位，現已改用 ServiceTicketReply 關聯
        'category_id',
    ];

    /**
     * 關聯：提問會員
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 關聯：問題分類
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * 關聯：回覆紀錄 (多對一)
     */
    public function replies()
    {
        return $this->hasMany(ServiceTicketReply::class);
    }
}

class ServiceTicketReply extends Model
{
    use HasFactory;

    protected $fillable = ['service_ticket_id', 'user_id', 'message'];

    /**
     * 關聯：所屬工單
     */
    public function ticket()
    {
        return $this->belongsTo(ServiceTicket::class, 'service_ticket_id');
    }

    /**
     * 關聯：回覆者 (可能是管理員或用戶)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
