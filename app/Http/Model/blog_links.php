<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class blog_links extends Model
{
    protected $table = 'blog_links';
    protected $primaryKey = 'link_id';
    public $timestamps = false;
    protected $guarded = [];
}
