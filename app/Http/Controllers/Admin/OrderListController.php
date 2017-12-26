<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\blog_order;
use App\Http\Model\blog_order_item;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class OrderListController extends Controller
{
    public function index()
    {
        $obj = new blog_order();
        $data = $obj->getInfowithUser();
        return view('Admin.Order.order-list')->with(compact('data'));
    }

    public function show($order_id)
    {
        $obj = new blog_order();
        $order = $obj->getSpeceficInfowithUser($order_id);
        //dd($order);
        $order_item = $obj->getSpeceficInfowithOrderitems($order_id);
        return view('Admin.Order.order-detail')->with(compact('order','order_item'));
    }

    public function store()
    {
        //實際上執行搜尋功能
        $input = Input::except('_token');

        $order_logistics = (!empty($input['order_logistics'][0]))?'\'order_logistics\''.'=>'.$input['order_logistics'][0]:'';

        $search = [
            'order_payment_state' => $input['order_payment_state'][0],
            $order_logistics,
            'order_date_s' => $input['order_date_s'],
            'order_date_e' => $input['order_date_e'],
        ];

        $obj = new blog_order();
        $data = $obj->getInfoSearch($search);
        return view('Admin.Order.order-list')->with(compact('data'));
    }
}
