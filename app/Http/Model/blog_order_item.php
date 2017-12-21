<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class blog_order_item extends Model
{
    protected $table = 'blog_order_item';
    protected $primaryKey = 'oi_id';
    public $timestamps = false;
}
