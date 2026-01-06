@extends('Admin/Layouts/layout')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>管理者列表</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('admin/main-page')}}">首頁</a>
            </li>
            <li class="active">
                <strong>管理者列表</strong>
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
                    <h5>管理者列表</h5>
                </div>
                <div class="ibox-content">
                    {{--<div class="mb10"><button type="button" class="btn btn-primary" onclick="window.location.href='add-admin.html'"><i class="fa fa-plus mr5" aria-hidden="true"></i>新增管理者</button></div>--}}
                    <table class="table table-striped table-bordered table-hover dataTables-example" >
                        <thead>
                        <tr>
                            <th class="text-center" width="10%">編號</th>
                            <th class="text-center" width="11%">名稱</th>
                            <th class="text-center" width="10%">帳號</th>
                            <th class="text-center" width="7%">是否啓用</th>
                            <th class="text-center" width="15%">Email</th>
                            <th class="text-center" width="10%">建立時間</th>
                            <th class="text-center" width="10%">最後登入時間</th>
                            <th class="text-center" width="7%">登入次數</th>
                            <th class="text-center" width="10%">最後登入IP</th>
                            <th class="text-center" width="10%">功能</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $d)
                        <tr>
                            <td class="text-center">{{$d->user_id}}</td>
                            <td class="text-left">{{$d->user_name}}</td>
                            <td class="text-center">{{$d->user_email}}</td>
                            <td class="text-center"><span class="text-danger"></span></td>
                            <td class="text-left"></td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center">
                                <a href="#" class="mr5">編輯</a>
                                <a href="#">啓用</a>
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