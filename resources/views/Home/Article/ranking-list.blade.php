@extends('Home.Article.Layouts.home')

@section('info')
    <title>{{\Illuminate\Support\Facades\Config::get('web.web_title')}} | 聊天室</title>
    <meta name="description" content="{{\Illuminate\Support\Facades\Config::get('web.web_description')}}">
    <meta name="keywords" content="{{\Illuminate\Support\Facades\Config::get('web.web_keywords')}}">
@endsection

@section('content')
    <!--內容外框_開始-->
    <div id="body_content">
        <!--排行區塊_開始-->
        <div class="ranking-article-row">

            <div class="container">

                <h2>熱門排行</h2>

                <div class="row mt20">
                    <div class="col-md-12 text-center mb20" style="display:none;">
                        <button type="button" class="btn btn-ranking active mr5">今日熱門</button>
                        <button type="button" class="btn btn-ranking mr5">本週熱門</button>
                        <button type="button" class="btn btn-ranking">觀看總數</button>
                    </div>
                </div>

                <div class="row mt20">
                    @foreach($data as $d)
                    <!--排行_開始-->
                    <div class="col-md-1-5 col-sm-1-5 col-xs-1-5">
                        <div class="ranking-article">
                            <div class="ranking-number">1</div>
                            <a href="{{url('new/'.$d->art_id)}}"><img src="{{url($d->art_thumb)}}" width="216" height="216" class="img-responsive"></a>
                            <div class="ranking-info">
                                <h2><a href="#">{{$d->art_title}}</a></h2>
                                <div class="post-date"><i class="fa fa-clock-o mr5" aria-hidden="true"></i>{{$d->art_time}}
                                </div>
                                <ul class="ranking">
                                    {{--<li>今日觀看：<span>2268人</span></li>--}}
                                    {{--<li>本週觀看：<span>2268人</span></li>--}}
                                    <li>觀看總數：<span>{{$d->art_view}}人</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!--排行_結束-->
                    @endforeach
                </div>
            </div>
        </div>
        <!--排行區塊_結束-->
    </div>
    <!--內容外框_結束-->
@endsection