<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class blog_category extends Model
{
    protected $table = 'blog_category';
    protected $primaryKey = 'cate_id';
    public $timestamps = false;
    protected $guarded = ['cate_id'];
}
