@extends('Admin/Layouts/layout')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>訂單管理</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('admin/main-page')}}">首頁</a>
                </li>
                <li>
                    <a href="{{url('admin/order-list')}}">訂單列表</a>
                </li>
                <li class="active">
                    <strong>訂單管理</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2">

        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">

            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>購買品項</h5>
                    </div>
                    <div class="ibox-content">
                        <table class="table table-striped table-bordered table-hover dataTables-example table-style-1">
                            <thead>
                            <tr>
                                <th class="text-center" width="10%">編號</th>
                                <th class="text-center" width="10%">商品縮圖</th>
                                <th class="text-center" width="30%">商品名稱</th>
                                <th class="text-center" width="10%">規格1</th>
                                <th class="text-center" width="10%">規格2</th>
                                <th class="text-center" width="10%">單價</th>
                                <th class="text-center" width="10%">數量</th>
                                <th class="text-center" width="10%">小計</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($order_item as $item)
                            <tr>
                                <td class="text-center">{{$item->oi_item_id}}</td>
                                <td class="text-center"><img src="{{url($item->art_thumb)}}" width="560" height="560"
                                                             class="img-style"></td>
                                <td class="text-left">{{$item->art_title}}</td>
                                <td class="text-center">xxxx</td>
                                <td class="text-center">xxxx</td>
                                <td class="text-right">NT${{$item->art_view}} 元</td>
                                <td class="text-center">{{$item->oi_count}}</td>
                                <td class="text-right">NT${{$item->art_view * $item->oi_count}} 元</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <div class="row">

                            <div class="col-md-10 col-sm-10 col-xs-10 confirm-title">貨到付款手續費：</div>
                            <div class="col-md-2 col-sm-2 col-xs-2 confirm-content">暫不計算</div>
                            <div class="col-md-10 col-sm-10 col-xs-10 confirm-title">運費：</div>
                            <div class="col-md-2 col-sm-2 col-xs-2 confirm-content">暫不計算</div>
                            <div class="col-md-10 col-sm-10 col-xs-10 confirm-title text-danger">消費總金額：</div>
                            <div class="col-md-2 col-sm-2 col-xs-2 confirm-content text-danger">${{$order->order_money}} 元</div>

                        </div>

                    </div>
                </div>

            </div>

            <div class="col-lg-5">

                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>出貨和付款狀態</h5>
                    </div>
                    <div class="ibox-content">
                        <h4>出貨狀態</h4>
                            <button type="button" class="btn btn-w-m btn-default mr5">新訂單(未備貨)</button>
                            <i class="fa fa-chevron-right mr5" aria-hidden="true"></i>

                            <button type="button" class="btn btn-w-m btn-warning mr5">尚未出貨</button>
                            <i class="fa fa-chevron-right mr5" aria-hidden="true"></i>

                            <button type="button" class="btn btn-w-m btn-primary mr5">已出貨</button>
                            <i class="fa fa-check mr5" aria-hidden="true"></i>

                        <h4 style="margin-top:20px;">付款狀態</h4>
                            <button type="button" class="btn btn-w-m btn-default mr5">未結帳</button>
                            <i class="fa fa-chevron-right mr5" aria-hidden="true"></i>

                            <button type="button" class="btn btn-w-m btn-primary mr5">已付款</button>
                                <i class="fa fa-check mr5" aria-hidden="true"></i>

                            <button type="button" class="btn btn-w-m btn-danger mr5">付款失敗</button>
                                <i class="fa fa-exclamation mr5" aria-hidden="true"></i>

                        <div style="border-top:1px dashed #ddd; margin-top:20px; padding-top:20px;">
                            <button type="button" class="btn btn-w-m btn-success">修改</button>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-lg-7">

                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>寄送資訊</h5>
                    </div>
                    <div class="ibox-content">
                        <table class="table table-striped table-bordered table-hover dataTables-example table-style-1">
                            <thead>
                            <tr>
                                <th class="text-center" width="15%">收件人姓名</th>
                                <th class="text-center" width="40%">收件地址</th>
                                <th class="text-center" width="15%">付款方式</th>
                                <th class="text-center" width="15%">發票資訊</th>
                                <th class="text-center" width="15%">連絡電話</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-center">{{$order->name}}</td>
                                <td class="text-left">{{$order->order_address}}</td>
                                <td class="text-center">
                                    @php
                                        switch($order->order_payment){
                                            case 0:
                                                echo '信用卡';
                                            break;
                                            case '1':
                                                echo 'ATM轉帳';
                                            break;
                                            case '2':
                                                echo '貨到付款';
                                            break;
                                        }
                                    @endphp
                                </td>
                                <td class="text-center">
                                    @php
                                        switch($order->order_invoice){
                                            case '0':
                                                echo '二聯式統一發票';
                                            break;
                                            case '1':
                                                echo '三聯式統一發票';
                                            break;

                                        }
                                    @endphp
                                </td>
                                <td class="text-center">{{$order->order_mobile}}</td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
