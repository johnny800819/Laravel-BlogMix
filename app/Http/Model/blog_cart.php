<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class blog_cart extends Model
{
    protected $table = 'blog_cart';
    protected $primaryKey = 'cart_id';
    public $timestamps = true;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Http\Model\blog_member');
    }

    public function cartItems()
    {
        //blog_cart與blog_cart_item的一對多關係
        return $this->hasMany('App\Http\Model\blog_cart_item', 'cart_id', 'cart_id');
    }
}
