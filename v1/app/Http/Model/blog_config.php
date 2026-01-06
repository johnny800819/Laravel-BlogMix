<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class blog_config extends Model
{
    protected $table = 'blog_config';
    protected $primaryKey = 'conf_id';
    public $timestamps = false;
    protected $guarded = [];
}
