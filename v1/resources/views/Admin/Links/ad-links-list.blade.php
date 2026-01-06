@extends('Admin/Layouts/layout')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>好文章廣告</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/admin/main-page')}}">首頁</a>
                </li>
                <li class="active">
                    <strong>好文章廣告</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2">

        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>好文章廣告</h5>
                </div>
                <div class="ibox-content">
                <div class="mb10"><button type="button" class="btn btn-primary" onclick="window.location.href='{{url('admin/ad-links/create')}}'"><i class="fa fa-plus mr5" aria-hidden="true"></i>新增廣告</button></div>
                <table class="table table-striped table-bordered table-hover dataTables-example table-style-1" >
                <thead>
                <tr>
                    <th class="text-center" width="5%">編號</th>
                    <th class="text-center" width="8%">區域</th>
                    <th class="text-left" width="21%">廣告鍊結</th>
                    <th class="text-left" width="21%">廣告標題</th>
                    <th class="text-left" width="35%">廣告短述</th>
                    <th class="text-center" width="10%">功能</th>

                    {{--<th class="text-center" width="10%">大圖</th>--}}
                    {{--<th class="text-center" width="10%">小圖</th>--}}
                    {{--<th class="text-center" width="5%">狀態</th>--}}
                    {{--<th class="text-center" width="8%">瀏覽人數</th>--}}
                    {{--<th class="text-center" width="10%">建立時間</th>--}}
                </tr>
                </thead>
                <tbody>
                @foreach($data as $v)
                <tr>
                    <td class="text-center">{{$v->link_id}}</td>
                    <td class="text-center">{{$v->link_page}}</td>
                    <td class="text-left"><a target="_blank" href="{{$v->link_url}}">{{$v->link_url}}</a></td>
                    <td class="text-left">{{$v->link_title}}</td>
                    <td class="text-left">{{$v->link_description}}</td>
                    <td class="text-center">
                        <a href="{{url('admin/ad-links/'.$v->link_id.'/edit')}}" class="mr5">編輯</a>
                        <a href="javascript::" onclick="delLinks('{{$v->link_id}}')">刪除</a>
                    </td>
                    {{--<td class="text-center"><img src="img/banner-1.png" width="1170" height="590" class="img-responsive-w120"></td>--}}
                    {{--<td class="text-center"><img src="img/demo-img-9.jpg" width="560" height="560" class="img-responsive-w120"></td>--}}
                    {{--<td class="text-center"><span class="text-danger">隱藏</span></td>--}}
                    {{--<td class="text-center">10</td>--}}
                    {{--<td class="text-center">2016-07-27 15:15:21</td>--}}
                </tr>
                @endforeach
                </tbody>
                </table>

                </div>
            </div>
        </div>
        </div>
    </div>
    <div class="footer">
        <div>
            <strong>Copyright</strong> © Protype All Rights Reserved
        </div>
    </div>

    <script>
        //DELETE類別
        function delLinks(link_id) {
            var c = confirm("確認要刪除此項目嗎？");
            if(c){
                $.post("{{url('admin/ad-links')}}/"+link_id,
                    {
                        '_method':'DELETE',
                        '_token':'{{csrf_token()}}',
                    },
                    function(data){
                        if(data.result){
                            window.location.reload();
                        };
                    });
            }
        }
    </script>
@endsection

