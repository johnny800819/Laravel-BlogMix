<?php

namespace App\Http\Controllers\Home;

use App\Http\Model\blog_cart;
use App\Http\Model\blog_order;
use App\Http\Model\blog_order_item;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class OrderController extends HomeController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function confirm()
    {
        $data = Input::all();

        $messages = [
            'order_receiver.required'  => '收件人姓名不可空白！',
            'order_tel.required'  => '市內電話不可空白！',
            'order_mobile.required'  => '行動電話不可空白！',
        ];
        $validator = Validator::make($data, [
            'order_receiver' => 'required',
            'order_tel' => 'required',
            'order_mobile' => 'required',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        $cart = blog_cart::where('cart_user_id',Auth::user()->id)->first();
        $items = $cart->cartItems;

        return view('Home.Payment.shopping-step-2')->with(compact('items','data'));
    }

    public function order()
    {
        //新增訂單
        $data = Input::except('_token','count_statistics');
        $id = blog_order::insertGetId($data);

        //新增訂單時間及訂購帳號是
        $order = blog_order::where('order_id',$id)->first();
        $order->order_date = date('Y-m-d H:i:s');
        $order->order_member = Auth::user()->id;
        $order->save();

        //將當前訂單的內容(current cart content)記錄下來
        $cart = blog_cart::where('cart_user_id',Auth::user()->id)->first();
        $items = $cart->cartItems;

        $count_statistics = Input::get('count_statistics');
        $count = explode('|',$count_statistics);
        $index = 0;

        foreach($items as $item){
            $item->order_count = $count[$index++];
            $item->save();

            blog_order_item::insert([
                'oi_order_id' => $id,
                'oi_item_id' => $item->product_id,
                'oi_count' => $item->order_count,
            ]);
        }

        //綠界金流
        return view('reference.ECPayAIO_PHP.AioSDK.example.sample_All_CreateOrder')
            ->with(compact('order','items'));
    }

    public function order_complete(Request $request)
    {
        //訂單完成,變更資料庫訂單狀態

        //訂單完成,刪除購物車內容

        //返回訂單狀態page
        return view('Home.Payment.shopping-step-4');
    }
}
