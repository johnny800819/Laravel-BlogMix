@extends('Admin/Layouts/layout')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>訂單列表</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('admin/main-page')}}">首頁</a>
                </li>
                <li class="active">
                    <strong>訂單列表</strong>
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
                        <h5>訂單列表</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="order-filter-row pt15">
                            <form method="post" action="{{url('admin/order-list')}}" class="form-horizontal">

                                {{csrf_field()}}

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">付款狀態</label>

                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <input type="radio" value="0" id="" name="order_payment_state[]" checked> 未結帳 </label>
                                        <label class="radio-inline">
                                            <input type="radio" value="2" id="" name="order_payment_state[]"> 付款失敗 </label>
                                        <label class="radio-inline">
                                            <input type="radio" value="3" id="" name="order_payment_state[]"> 未完成交易 </label>
                                        <label class="radio-inline">
                                            <input type="radio" value="1" id="" name="order_payment_state[]"> 已付款 </label>
                                    </div>
                                </div>
                                <div class="hr-line-dashed-s1"></div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">訂購日期</label>

                                    <div class="col-sm-2">
                                        <input type="date" name="order_date_s" class="form-control" placeholder="開始">
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="date" name="order_date_e" class="form-control" placeholder="結束">
                                    </div>
                                </div>
                                <div class="hr-line-dashed-s1"></div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">出貨狀態</label>

                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <input type="radio" value="0" id="" name="order_logistics[]"> 新訂單 (尚未備貨)</label>
                                        <label class="radio-inline">
                                            <input type="radio" value="1" id="" name="order_logistics[]"> 尚未出貨 </label>
                                        <label class="radio-inline">
                                            <input type="radio" value="2" id="" name="order_logistics[]"> 已出貨 </label>
                                    </div>
                                </div>
                                <div class="hr-line-dashed-s1"></div>

                                {{--<div class="form-group">--}}
                                    {{--<label class="col-sm-2 control-label">訂單編號</label>--}}
                                    {{--<div class="col-sm-4"><input type="text" class="form-control"></div>--}}
                                    {{--<label class="col-sm-2 control-label">收件人</label>--}}
                                    {{--<div class="col-sm-4"><input type="text" class="form-control"></div>--}}

                                {{--</div>--}}
                                {{--<div class="hr-line-dashed-s1"></div>--}}
                                {{----}}
                                {{--<div class="form-group">--}}

                                    {{--<label class="col-sm-2 control-label">訂購會員</label>--}}
                                    {{--<div class="col-sm-4"><input type="text" class="form-control"></div>--}}

                                {{--</div>--}}
                                {{--<div class="hr-line-dashed-s1"></div>--}}

                                <div class="form-group">
                                    <div class="col-sm-4 col-sm-offset-2">
                                        <button class="btn btn-primary" type="submit">搜尋</button>
                                        <button class="btn btn-default" type="button" onclick="javascript:window.location.reload()">重設</button>
                                    </div>
                                </div>
                            </form>

                        </div>

                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                            <tr>
                                <th class="text-center">編號</th>
                                <th class="text-center">建立日期</th>
                                <th class="text-center">收件人</th>
                                <th class="text-center">訂購會員</th>
                                <th class="text-center">付款狀態</th>
                                <th class="text-center">出貨狀態</th>
                                <th class="text-center">交易金額</th>
                                <th class="text-center">功能</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $d)
                            <tr>
                                <td class="text-center">{{$d->order_id}}</td>
                                <td class="text-center">{{$d->order_date}}</td>
                                <td class="text-center">{{$d->name}}</td>
                                <td class="text-center"><a href="#">{{$d->email}}</a></td>
                                <td class="text-center">
                                    @if($d->order_payment_state == '0')
                                    <span class="text-danger">
                                        <i class="fa fa-exclamation-circle mr5" aria-hidden="true"></i>
                                        未付款
                                    </span>
                                    @else
                                    <span class="text-success">
                                        <i class="fa fa-exclamation-circle mr5" aria-hidden="true"></i>
                                        已付款
                                    </span>
                                    @endif
                                </td>
                                <td class="text-center">(尚未串物流)</td>
                                <td class="text-right">NT${{$d->order_money}} 元</td>
                                <td class="text-center"><a href="{{url('admin/order-list').'/'.$d->order_id}}">管理</a></td>
                            </tr>
                            @endforeach
                            {{--<tr>--}}
                                {{--<td class="text-center">2</td>--}}
                                {{--<td class="text-center">2016-07-27 15:15:21</td>--}}
                                {{--<td class="text-center">王小明</td>--}}
                                {{--<td class="text-center"><a href="#">ray</a></td>--}}
                                {{--<td class="text-center"><span class="text-success"><i class="fa fa-check mr5"--}}
                                                                                      {{--aria-hidden="true"></i>已結帳</span>--}}
                                {{--</td>--}}
                                {{--<td class="text-center">新訂單 (尚未備貨)</td>--}}
                                {{--<td class="text-right">NT$485元</td>--}}
                                {{--<td class="text-center"><a href="order-detail.html">管理</a></td>--}}
                            {{--</tr>--}}
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
