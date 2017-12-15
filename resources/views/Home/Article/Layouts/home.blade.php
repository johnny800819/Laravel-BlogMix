{{--{{session(['status' => ''])}}--}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    @yield('info')

    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{asset('org/Home/Article/images/apple-touch-icon-144-precomposed.png')}}">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{asset('org/Home/Article/images/apple-touch-icon-114-precomposed.png')}}">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{asset('org/Home/Article/images/apple-touch-icon-72-precomposed.png')}}">
    <link rel="apple-touch-icon-precomposed" sizes="57x57" href="{{asset('org/Home/Article/images/apple-touch-icon-57-precomposed.png')}}">
    <link rel="shortcut icon" href="{{asset('org/Home/Article/images/favicon.png')}}">

    <link href="{{asset('org/Home/Article/css/bootstrap.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('org/Home/Article/css/style-for-pad.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('org/Home/Article/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('org/FlexSlider/flexslider.css')}}" rel="stylesheet" type="text/css" />

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="{{asset('org/Home/Article/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('org/FlexSlider/jquery.flexslider.js')}}"></script>

    <script>
    $(document).ready(function(){
        $('.flexslider').flexslider({
            animation: "slide",
            animationLoop: true,
            itemWidth: 210,
            itemMargin: 5,
            minItems: 2,
            maxItems: 4
        });
    });

    /***** FB登入API *****/
    function fb_get_api(response){
        FB.api(
            '/me',
            'GET',
            {"fields":"name,email"}, //response內含大量json資料,可透過這裡篩選
            function(response) { //the callback function to handle the response
                $.ajax({
                    type: "POST",
                    url: '{{url('register')}}',
                    data: {
                        email:response.email,
                        resource:'facebook',
                        _token:'{{csrf_token()}}'
                    },
                    success: function(response){
                        alert(response);
                        window.location.reload();
                    }
                });
            }
        );
    }
    function statusChangeCallback(response) {
        // 這個 response object is returned with a status field 來讓app知道現在這個登入者的資訊
        if (response.status === 'connected') { // Logged into your app and Facebook(authorized).
            fb_get_api(response);
        } else {
            FB.login(function(response) {
                if (response.authResponse) { // 登入且同意本網站要求的特殊授權
                    fb_get_api(response);
                }
            }, {scope: 'email'}); //要求特別存取Email (Facebook Permissions)
        }
    }
    // 按下登入fb按鈕,  檢測登入的狀態
    function checkLoginState() {
        FB.getLoginStatus(function(response) { //the callback function to handle the response
            statusChangeCallback(response);
        });
    }

    //fb（非同步）初始化參數
    window.fbAsyncInit = function() {
        FB.init({
            appId      : '1981364918741814',
            cookie     : true,
            xfbml      : true,
            version    : 'v2.11'
        });
    };

    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/zh_TW/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
    </script>

    <style>
        .flex-direction-nav a {
            display: block;
            width: 40px;
            height: 40px;
            margin: -20px 0 0;
            position: absolute;
            top: 50%;
            z-index: 10;
            overflow: hidden;
            opacity: 0;
            cursor: pointer;
            color: rgba(0, 0, 0, 0.8);
            text-shadow: none;
            -webkit-transition: all 0.3s ease-in-out;
            -moz-transition: all 0.3s ease-in-out;
            -ms-transition: all 0.3s ease-in-out;
            -o-transition: all 0.3s ease-in-out;
            transition: all 0.3s ease-in-out;
            color: #fff;
            background-color: #000;
            border-radius: 50%;
            text-align: center;
        }
        .flex-direction-nav a:before {
            font-family: "flexslider-icon";
            font-size: 20px;
            display: inline-block;
            content: '\f001';
            color: #FFF;
            text-shadow: 1px 1px 0 rgba(255, 255, 255, 0.3);
            line-height: 40px;
        }
        .flex-direction-nav .flex-next {
            right: -50px;
            text-align: center;
        }
    </style>
</head>

{!! \Illuminate\Support\Facades\Config::get('web.web_count') !!}

