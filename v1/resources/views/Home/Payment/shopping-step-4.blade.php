@extends('Home.Payment.payment-common')

@section('payment_content')

    <div class="row">
        <div class="col-md-12">
            <!--完成訂購_開始-->
            <div class="panel panel-bg-img">
                <div class="panel-body">

                    <!--錯誤訊息_開始-->
                    <div class="row">
                        <div class="col-md-12 pay-info">
                            <div class="pay-error"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>付款失敗</div>
                            <p>請到【會員中心】>【歷史訂單】重新付款。</p>
                        </div>

                    </div>
                    <!--錯誤訊息_結束-->

                    <!--信用卡訊息_開始-->
                    <div class="row">
                        <div class="col-md-12 pay-info">
                            <div class="pay-success"><i class="fa fa-check-circle" aria-hidden="true"></i>完成訂購</div>
                            <p>您可至【會員中心】>【歷史訂單】中，檢視歷史紀錄。</p>
                        </div>

                    </div>
                    <!--信用卡訊息_結束-->

                    <!--atm訊息_開始-->
                    <div class="row">
                        <div class="col-md-12 pay-info">

                            <div class="pay-success"><i class="fa fa-check-circle" aria-hidden="true"></i>完成訂購</div>
                            <p>您選擇的付款方式為【ATM自動提款機轉帳】，<br>可使用ATM 、臨櫃、網路銀行、電話語音的轉帳方式來進行繳費。</p>
                            <h4>繳費資訊</h4>
                            <ul>
                                <li>繳費時間：2013/10/10~2013/10/17</li>
                                <li>繳費金額：NT$1,500元</li>
                                <li>銀行：第一銀行(代碼007)</li>
                                <li>匯款帳號：20122136521</li>
                            </ul>
                            <div class="hr-line-dashed"></div>
                            <p>您可至【會員中心】>【歷史訂單】中，檢視歷史紀錄。</p>
                        </div>

                    </div>
                    <!--atm訊息_結束-->
                </div>
            </div>
            <!--完成訂購_結束-->

            <div class="btn_submit">
                <ul>
                    <li><a href="#" class="btn btn-success">查詢歷史訂單</a></li>
                    <li><a href="{{url('member-profile')}}" class="btn btn-success">前往會員中心</a></li>
                </ul>
            </div>
        </div>
    </div>
    <script>
        $('#step3').addClass('st3 select');
    </script>
@endsection
