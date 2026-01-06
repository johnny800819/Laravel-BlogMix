@extends('Admin/Layouts/layout')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2></h2>
        <ol class="breadcrumb">
            <li>
                <a target="_blank" href="{{url('/')}}"><button type="button" class="btn btn-info">返回網站前台</button></a>
                <a onclick="javascript:alert('尚未完成線上寄信…')"><button type="button" class="btn btn-info">聯絡管理者</button></a>
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
                    <h5>伺服器狀態</h5>
                </div>
                <div class="ibox-content">
                    <table class="table table-striped table-bordered table-hover dataTables-example" >
                        <tr>
                            <th class="text-center" width="30%">伺服器名稱</th>
                            <td class="text-left" width="70%">{{$_SERVER['SERVER_NAME']}}</td>
                        </tr>
                        <tr>
                            <th class="text-center" width="30%">伺服器IP位置</th>
                            <td class="text-left" width="70%">{{$_SERVER['HTTP_HOST']}}</td>
                        </tr>
                        <tr>
                            <th class="text-center" width="30%">來源IP位置</th>
                            <td class="text-left" width="70%">{{$_SERVER['REMOTE_ADDR']}}</td>
                        </tr>
                        <tr>
                            <th class="text-center" width="30%">PHP上傳檔案大小限制</th>
                            <td class="text-left" width="70%"><?php echo get_cfg_var("upload_max_filesize")?get_cfg_var("upload_max_filesize"):"不允許上傳附件檔案" ?></td>
                        </tr>
                        <tr>
                            <th class="text-center" width="30%">time</th>
                            <td class="text-left" width="70%">{{date('Y-m-d H:i:s')}}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection