@extends('Home.Member.member-common')

@section('breadcrumb')
    <!--breadcrumb_開始-->
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="#">首頁</a></li>
                <li><a href="#">會員中心</a></li>
                <li class="active">最新客服回覆</li>
            </ol>
        </div>
    </div>
    <!--breadcrumb_結束-->
@endsection

@section('member_content')

    <div class="right-row">
        <div class="col-md-12">
            <div class="panel panel-bg-img min-height-300">
                <div class="panel-heading">
                    <h3 class="panel-title">最新客服回覆</h3>
                </div>
                <div class="panel-body">
                    @foreach($data as $d)
                    <!--問題_開始-->
                    <div class="faq-row">

                        <div class="question-row">
                            <div class="faq-time"><i class="fa fa-clock-o mr5" aria-hidden="true"></i>發問時間:{{$d->slist_time}}
                            </div>
                            <h3>{{$d->slist_theme}}</h3>
                        </div>
                        @if($d->slist_status == '1')
                        <div class="no-ans-row">
                            <i class="fa fa-exclamation-triangle mr5" aria-hidden="true"></i>感謝您的問題留言,客服人員將於24小時內回覆
                        </div>
                        @elseif($d->slist_status == '2')
                        <div class="ans-row">
                            <div class="faq-time"><i class="fa fa-reply mr5" aria-hidden="true"></i>回覆時間:{{$d->response_time}}
                            </div>
                            <p>{{$d->slist_response}}</p>
                        </div>
                        @endif
                    </div>
                    <!--問題_結束-->
                    @endforeach

                    <!--問題_開始-->
                    {{--<div class="faq-row">--}}

                        {{--<div class="question-row">--}}
                            {{--<div class="faq-time"><i class="fa fa-clock-o mr5" aria-hidden="true"></i>發問時間:2015/02/02--}}
                            {{--</div>--}}
                            {{--<h3>購買了義杯醬- 手作天然食材蒜香頂級奶油醬還沒收到</h3>--}}
                        {{--</div>--}}
                        {{--<div class="ans-row">--}}
                            {{--<div class="faq-time"><i class="fa fa-reply mr5" aria-hidden="true"></i>回覆時間:2015/02/02--}}
                            {{--</div>--}}
                            {{--<p>美國馬里蘭州巴爾的摩（Baltimore）一棟房屋日前傳出火警，一名8個月大的女嬰以及狗狗都被困在其中，消防員抵達現場救援，才發現</p>--}}
                        {{--</div>--}}

                    {{--</div>--}}
                    <!--問題_結束-->
                </div>
            </div>

            <!--分頁_開始-->
            <div class="pagination-row">
                <nav aria-label="Page navigation">
                    {!! $data->links() !!}
                </nav>
            </div>
            <!--分頁_結束-->
        </div>
    </div>
@endsection
