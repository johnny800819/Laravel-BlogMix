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
                        <li>購物車中的商品只能暫放90天，可暫存的商品品項至多為20項。</li>
                        <li>消費總金額滿xxx元,免運費。</li>
                        <li>更多購物說明,請點選右側說明連結前往。(<a href="#">詳細說明</a>)</li>
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
                    <tr>
                        <td class="text-center"><img src="images/demo-img-9.jpg" width="560" height="560"
                                                     class="shopping-car"></td>
                        <td class="text-left">【hilltop山頂鳥】BASIL 黑藍自在旅行單車馬鞍包T34XM6</td>
                        <td class="text-center">xxx</td>
                        <td class="text-center">xxx</td>
                        <td class="text-right">NT$5,000元</td>
                        <td class="text-center">2</td>
                        <td class="text-right">$1,600元</td>
                    </tr>
                    <tr>
                        <td class="text-center"><img src="images/demo-img-9.jpg" width="560" height="560"
                                                     class="shopping-car"></td>
                        <td class="text-left">【hilltop山頂鳥】BASIL 黑藍自在旅行單車馬鞍包T34XM6</td>
                        <td class="text-center">xxx</td>
                        <td class="text-center">xxx</td>
                        <td class="text-right">NT$5,000元</td>
                        <td class="text-center">2</td>
                        <td class="text-right">$1,600元</td>
                    </tr>
                    <tr>
                        <td class="text-center"><img src="images/demo-img-9.jpg" width="560" height="560"
                                                     class="shopping-car"></td>
                        <td class="text-left">【hilltop山頂鳥】BASIL 黑藍自在旅行單車馬鞍包T34XM6</td>
                        <td class="text-center">xxx</td>
                        <td class="text-center">xxx</td>
                        <td class="text-right">NT$5,000元</td>
                        <td class="text-center">2</td>
                        <td class="text-right">$1,600元</td>
                    </tr>
                    <tr>
                        <td class="text-center"><img src="images/demo-img-9.jpg" width="560" height="560"
                                                     class="shopping-car"></td>
                        <td class="text-left">【hilltop山頂鳥】BASIL 黑藍自在旅行單車馬鞍包T34XM6</td>
                        <td class="text-center">xxx</td>
                        <td class="text-center">xxx</td>
                        <td class="text-right">NT$5,000元</td>
                        <td class="text-center">2</td>
                        <td class="text-right">$1,600元</td>
                    </tr>
                </table>
                <!--表格_結束-->

                <div class="row">

                    <div class="col-md-9 col-sm-9 col-xs-9 confirm-title">小計：</div>
                    <div class="col-md-2 col-sm-2 col-xs-2 confirm-content">$2,000 元</div>
                    <div class="col-md-9 col-sm-9 col-xs-9 confirm-title">運費：</div>
                    <div class="col-md-2 col-sm-2 col-xs-2 confirm-content">$100 元</div>
                    <div class="col-md-9 col-sm-9 col-xs-9 confirm-title note-red">消費總金額：</div>
                    <div class="col-md-2 col-sm-2 col-xs-2 confirm-content note-red">$2,100 元</div>

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
                        <p class="form-control-static">email@example.com</p>
                    </div>
                    <div class="col-md-6 mb25">
                        <label class='control-label'>市內電話</label>
                        <p class="form-control-static">email@example.com</p>
                    </div>
                    <div class="col-md-6 mb25">
                        <label class='control-label'>行動電話</label>
                        <p class="form-control-static">email@example.com</p>
                    </div>

                    <div class="col-md-12">
                        <label class='control-label'>地址</label>
                        <p class="form-control-static">234台北市信義區忠孝東路四段xx號2樓</p>
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
                        <p class="form-control-static">信用卡</p>
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
                        <p class="form-control-static">三聯式統一發票</p>
                    </div>

                    <div class="col-md-6">
                        <label class='control-label'>統一編號</label>
                        <p class="form-control-static">5324-5069</p>
                    </div>
                    <div class="col-md-6">
                        <label class='control-label'>發票抬頭</label>
                        <p class="form-control-static">王小明股份有限公司</p>
                    </div>


                </div>


            </div>
        </div>

        <!--發票資訊_結束-->

        <div class="btn_submit">
            <ul>
                <li><a href="{{url('cart')}}" class="btn btn-default btn-black"><i
                                class="fa fa-angle-left mr5"></i>上一步</a></li>
                <li><a href="#" class="btn btn-success">確定送出<i class="fa fa-angle-right ml5"></i></a></li>
            </ul>
        </div>
    </div>
</div>
<script>
    $('#step2').addClass('st2 select');
</script>
@endsection
