@extends('Home.Article.Layouts.home')

@section('info')
    <title>{{\Illuminate\Support\Facades\Config::get('web.web_title')}} | 文章內容</title>
    <meta name="description" content="{{\Illuminate\Support\Facades\Config::get('web.web_description')}}">
    <meta name="keywords" content="{{\Illuminate\Support\Facades\Config::get('web.web_keywords')}}">
@endsection

@section('content')
    <!--內容外框_開始-->
    <div id="body_content">
        <div class="container">

            <!--breadcrumb_開始-->
            <div class="row">
                <div class="col-md-12">

                    <ol class="breadcrumb">
                        <li><a href="{{url('/')}}"><i class="fa fa-home mr5" aria-hidden="true"></i>首頁</a></li>
                        <li><a href="{{url('/list')}}">好文章</a></li>
                        <li class="active">最新文章</li>
                    </ol>


                </div>
            </div>
            <!--breadcrumb_結束-->

            <div class="left-row-330">
                <div class="col-md-12 pl0">
                    <div class="panel panel-bg-img">
                        <div class="panel-body min-height-300">
                            <div class="row">

                                <!--文章_開始-->
                                <div class="col-md-12 col-sm-12">

                                    <div class="article-detail">

                                        <h2>{{$data->art_title}}</h2>
                                        <div class="row">

                                            <div class="col-sm-4"><span class="post-date"><i class="fa fa-clock-o mr5"></i>發布日期：{{$data->art_time}}</span></div>
                                            {{--<div class="col-sm-8 text-right"><a href="#" class="mr5">fb_讚</a><a href="#">fb_分享</a></div>--}}

                                        </div>

                                        <div class="row">
                                            <!--統計數值_開始-->
                                            <div class="col-sm-12">

                                                <div id="literaryworks_counter">
                                                    <ul>
                                                        <li><span>{{$data->art_view}}</span><strong>看過</strong></li>
                                                        <li><span>{{ 0 }}</span><strong>本週觀看</strong></li>
                                                        <li><span>{{ 0 }}</span><strong>今觀看</strong></li>
                                                    </ul>
                                                </div>

                                            </div>
                                            <!--統計數值_結束-->


                                        </div>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="tag-row">
                                                    <span><i class="fa fa-tag mr5" aria-hidden="true"></i>標籤</span>
                                                    <a href="#">{{$data->art_tag}}</a>
                                                </div>
                                            </div>
                                        </div>

                                        {!! $data->art_content !!}

                                    </div>

                                </div>
                                <!--文章_結束-->

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 固定欄位寬度為330px -->
            <div class="col-fixed-330">

                <div class="panel panel-bg-img">
                    <div class="panel-heading">
                        <h3 class="panel-title">相關好商品</h3>
                    </div>
                    <div class="panel-body plr0">

                        <!--商品_開始-->
                        <div class="col-md-6 col-sm-3 col-xs-3">
                            <div class="right-product-list">
                                <a href="#"><img src="{{asset('org/Home/Article/images/img1.jpg')}}" width="626" height="529" class="img-responsive"></a>
                                <div class="right-product-list-info">
                                    <h2><a href="#">蔬菜麵系列(萵苣麵/菊苣麵/羽衣甘藍麵/冰萵苣麵/菊苣麵/羽衣甘藍麵/冰花麵)</a></h2>
                                    <div class="price">NT$1,200</div>
                                </div>
                            </div>
                        </div>
                        <!--商品_結束-->

                        <!--商品_開始-->
                        <div class="col-md-6 col-sm-3 col-xs-3">
                            <div class="right-product-list">
                                <a href="#"><img src="images/img1.jpg" width="626" height="529" class="img-responsive"></a>
                                <div class="right-product-list-info">
                                    <h2><a href="#">蔬菜麵系列(萵苣麵/菊苣麵/羽衣甘藍麵/冰萵苣麵/菊苣麵/羽衣甘藍麵/冰花麵)</a></h2>
                                    <div class="price">NT$1,200</div>
                                </div>
                            </div>
                        </div>
                        <!--商品_結束-->

                        <!--商品_開始-->
                        <div class="col-md-6 col-sm-3 col-xs-3">
                            <div class="right-product-list">
                                <a href="#"><img src="images/img1.jpg" width="626" height="529" class="img-responsive"></a>
                                <div class="right-product-list-info">
                                    <h2><a href="#">蔬菜麵系列(萵苣麵/菊苣麵/羽衣甘藍麵/冰萵苣麵/菊苣麵/羽衣甘藍麵/冰花麵)</a></h2>
                                    <div class="price">NT$1,200</div>
                                </div>
                            </div>
                        </div>
                        <!--商品_結束-->

                        <!--商品_開始-->
                        <div class="col-md-6 col-sm-3 col-xs-3">
                            <div class="right-product-list">
                                <a href="#"><img src="images/img1.jpg" width="626" height="529" class="img-responsive"></a>
                                <div class="right-product-list-info">
                                    <h2><a href="#">蔬菜麵系列(萵苣麵/菊苣麵/羽衣甘藍麵/冰萵苣麵/菊苣麵/羽衣甘藍麵/冰花麵)</a></h2>
                                    <div class="price">NT$1,200</div>
                                </div>
                            </div>
                        </div>
                        <!--商品_結束-->

                        <!--商品_開始-->
                        <div class="col-md-6 col-sm-3 col-xs-3">
                            <div class="right-product-list">
                                <a href="#"><img src="images/img1.jpg" width="626" height="529" class="img-responsive"></a>
                                <div class="right-product-list-info">
                                    <h2><a href="#">蔬菜麵系列(萵苣麵/菊苣麵/羽衣甘藍麵/冰萵苣麵/菊苣麵/羽衣甘藍麵/冰花麵)</a></h2>
                                    <div class="price">NT$1,200</div>
                                </div>
                            </div>
                        </div>
                        <!--商品_結束-->

                        <!--商品_開始-->
                        <div class="col-md-6 col-sm-3 col-xs-3">
                            <div class="right-product-list">
                                <a href="#"><img src="images/img1.jpg" width="626" height="529" class="img-responsive"></a>
                                <div class="right-product-list-info">
                                    <h2><a href="#">蔬菜麵系列(萵苣麵/菊苣麵/羽衣甘藍麵/冰萵苣麵/菊苣麵/羽衣甘藍麵/冰花麵)</a></h2>
                                    <div class="price">NT$1,200</div>
                                </div>
                            </div>
                        </div>
                        <!--商品_結束-->

                    </div>
                </div>

            </div>
            <!-- 固定欄位寬度為330px end-->

        </div>

    </div>
    <!--內容外框_結束-->
@endsection

