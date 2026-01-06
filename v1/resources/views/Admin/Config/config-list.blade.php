@extends('Admin/Layouts/layout')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>網站配置</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/admin/main-page')}}">首頁</a>
                </li>
                <li class="active">
                    <strong>網站配置</strong>
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
                    <h5>網站配置</h5>
                </div>
                <div class="ibox-content">
                <div class="mb10">
                    <button type="button" class="btn btn-primary" onclick="window.location.href='{{url('admin/config/create')}}'">
                        <i class="fa fa-plus mr5" aria-hidden="true"></i>新增
                    </button>
                </div>

                <form action="{{url('admin/configEdit')}}" method="post">
                    {{csrf_field()}}

                    <table class="table table-striped table-bordered table-hover dataTables-example table-style-1" >
                    <thead>
                    <tr>
                        <th class="text-center" width="5%">編號</th>
                        <th class="text-center" width="8%">標題</th>
                        <th class="text-left" width="15%">名稱</th>
                        <th class="text-left" width="25%">短述</th>
                        <th class="text-left" width="35%">內容</th>
                        <th class="text-center" width="12%">功能</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $v)
                    <tr>
                        <td class="text-center">{{$v->conf_id}}</td>
                        <td class="text-center">{{$v->conf_title}}</td>
                        <td class="text-left">{{$v->conf_name}}</td>
                        <td class="text-left">{{$v->conf_tips}}</td>
                        <td class="text-left">{!! $v->_html !!}</td>
                        <td class="text-center">
                            <a href="{{url('admin/config/'.$v->conf_id.'/edit')}}" class="mr5">編輯</a>
                            <a href="javascript:" onclick="delConfig('{{$v->conf_id}}')">刪除</a>
                        </td>
                        <input type="hidden" name="conf_id[]" value="{{$v->conf_id}}">
                    </tr>
                    @endforeach
                    </tbody>
                    </table>
                    <div class="form-group">
                        <div class="">
                            <button class="btn btn-orange" type="submit">確認並更新</button>
                            {{--<button class="btn btn-white" type="submit">取消</button>--}}
                        </div>
                    </div>
                </form>
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
        function delConfig(conf_id) {
            var c = confirm("確認要刪除此項目嗎？");
            if(c){
                $.post("{{url('admin/config')}}/"+conf_id,
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

