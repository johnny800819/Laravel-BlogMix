@extends('Home.Member.member-common')

@section('breadcrumb')
    <!--breadcrumb_開始-->
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="#">首頁</a></li>
                <li><a href="#">會員中心</a></li>
                <li class="active">最新客服回覆</li>
            </ol>
        </div>
    </div>
    <!--breadcrumb_結束-->
@endsection

@section('member_content')
    <div class="right-row">
        <div class="col-md-12">
            <div class="panel panel-bg-img min-height-300">
                <div class="panel-heading">
                    <h3 class="panel-title">歷史訂單查詢</h3>
                </div>
                <div class="panel-body">

                    <!--表格_開始-->
                    <table class="table_style1 mb30">
                        <caption>付款資訊</caption>
                        <tr>
                            <th scope="col">訂單編號</th>
                            <th scope="col">訂單日期</th>
                            <th scope="col">繳費金額</th>
                            <th scope="col">開立發票</th>
                            <th scope="col">付款方式</th>
                            <th scope="col">付款狀態</th>
                            <th scope="col">統一編號</th>
                        </tr>
                        <tr>
                            <td class="text-center">{{$order->order_id}}</td>
                            <td class="text-center">{{$order->order_date}}</td>
                            <td class="text-center">NT${{$order->order_money}} 元</td>
                            <td class="text-center">
                                @if($order->order_invoice == '0')
                                    二聯式
                                @else
                                    三聯式
                                @endif
                            </td>
                            <td class="text-center">信用卡</td>
                            <td class="text-center">
                                @if($order->order_payment_state == '0')
                                <span class="danger">
                                    <i class="fa fa-exclamation-circle mr5" aria-hidden="true"></i>
                                    未付款
                                </span>
                                @else
                                <span class="success">
                                    <i class="fa fa-exclamation-circle mr5" aria-hidden="true"></i>
                                    已付款
                                </span>
                                @endif

                            </td>
                            <td class="text-center">{{$order->order_uni_number}}</td>
                        </tr>
                    </table>
                    <!--表格_結束-->

                    <!--表格_開始-->
                    <table class="table_style1">
                        <caption>訂單明細</caption>
                        <tr>
                            <th scope="col">商品編號</th>
                            <th scope="col">縮圖</th>
                            <th scope="col">商品名稱</th>
                            <th scope="col">單價</th>
                            <th scope="col">數量</th>
                            <th scope="col">小計</th>
                        </tr>
                        @foreach($order_item as $d)
                        <tr>
                            <td class="text-center">{{$d->oi_item_id}}</td>
                            <td class="text-center"><img src="{{url($d->art_thumb)}}" width="560" height="560"
                                                         class="shopping-car"></td>
                            <td class="text-center">{{$d->art_title}}</td>
                            <td class="text-center">NT${{$d->art_view}} 元</td>
                            <td class="text-center">{{$d->oi_count}}</td>
                            <td class="text-center">NT${{$d->art_view * $d->oi_count}} 元</td>
                        </tr>
                        @endforeach
                    </table>
                    <!--表格_結束-->

                    <div class="row mt20">

                        <div class="col-md-9 col-sm-9 col-xs-9 mc-order-title">貨到付款手續費：</div>
                        <div class="col-md-3 col-sm-3 col-xs-3 mc-order-content">暫不計算</div>
                        <div class="col-md-9 col-sm-9 col-xs-9 mc-order-title">運費：</div>
                        <div class="col-md-3 col-sm-3 col-xs-3 mc-order-content">暫不計算</div>
                        <div class="col-md-9 col-sm-9 col-xs-9 mc-order-title note-red">消費總金額：</div>
                        <div class="col-md-3 col-sm-3 col-xs-3 mc-order-content note-red">${{$order->order_money}} 元</div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
