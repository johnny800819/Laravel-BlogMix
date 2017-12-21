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
                    <table class="table_style1">
                        <tr>
                            <th scope="col">訂單編號</th>
                            <th scope="col">訂單日期</th>
                            <th scope="col">訂單金額</th>
                            <th scope="col">出貨方式（物流）</th>
                            <th scope="col">付款狀態</th>
                            <th scope="col">詳細內容</th>
                        </tr>
                        @foreach($data as $d)
                        <tr>
                            <td class="text-center">{{$d->order_id}}</td>
                            <td class="text-center">{{date('Y-m-d',strtotime($d->order_date))}}</td>
                            <td class="text-center">${{$d->order_money}} 元</td>
                            <td class="text-center">(尚未串物流)</td>
                            <td class="text-center">
                                @if($d->order_payment_state == '0')
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
                            <td class="text-center">
                                <form action="{{url('member-order-detail').'/'.$d->order_id}}" method="post">
                                    {{csrf_field()}}
                                    <a href="#">
                                        <input type="submit" value="前往">
                                    </a>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        {{--<tr>--}}
                            {{--<td class="text-center">002</td>--}}
                            {{--<td class="text-center">2016-08-16</td>--}}
                            {{--<td class="text-left">【台灣華彩文創】九份風情能量杯</td>--}}
                            {{--<td class="text-center">新訂單 (尚未備貨)</td>--}}
                            {{--<td class="text-center"><span class="success"><i class="fa fa-check-circle mr5"--}}
                                                                             {{--aria-hidden="true"></i>已付款</span></td>--}}
                            {{--<td class="text-center"><a href="member-order-detail.html">前往</a></td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                            {{--<td class="text-center">002</td>--}}
                            {{--<td class="text-center">2016-08-16</td>--}}
                            {{--<td class="text-left">【台灣華彩文創】九份風情能量杯</td>--}}
                            {{--<td class="text-center">新訂單 (尚未備貨)</td>--}}
                            {{--<td class="text-center"><span class="warning"><i class="fa fa-exclamation-circle mr5"--}}
                                                                             {{--aria-hidden="true"></i>未結帳</span></td>--}}
                            {{--<td class="text-center"><a href="member-order-detail.html">前往</a></td>--}}
                        {{--</tr>--}}
                    </table>
                    <!--表格_結束-->

                </div>
            </div>

            <!--分頁_開始-->
            <div class="pagination-row">
                <nav aria-label="Page navigation">
                    {!! $data->links() !!}
                </nav>
            </div>
            <!--分頁_結束-->

        </div>
    </div>
@endsection