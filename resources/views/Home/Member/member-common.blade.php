@extends('Home.Article.Layouts.home')

@section('info')
    <title>{{\Illuminate\Support\Facades\Config::get('web.web_title')}} | 會員中心</title>
    <meta name="description" content="{{\Illuminate\Support\Facades\Config::get('web.web_description')}}">
    <meta name="keywords" content="{{\Illuminate\Support\Facades\Config::get('web.web_keywords')}}">
@endsection

@section('content')

    <!--內容外框_開始-->
    <div id="body_content">

        <div class="container">

            <!--breadcrumb_開始-->
            @yield('breadcrumb')
            <!--breadcrumb_結束-->

            <!-- 固定欄位寬度為300px -->
            <div class="col-fixed">

                <div class="panel panel-bg-img">
                    <div class="panel-heading">
                        <h3 class="panel-title">會員中心</h3>
                    </div>
                    <div class="panel-body">
                        <div class="mc-nav">
                            <ul>
                                <li><a href="{{url('/member-profile')}}">會員資料修改</a></li>
                                <li><a href="{{url('/member-add-service')}}">聯絡客服</a></li>
                                <li><a href="{{url('/member-re-service')}}">最新客服回覆</a></li>
                                <li><a href="member-order-list.html">歷史訂單查詢</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
            <!-- 固定欄位寬度為300px end-->

            @yield('member_content')
        </div>
    </div>
    <!--內容外框_結束-->
@endsection
