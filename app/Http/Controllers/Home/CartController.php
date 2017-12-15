<?php

namespace App\Http\Controllers\Home;

use App\Http\Model\blog_cart;
use App\Http\Model\blog_cart_item;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Home\HomeController;
use Illuminate\Support\Facades\Auth;

class CartController extends HomeController
{
    public function __construct()
    {
        //這邊不設計再次驗證,因為購物車僅給會員（已驗證）使用
        //$this->middleware('auth');
        parent::__construct();
    }

    public function index(){

        $cart = blog_cart::where('cart_user_id',Auth::user()->id)->first();
        //dd($cart);
        if(!$cart){
            $cart = new blog_cart();
            $cart->cart_user_id = Auth::user()->id;
            $cart->save();
        }

        $items = $cart->cartItems;
        //dd($items);
        $total=0;
        foreach($items as $item){
            //下面的article是model中自寫的方法
            $total+=$item->article->art_view;
        }
        return view('Home.Payment.shopping-step-1')->with(compact('items','total'));
    }

    public function update ($productId)
    {
        $msg = "";
        $cart = blog_cart::where('cart_user_id', Auth::user()->id)->first();
        if (!$cart) {
            $cart = new blog_cart();
            $cart->cart_user_id = Auth::user()->id;
            $cart->save();
        }

        $delicate = blog_cart_item::where('product_id', $productId)->first();
        if($delicate){
            $msg = '已加入購物車囉,不能重複加入';
        }else{
            $cartItem = new blog_cart_item();
            $cartItem->product_id = $productId;
            $cartItem->cart_id = $cart->cart_id;
            $re = $cartItem->save();

            if ($re) {
                $msg = '成功加入購物車！';
            }else{
                $msg = '加入失敗,請重新整理後再試一次';
            }
        }
        return $msg;
    }

    public function destroy($id){

        blog_cart_item::destroy($id);
        return redirect('/cart');
    }
}
