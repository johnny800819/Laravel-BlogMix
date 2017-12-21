@extends('Home.Payment.payment-common')

@section('payment_content')
    <div class="row">
        <form id="order_form" method="post" action="{{url('confirm')}}" accept-charset="UTF-8">
            {{csrf_field()}}
            <div class="col-md-12">

                <!--購物車清單_開始-->

                <div class="panel panel-bg-img min-height-300">
                    <div class="panel-heading">
                        <h3 class="panel-title">購物車清單</h3>
                    </div>

                    <div class="panel-body">

                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

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
                                <th width="25%" scope="col">商品名稱</th>
                                <th width="10%" scope="col">規格1</th>
                                <th width="10%" scope="col">規格2</th>
                                <th width="10%" scope="col">網路價</th>
                                <th width="10%" scope="col">數量</th>
                                <th width="10%" scope="col">小計</th>
                                <th width="10%" scope="col">刪除</th>
                            </tr>
                            @foreach($items as $item)
                                <tr>
                                    <td class="text-center"><img src="{{url($item->article->art_thumb)}}" width="560"
                                                                 height="560" class="shopping-car"></td>
                                    <td class="text-left">{{$item->article->art_title}}</td>
                                    <td class="text-center">xxx</td>
                                    <td class="text-center">xxx</td>
                                    <td class="text-right">NT${{$item->article->art_view}}元</td>
                                    <td class="text-right">
                                        <select id="itemsCount_{{$item->article->art_id}}"
                                                name="itemsCount_{{$item->article->art_id}}"
                                                onchange="Accounting('{{$item->article->art_view}}', '{{$item->article->art_id}}', cb_fun)"
                                                class="form-control">
                                            @for($i=1; $i<=5; $i++)
                                                <option value="{{$i}}">{{$i}}</option>
                                            @endfor
                                        </select>
                                    </td>
                                    <td class="text-right">
                                        $<span id="account_{{$item->article->art_id}}">{{$item->article->art_view}}</span>
                                        元
                                    </td>
                                    <td class="text-center">
                                        <button type="button" onclick="destroy('{{$item->item_id}}')"
                                                class="btn btn-default btn-sm"><i class="fa fa-times mr5"></i>刪除
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                        <!--表格_結束-->

                        <div class="row">

                            <div class="col-md-9 col-sm-9 col-xs-9 payinfo-title">小計：</div>
                            <div class="col-md-2 col-sm-2 col-xs-2 payinfo-content">
                                $<span id="TotalPrice">{{$total}}</span> 元
                                <input type="hidden" id="order_money" name="order_money" value="{{$total}}"/>
                            </div>
                            {{--<div class="col-md-9 col-sm-9 col-xs-9 payinfo-title">貨到付款手續費：</div>--}}
                            {{--<div class="col-md-2 col-sm-2 col-xs-2 payinfo-content">$50 元</div>--}}
                            {{--<div class="col-md-9 col-sm-9 col-xs-9 payinfo-title">運費：</div>--}}
                            {{--<div class="col-md-2 col-sm-2 col-xs-2 payinfo-content">$250 元</div>--}}
                            {{--<div class="col-md-9 col-sm-9 col-xs-9 payinfo-title note-red">消費總金額：</div>--}}
                            {{--<div class="col-md-2 col-sm-2 col-xs-2 payinfo-content note-red">$1,250 元</div>--}}
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
                                <label class='control-label'><span class="note">*</span>收件人姓名</label>
                                <input type="text" name="order_receiver" class="form-control">
                            </div>
                            <div class="col-md-6 mb25">
                                <label class='control-label'><span class="note">*</span>市內電話</label>
                                <input type="text" name="order_tel" class="form-control">
                            </div>
                            <div class="col-md-6 mb25">
                                <label class='control-label'><span class="note">*</span>行動電話</label>
                                <input type="text" name="order_mobile" class="form-control">
                            </div>

                            <div class="col-md-2">
                                <label class='control-label'>郵遞區號</label>
                                <input onkeyup="set_address()" type="text" id="postal_code" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <label class='control-label'>縣市</label>
                                <select onchange="set_address()" id="county" class="form-control">
                                    <option value="台北市">台北市</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class='control-label'>區域</label>
                                <select onchange="set_address()" id="region" class="form-control">
                                    <option value="信義區">信義區</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class='control-label'>街道</label>
                                <input onkeyup="set_address()" id="street" type="text" class="form-control"
                                       placeholder="">
                            </div>
                            <input type="hidden" name="order_address" id="order_address">
                        </div>

                    </div>
                </div>

                <!--收件人_結束-->

                <!--付款方式_開始-->

                <div class="panel panel-bg-img">
                    <div class="panel-heading">
                        <h3 class="panel-title">選擇付款方式</h3>
                    </div>
                    <div class="panel-body">

                        <div class="row">

                            <div class="col-md-12 mb25">
                                <input type="radio" name="order_payment[]" value="0" class="css-radio" checked="checked" /><label
                                        for="radioG1" class="css-label1">信用卡</label>
                                <input type="radio" name="order_payment[]" value="1" class="css-radio"/><label
                                        for="radioG2" class="css-label1">ATM轉帳</label>
                                <input type="radio" name="order_payment[]" value="2" class="css-radio"/><label
                                        for="radioG3" class="css-label1">貨到付款</label>
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
                                <input type="radio" name="order_invoice[]" value="0" class="css-radio"
                                       checked="checked"/><label for="radioG5" class="css-label1">二聯式發票</label>
                                <input type="radio" name="order_invoice[]" value="1" class="css-radio"/><label
                                        for="radioG6" class="css-label1">三聯式發票</label>
                            </div>

                            <div class="col-md-6">
                                <label class='control-label'>統一編號</label>
                                <input type="text" name="order_uni_number" class="form-control">
                            </div>
                            {{--<div class="col-md-6">--}}
                            {{--<label class='control-label'><span class="note">*</span>發票抬頭</label>--}}
                            {{--<input type="text" class="form-control">--}}
                            {{--</div>--}}
                        </div>


                    </div>
                </div>

                <!--發票資訊_結束-->

                <div class="btn_submit">
                    <ul>
                        <li>
                            <a href="#" class="btn btn-danger-outline btn-black">繼續購物
                                <i class="fa fa-shopping-cart mr5" aria-hidden="true"></i></a>
                        </li>
                        <li>
                            <button onclick="f_submit()" class="btn btn-success" aria-hidden="true">下一步</button>
                        </li>
                    </ul>
                </div>
            </div>
        </form>
    </div>
    <script>
        function Accounting(unit_price, item_id, cb) {
            var price_before = $("#account_" + item_id).text();
            var count = $('#itemsCount_' + item_id).val();
            var price_after = parseFloat(unit_price) * parseFloat(count);
            $("#account_" + item_id).text(price_after);

            cb(parseFloat(price_after) - parseFloat(price_before));
        }

        function cb_fun(account_differ) {
            var total_before = $('#TotalPrice').text();
            var total_after = parseFloat(total_before) + parseFloat(account_differ);
            $('#TotalPrice').text(total_after);
            $('#order_money').val(total_after);
        }

        function destroy(id) {
            $.ajax({
                method: 'post',
                url: "{{url('cart/')}}/" + id,
                data: {'_token': '{{csrf_token()}}', '_method': 'delete'},
                success: function (response) {
                    window.location.reload();
                }
            });
        }

        function set_address() {
            var order_address = $('#postal_code').val() +
                $('#county').val() +
                $('#region').val() +
                $('#street').val();
            $('#order_address').val(order_address);
        }

        function f_submit() {
            set_address();
            $('#order_form').submit();
        }

        $('#step1').addClass('st1 select');
    </script>
@endsection
