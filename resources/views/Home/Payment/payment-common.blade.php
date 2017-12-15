@extends('Home.Article.Layouts.home')

@section('info')
    <title>{{\Illuminate\Support\Facades\Config::get('web.web_title')}} | 購物車</title>
    <meta name="description" content="{{\Illuminate\Support\Facades\Config::get('web.web_description')}}">
    <meta name="keywords" content="{{\Illuminate\Support\Facades\Config::get('web.web_keywords')}}">
@endsection

@section('content')
    <!--內容外框_開始-->
    <div id="body_content">
        <form class="form_style4">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <ul id="pay-step">
                            <li id="step1" class="st1">
                                <div class="step-row">
                                    <div class="st-number">1</div>
                                    <div class="st-info"><span class="m-step-number">1：購物車</span><span
                                                class="pc-step-number">購物車</span>確認購物清單及價格
                                    </div>
                                </div>
                            </li>

                            <li id="step2" class="st2">
                                <div class="step-row">
                                    <div class="st-number">2</div>
                                    <div class="st-info"><span class="m-step-number">2：最後確認</span><span
                                                class="pc-step-number">最後確認</span>最後確認訂單內容
                                    </div>
                                </div>
                            </li>
                            <li class="st3">
                                <div class="step-row">
                                    <div class="st-number">3</div>
                                    <div class="st-info"><span class="m-step-number">3：送出訂單</span><span
                                                class="pc-step-number">送出訂單</span>檢查訂單資訊
                                    </div>
                                </div>
                            </li>
                            <li class="st4">
                                <div class="step-row">
                                    <div class="st-number">4</div>
                                    <div class="st-info"><span class="m-step-number">4：完成訂購</span><span
                                                class="pc-step-number">完成訂購</span>顯示繳款方式
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                @yield('payment_content')

            </div>
        </form>
    </div>
    <!--內容外框_結束-->
@endsection
