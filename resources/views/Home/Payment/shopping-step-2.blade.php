@extends('Home.Payment.payment-common')

@section('payment_content')
    <div class="row">
        <div class="col-md-12">

            <!--購物車清單_開始-->

            <div class="panel panel-bg-img min-height-300">
                <div class="panel-heading">
                    <h3 class="panel-title">最後確認</h3>
                </div>
                <div class="panel-body">

                    <div class="explanation">
                        <h4 class="note">注意事項：</h4>
                        <ul class="decimal">
                            <li>XXX</li>
                        </ul>
                    </div>
                    <!--表格_開始-->
                    <table class="table_style1">
                        <tr>
                            <th width="15%" scope="col">商品縮圖</th>
                            <th width="35%" scope="col">商品名稱</th>
                            <th width="10%" scope="col">規格1</th>
                            <th width="10%" scope="col">規格2</th>
                            <th width="10%" scope="col">網路價</th>
                            <th width="10%" scope="col">數量</th>
                            <th width="10%" scope="col">小計</th>
                        </tr>
                        <?php $count_statistics = ''; ?>
                        @foreach($items as $item)
                            <tr>
                                <td class="text-center"><img src="{{url($item->article->art_thumb)}}" width="560"
                                                             height="560" class="shopping-car"></td>
                                <td class="text-left">{{$item->article->art_title}}</td>
                                <td class="text-center">xxx</td>
                                <td class="text-center">xxx</td>
                                <td class="text-right">NT${{$item->article->art_view}}元</td>
                                <td class="text-right">{{$data['itemsCount_'.$item->article->art_id]}}</td>
                                <?php $count_statistics.= $data['itemsCount_'.$item->article->art_id].'|'; ?>
                                <td class="text-right">
                                    $<span id="account_{{$item->article->art_id}}">
                                        {{$data['itemsCount_'.$item->article->art_id] * $item->article->art_view}}
                                    </span>
                                    元
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    <!--表格_結束-->

                    <div class="row">

                        <div class="col-md-9 col-sm-9 col-xs-9 confirm-title">小計：</div>
                        <div class="col-md-2 col-sm-2 col-xs-2 confirm-content">${{$data['order_money']}} 元</div>
                        <div class="col-md-9 col-sm-9 col-xs-9 confirm-title">運費：</div>
                        <div class="col-md-2 col-sm-2 col-xs-2 confirm-content">暫不計算</div>
                        <div class="col-md-9 col-sm-9 col-xs-9 confirm-title note-red">消費總金額：</div>
                        <div class="col-md-2 col-sm-2 col-xs-2 confirm-content note-red">${{$data['order_money']}} 元</div>

                    </div>

                </div>

            </div>

            <!--購物車清單_結束-->

            <!--收件人_開始-->

            <div class="panel panel-bg-img">
                <div class="panel-heading">
                    <h3 class="panel-title">收件人資訊</h3>
                </div>
                <div class="panel-body">

                    <div class="row">
                        <div class="col-md-12 mb25">
                            <label class='control-label'>收件人姓名</label>
                            <p class="form-control-static">{{$data['order_receiver']}}</p>
                        </div>
                        <div class="col-md-6 mb25">
                            <label class='control-label'>市內電話</label>
                            <p class="form-control-static">{{$data['order_tel']}}</p>
                        </div>
                        <div class="col-md-6 mb25">
                            <label class='control-label'>行動電話</label>
                            <p class="form-control-static">{{$data['order_mobile']}}</p>
                        </div>

                        <div class="col-md-12">
                            <label class='control-label'>地址</label>
                            <p class="form-control-static">{{$data['order_address']}}</p>
                        </div>
                    </div>

                </div>
            </div>

            <!--收件人_結束-->

            <!--付款方式_開始-->

            <div class="panel panel-bg-img">
                <div class="panel-heading">
                    <h3 class="panel-title">付款方式</h3>
                </div>
                <div class="panel-body">

                    <div class="row">
                        <div class="col-md-12 mb25">
                            <p class="form-control-static">
                                @php
                                switch($data['order_payment'][0]){
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
                            </p>
                        </div>
                    </div>

                </div>
            </div>

            <!--付款方式_結束-->

            <!--發票資訊_開始-->

            <div class="panel panel-bg-img">
                <div class="panel-heading">
                    <h3 class="panel-title">發票資訊</h3>
                </div>
                <div class="panel-body">

                    <div class="row">

                        <div class="col-md-12 mb25">
                            <p class="form-control-static">
                                @php
                                    switch($data['order_invoice'][0]){
                                        case '0':
                                            echo '二聯式統一發票';
                                        break;
                                        case '1':
                                            echo '三聯式統一發票';
                                        break;

                                    }
                                @endphp
                            </p>
                        </div>

                        <div class="col-md-6">
                            <label class='control-label'>統一編號</label>
                            <p class="form-control-static">{{$data['order_uni_number']}}</p>
                        </div>
                        {{--<div class="col-md-6">--}}
                            {{--<label class='control-label'>發票抬頭</label>--}}
                            {{--<p class="form-control-static">王小明股份有限公司</p>--}}
                        {{--</div>--}}
                    </div>
                </div>
            </div>

            <!--發票資訊_結束-->
            <form action="{{url('order')}}" method="post">
                {{csrf_field()}}
                <div class="btn_submit">
                    <ul>
                        <li><a href="{{url('cart')}}" class="btn btn-default btn-black"><i
                                        class="fa fa-angle-left mr5"></i>上一步</a></li>
                        <li><button type="submit" class="btn btn-success">確定送出<i class="fa fa-angle-right ml5"></i></button></li>
                    </ul>
                </div>
                <input type="hidden" name="order_money" value="{{$data['order_money']}}">
                <input type="hidden" name="order_receiver" value="{{$data['order_receiver']}}">
                <input type="hidden" name="order_tel" value="{{$data['order_tel']}}">
                <input type="hidden" name="order_mobile" value="{{$data['order_mobile']}}">
                <input type="hidden" name="order_address" value="{{$data['order_address']}}">
                <input type="hidden" name="order_payment" value="{{$data['order_payment'][0]}}">
                <input type="hidden" name="order_invoice" value="{{$data['order_invoice'][0]}}">
                <input type="hidden" name="order_uni_number" value="{{$data['order_uni_number']}}">
                <input type="hidden" name="count_statistics" value="{{$count_statistics}}">
            </form>
        </div>
    </div>
    <script>
        $('#step2').addClass('st2 select');
    </script>
@endsection
