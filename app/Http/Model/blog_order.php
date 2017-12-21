<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class blog_order extends Model
{
    protected $table = 'blog_order';
    protected $primaryKey = 'order_id';
    public $timestamps = false;
}