<body role="document">
<!--網站外框_開始-->
<div id="wrapper">

    <div class="topbar-fluid">
        <div class="container">
            <div class="row">

                <div class="col-md-12 col-sm-12 text-right">

                    <form class="form-inline">
                        <div class="form-group mr5">
                            <input type="text" class="form-control input-sm" id="" placeholder="請輸入關鍵字搜尋">
                        </div>
                        <button type="button" class="btn btn-black btn-sm"><i class="fa fa-search" aria-hidden="true"></i></button>
                    </form>

                </div>

            </div>
        </div>
    </div>

    <!--表頭_開始-->
    <header>
        <!--選單_開始-->
        <div class="menu-hor-style5">
            @if(session('status') == 'login')
                <div style="text-align: right">Hello {{\Illuminate\Support\Facades\Auth::user()->email}}</div>
            @endif
            <ul style="margin-left: 140px; position:relative;">
                <li class="pt25"><a href="{{url('/list')}}"><img src="{{asset('org/Home/Article/images/icon1.png')}}" width="47" height="46"><span>全部文章</span></a></li>
                <li class="pt25"><a href="product-index.html"><img src="{{asset('org/Home/Article/images/icon2.png')}}" width="47" height="46"><span>商品功能（尚未完成）</span></a></li>
                <li class="pt25"><a href="{{url('/')}}"><img src="{{asset('org/Home/Article/images/icon3.png')}}" width="47" height="46"><span>返回首頁</span></a></li>

                @if(session('status') != 'login')
                <!--登出_開始-->

                <li class="pt25 fb">
                    <a href="javascript:checkLoginState()">
                        <img src="{{asset('org/Home/Article/images/icon4.png')}}" width="47" height="46"><span>FB登入</span>
                    </a>
                </li>
                <li class="pt25"><a href="{{url('/login')}}"><img src="{{asset('org/Home/Article/images/icon5.png')}}" width="47" height="46"><span>登入/註冊</span></a></li>
                <li class="pt25"><a href="ranking-list.html"><img src="{{asset('org/Home/Article/images/icon6.png')}}" width="47" height="46"><span>熱門文章</span></a></li>

                <!--登出_結束-->
                @else
                <!--登入_開始-->

                <li class="pt25 shopping"><a href="{{url('/cart')}}"><span class="number">{{$cart_item_count}}</span><img src="{{asset('org/Home/Article/images/icon7.png')}}" width="47" height="46"><span>購物車</span></a></li>
                <li class="pt25"><a href="{{url('/member-profile')}}"><img src="{{asset('org/Home/Article/images/icon8.png')}}" width="47" height="46"><span>會員中心</span></a></li>
                <li class="pt25"><a href="{{url('/logout')}}"><img src="{{asset('org/Home/Article/images/icon9.png')}}" width="47" height="46"><span>登出</span></a></li>

                <!--登入_結束-->
                @endif
            </ul>
        </div>
        <!--選單_結束-->

    </header>
    <!--表頭_結束-->

    @yield('content')

    <!--置底_開始-->
    <footer>
        <!--banner_開始-->
        <div class="footer-banner-row-1">
            <div class="container">
                <div class="flexslider carousel">
                    <ul class="slides">
                        @foreach($ad as $k=>$v)
                        <li>
                            <a title="{{$v->link_url}}" href="{{$v->link_url}}" target="_blank">
                            <img src="{{asset('org/Home/Article/images/demo-img-1.gif')}}" />
                            {{$v->link_id}}/{{$v->link_title}}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <!--banner_結束-->

        <!--site-map_開始-->
        <div class="footer-site-map-row">
            <div class="container">
                <div class="row">

                    <div class="col-md-4 col-sm-4 col-xs-4" >
                        {{--<img src="{{asset('org/Home/Article/images/footer-logo.png')}}" width="180" height="105" class="img-responsive">--}}
                        <p>管理者服務時間: 週一至週五08:30~18:00<br>管理者服務電話: 02-XXXX-XXXX＃XXXX 呂先生<br>E-mail : johnny800819@gmail.com</p>
                    </div>

                    <div class="col-md-8 col-sm-8 col-xs-8">
                        <div class="row">

                            <div class="col-md-3 col-sm-3 col-xs-3">
                                <h4>關於NICE GREEN</h4>
                                <ul class="footer-link">
                                    <li><a href="#">品牌故事</a></li>
                                    <li><a href="#">facebook粉絲團</a></li>
                                    <li><a href="#">人才招募</a></li>
                                    <li><a href="#">聯絡我們</a></li>
                                </ul>
                            </div>
                            <div class="col-md-2 col-sm-3 col-xs-3">
                                <h4>會員服務</h4>
                                <ul class="footer-link">
                                    <li><a href="#">會員條款</a></li>
                                    <li><a href="#">訂單查詢</a></li>
                                    <li><a href="#">加入會員</a></li>
                                    <li><a href="#">會員登入</a></li>
                                </ul>
                            </div>
                            <div class="col-md-2 col-sm-3 col-xs-3">
                                <h4>客服中心</h4>
                                <ul class="footer-link">
                                    <li><a href="#">購物流程說明</a></li>
                                    <li><a href="#">付款方式</a></li>
                                    <li><a href="#">運費說明</a></li>
                                    <li><a href="#">FAQ</a></li>
                                </ul>
                            </div>
                            <div class="col-md-2 col-sm-3 col-xs-3">
                                <h4>退換貨說明</h4>
                                <ul class="footer-link">
                                    <li><a href="#">退貨說明</a></li>
                                    <li><a href="#">換貨說明</a></li>
                                </ul>
                            </div>
                            <div class="col-md-2 col-sm-3 col-xs-3">
                                <h4>其它相關服務</h4>
                                <ul class="footer-link">
                                    <li><a href="#">合作提案</a></li>
                                    <li><a href="#">換貨說明</a></li>
                                </ul>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!--site-map_結束-->

        <!--copyright_開始-->
        <div class="footer-copyright-row">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <p>{{\Illuminate\Support\Facades\Config::get('web.web_copyright')}}</p>
                    </div>
                </div>
            </div>
        </div>
        <!--copyright_結束-->

    </footer>
    <!--置底_結束-->

</div>
<!--網站外框_結束-->

</body>
</html>
