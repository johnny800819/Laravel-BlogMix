<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class blog_cart_item extends Model
{
    protected $table = 'blog_cart_item';
    protected $primaryKey = 'item_id';
    public $timestamps = true;
    protected $guarded = [];

    public function cart()
    {
        return $this->belongsTo('App\Http\Model\blog_cart');
    }

//    public function product()
//    {
//        return $this->belongsTo('App\Product');
//    }

    public function article()
    {
        //blog_cart_item與blog_article的一對一關係
        return $this->belongsTo('App\Http\Model\blog_article', 'product_id');
    }
}
