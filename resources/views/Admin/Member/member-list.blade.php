@extends('Admin/Layouts/layout')

@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>會員列表</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('admin/main-page')}}">首頁</a>
            </li>
            <li class="active">
                <strong>會員列表</strong>
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
                <h5>會員列表</h5>
            </div>
            <div class="ibox-content">

            <table class="table table-striped table-bordered table-hover dataTables-example" >
            <thead>
            <tr>
                <th class="text-center" width="10%">編號</th>
                <th class="text-center" width="12%">Email</th>
                <th class="text-center" width="10%">暱稱</th>
                <th class="text-center" width="10%">是否封鎖</th>
                <th class="text-center" width="10%">登入次數</th>
                <th class="text-center" width="10%">建立時間</th>
                <th class="text-center" width="10%">上次登入時間</th>
                <th class="text-center" width="10%">上次登入 IP</th>
                <th class="text-center" width="18%">功能</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $d)
            <tr>
                <td class="text-center">{{$d->id}}</td>
                <td class="text-left">{{$d->email}}</td>
                <td class="text-center">{{$d->name}}</td>
                <td class="text-center">
                    @if($d->mem_lock == '0')
                        <span class="text-success">否</span>
                    @else
                        <span class="text-danger">是</span>
                    @endif
                </td>
                <td class="text-center">{{$d->mem_count}}</td>
                <td class="text-center">{{$d->mem_createtime}}</td>
                <td class="text-center">{{$d->mem_logintime}}</td>
                <td class="text-center">{{$d->mem_loginip}}</td>
                <td class="text-center">
                    <a href="{{url('admin/member/'.$d->id.'/edit')}}" class="mr5">修改內容</a>
                    @if($d->mem_lock == '0')
                        <a href="javascript:lock_active('1','{{$d->id}}')">封鎖</a>
                    @else
                        <a href="javascript:lock_active('0','{{$d->id}}')">復權</a>
                    @endif
                    <script>
                        function lock_active(mem_lock,id) {
                            $.ajax({
                                url:'{{url('admin/member')}}/'+id,
                                data:{
                                    '_token':'{{csrf_token()}}',
                                    '_method':'delete',
                                    'mem_lock':mem_lock
                                },
                                type:'post',
                                success:function(msg){
                                    location.reload();
                                }
                            });
                        }
                    </script>
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
@endsection