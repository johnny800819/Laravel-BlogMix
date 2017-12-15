@extends('Admin/Layouts/layout')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>好文章列表</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/admin/main-page')}}">首頁</a>
                </li>
                <li class="active">
                    <strong>好文章列表</strong>
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
                    <h5>好文章列表</h5>
                </div>
                <div class="ibox-content">
                <div class="mb10">
                    <button type="button" class="btn btn-primary" onclick="window.location.href='{{url('admin/article/create')}}'">
                        <i class="fa fa-plus mr5" aria-hidden="true"></i>新增好文章
                    </button>
                </div>
                <table class="table table-striped table-bordered table-hover dataTables-example table-style-1" >
                <thead>
                <tr>
                    <th class="text-center" width="5%">編號</th>
                    <th class="text-center" width="10%">主類別</th>
                    <th class="text-center" width="10%">次類別</th>
                    <th class="text-center" width="40%">文章標題</th>
                    {{--<th class="text-center" width="10%">是否上架</th>--}}
                    <th class="text-center" width="10%">瀏覽量</th>
                    <th class="text-center" width="15%">發布時間</th>
                    {{--<th class="text-center" width="10%">最後更新時間</th>--}}
                    <th class="text-center" width="10%">功能</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data as $v)
                    <tr>
                        {{--這裡是故意嘗試＄v的使用方法（$v->art_id and $v['art_id']）--}}
                        <td class="text-center">{{$v['art_id']}}</td>
                        <td class="text-center">{{$v['cate_name']}}</td>
                        <td class="text-center">{{$v['cate_name_sub']}}</td>
                        <td class="text-left">{{$v['art_title']}}</td>
                        {{--<td class="text-center"><span class="text-danger">否</span></td>--}}
                        <td class="text-center">{{$v['art_view']}}</td>
                        <td class="text-center">{{$v['art_time']}}</td>
                        {{--<td class="text-center">2016-07-27 15:15:21</td>--}}
                        <td class="text-center">
                            <a href="{{url('admin/article/'.$v['art_id'].'/edit')}}" class="mr5">編輯</a>
                            <a href="javascript:" onclick="delArticle('{{$v['art_id']}}')">刪除</a>
                        </td>
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
        function delArticle(art_id) {
            var c = confirm("確認要刪除此項目嗎？");
            if(c){
                $.post("{{url('admin/article/')}}/"+art_id,
                {
                    '_method':'DELETE',
                    '_token':'{{csrf_token()}}',
                    'cate_id': art_id,
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


