@extends('Home.Article.Layouts.home')

@section('info')
    <title>{{\Illuminate\Support\Facades\Config::get('web.web_title')}} | 文章列表</title>
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
                        <li><a href="#">好文章</a></li>
                        <li class="active">最新文章</li>
                    </ol>


                </div>
            </div>
            <!--breadcrumb_結束-->

            <div class="left-row-330">
                <div class="col-md-12 pl0">
                    <div class="panel panel-bg-img">
                        <div class="panel-heading">
                            <h3 class="panel-title">搜尋文章,共<span class="note">{{$count}}</span>筆</h3>
                        </div>
                        <div class="panel-body min-height-300">
                            <div class="row">
                                @foreach($article as $art)
                                <!--文章_開始-->
                                <div class="col-md-12 col-sm-4">
                                    <div class="row">
                                        <div class="article-list">
                                            <div class="col-md-3 col-sm-12 col-xs-12">
                                                <a href="{{url('new/'.$art->art_id)}}">
                                                    <img src="{{url($art->art_thumb)}}" width="626" height="529" class="img-responsive">
                                                </a>
                                            </div>

                                            <div class="col-md-9 col-sm-12 col-xs-12 p-change-l15">
                                                <h3><a href="{{url('new/'.$art->art_id)}}">{{$art->art_title}}</a></h3>
                                                <p>內容稍微顯示/或放短敘述</p>
                                                <span class="mr5"><i class="fa fa-clock-o mr5" aria-hidden="true"></i>{{$art->art_time}}</span>
                                                <span><i class="fa fa-eye mr5" aria-hidden="true"></i>{{$art->art_view}}人</span>
                                            </div>
                                            <div>
                                                <a href="javascript:
                                                $.ajax({
                                                type: 'POST',
                                                url: '{{url('cart/'.$art->art_id)}}',
                                                data: {
                                                    _token:'{{csrf_token()}}',
                                                    _method:'patch'
                                                },
                                                success: function(response){
                                                    alert(response);
                                                }
                                                });
                                                "><button type="button" class="btn btn-warning">加入購物車</button></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--文章_結束-->
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!--分頁_開始（使用laravel內建）簡易型-->
                    {!! $article->links() !!}
                    <!--分頁_結束-->

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
                                <a href="#"><img src="images/demo-img-9.jpg" width="560" height="560" class="img-responsive"></a>
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
                                <a href="#"><img src="images/demo-img-9.jpg" width="560" height="560" class="img-responsive"></a>
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
                                <a href="#"><img src="images/demo-img-9.jpg" width="560" height="560" class="img-responsive"></a>
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
                                <a href="#"><img src="images/demo-img-9.jpg" width="560" height="560" class="img-responsive"></a>
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
                                <a href="#"><img src="images/demo-img-9.jpg" width="560" height="560" class="img-responsive"></a>
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
                                <a href="#"><img src="images/demo-img-9.jpg" width="560" height="560" class="img-responsive"></a>
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


    

    

