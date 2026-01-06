@extends('Home.Article.Layouts.home')

@section('info')
    <title>{{\Illuminate\Support\Facades\Config::get('web.web_title')}} | 文章首頁</title>
    <meta name="description" content="{{\Illuminate\Support\Facades\Config::get('web.web_description')}}">
    <meta name="keywords" content="{{\Illuminate\Support\Facades\Config::get('web.web_keywords')}}">
@endsection

@section('content')
    <style type="text/css">
        .carousel-fade .carousel-inner .item {
            opacity: 0;
            -webkit-transition-property: opacity;
            -moz-transition-property: opacity;
            -o-transition-property: opacity;
            transition-property: opacity;
        }
        .carousel-fade .carousel-inner .active {
            opacity: 1;
        }
        .carousel-fade .carousel-inner .active.left,
        .carousel-fade .carousel-inner .active.right {
            left: 0;
            opacity: 0;
            z-index: 1;
        }
        .carousel-fade .carousel-inner .next.left,
        .carousel-fade .carousel-inner .prev.right {
            opacity: 1;
        }
        .carousel-fade .carousel-control {
            z-index: 2;
        }
    </style>

    <!--內容外框_開始-->
    <div id="body_content">

        <!--banner_開始-->
        {{--<div class="container banner-bg">--}}

            {{--<div class="row">--}}

                {{--<div class="col-md-12">--}}

                    {{--<div id="carousel-example-generic-1" class="carousel slide carousel-fade" data-ride="carousel">--}}
                        {{--<!-- Indicators(1070*430) -->--}}

                        {{--<div class="carousel-inner">--}}
                            {{--<div class="item active">--}}
                                {{--<a href="#"><img src="{{asset('org/Home/Article/images/banner-1.png')}}" width="1170" height="520"></a>--}}
                            {{--</div>--}}
                            {{--<div class="item">--}}
                                {{--<a href="#"><img src="{{asset('org/Home/Article/images/banner-1.png')}}" width="1170" height="520"></a>--}}
                            {{--</div>--}}
                            {{--<div class="item">--}}
                                {{--<a href="#"><img src="{{asset('org/Home/Article/images/banner-1.png')}}" width="1170" height="520"></a>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<a class="left carousel-control" href="#carousel-example-generic-1" data-slide="prev">--}}
                            {{--<img src="{{asset('org/Home/Article/images/left-icon.png')}}" width="32" height="47" class="left-icon">--}}

                        {{--</a>--}}
                        {{--<a class="right carousel-control" href="#carousel-example-generic-1" data-slide="next">--}}
                            {{--<img src="{{asset('org/Home/Article/images/right-icon.png')}}" width="32" height="47" class="right-icon">--}}
                        {{--</a>--}}
                    {{--</div>--}}

                {{--</div>--}}

            {{--</div>--}}

        {{--</div>--}}
        <!--banner_結束-->

        <!--排行區塊_開始-->
        <div class="ranking-article-row">
            <div class="container">
                <div class="row mt40">
                    <!--排行_開始-->
                    <?php $cont = 1; ?>
                    @foreach($hot as $h)
                    <div class="col-md-1-5 col-sm-1-5 col-xs-1-5">
                        <div class="ranking-article">
                            <div class="ranking-number">{{$cont++}}</div>
                            <a href="{{url('new/'.$h->art_id)}}">
                                <img src="{{url($h->art_thumb)}}" width="216" height="216" class="img-responsive">
                            </a>
                            <div class="ranking-info">
                            <h2>
                                <a href="{{url('new/'.$h->art_id)}}">{{$h->art_title}}</a>
                            </h2>
                            <ul class="ranking">
                            <li>今日觀看：<span></span></li>
                            <li>本週觀看：<span></span></li>
                            <li>觀看總數：<span>{{$h->art_view}}人</span></li>
                            </ul>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <!--排行_結束-->
                </div>
            </div>
        </div>
        <!--排行區塊_結束-->

        <!--最新文章_開始-->
        <div class="news-article-row">
            <div class="container">
                <h2>最新文章</h2>
                <div class="row mt40">
                    @foreach($new as $n)
                    <!--文章_開始-->
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="row">
                            <div class="news-article">
                                <a href="{{url('new/'.$n->art_id)}}">
                                    <div class="col-xs-5 news-article-img-row">
                                        <img src="{{url($n->art_thumb)}}" width="188" height="188">
                                    </div>

                                    <div class="col-xs-7">
                                        {{$n->art_title}}
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!--文章_結束-->
                    @endforeach
                    <div class="col-md-12 col-sm-12 text-center mt70 mb80">
                        <button type="button" class="btn-br0 btn-black" onclick="window.location.href='{{url('list')}}'">更多文章</button>
                    </div>

                </div>
            </div>
        </div>
        <!--最新文章_結束-->

    </div>
    <!--內容外框_結束-->
@endsection








