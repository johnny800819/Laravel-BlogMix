<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class blog_order extends Model
{
    protected $table = 'blog_order';
    protected $primaryKey = 'order_id';
    public $timestamps = false;

    public function getInfowithUser()
    {
        return $this->join('blog_member','blog_order.order_member','=','blog_member.id')
                    ->where('order_state','0')->get();
    }

    public function getSpeceficInfowithUser($order_id)
    {
        return $this->join('blog_member','blog_order.order_member','=','blog_member.id')
            ->where('order_state','0')
            ->where('order_id',$order_id)->first();
    }

    public function getSpeceficInfowithOrderitems($order_id)
    {
        return $this->join('blog_order_item','blog_order.order_id','=','blog_order_item.oi_order_id')
                    ->join('blog_article','blog_order_item.oi_item_id','=','blog_article.art_id')
                    ->where('order_state','0')
                    ->where('order_id',$order_id)->get();
    }

    public function getInfoSearch($search)
    {
        $current = 1;
        $length = count($search);
        $condition = '';
        foreach ($search as $k=>$v){
            if($v != ''){
                $and = ($current++ < $length)?' and ':'';
                $condition .= $and.$k.' = \''.$v.'\'';
            }
        }
        if(empty($condition)){
            return $this->join('blog_member','blog_order.order_member','=','blog_member.id')
                        ->where('order_state','0')->get();
        }
        return $this->join('blog_member','blog_order.order_member','=','blog_member.id')
                    ->whereRaw('order_state = 0 '.$condition)->get();
    }
}
