<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * 可批量賦值的屬性
     */
    protected $fillable = [
        'category_id',
        'category_sub_id',
        'title',
        'content',
        'image_path',
        'price',
        'view_count',
        'is_published',
        'published_at',
    ];

    /**
     * 屬性轉型
     */
    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'price' => 'decimal:2',
    ];

    /**
     * 關聯：主分類
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * 關聯：子分類
     */
    public function subCategory()
    {
        return $this->belongsTo(Category::class, 'category_sub_id');
    }

    /**
     * Scope: 僅查詢已發佈的文章 (Only published articles)
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * 關聯：訂單項目
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
