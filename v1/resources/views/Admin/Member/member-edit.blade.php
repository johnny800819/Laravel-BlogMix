@extends('Admin/Layouts/layout')

@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>編輯會員</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('admin/member')}}">會員列表</a>
            </li>
            <li class="active">
                <strong>編輯會員</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2">

    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-7">
        </div>
        <div class="col-lg-5">
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>編輯會員</h5>
                </div>
                <div class="ibox-content">

                    <div>
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    <form method="post" action="{{url('admin/member/'.$data->id)}}" class="form-horizontal">

                        {{csrf_field()}}

                        {{method_field('patch')}}

                        <div class="form-group"><label class="col-sm-2 control-label">名稱</label>

                            <div class="col-sm-10"><p class="form-control-static">{{$data->name}}</div>
                        </div>
                        <div class="hr-line-dashed"></div>

                        <div class="form-group"><label class="col-sm-2 control-label">帳號</label>
                            <div class="col-sm-10"><p class="form-control-static">{{$data->email}}</p>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>

                        <div class="form-group"><label class="col-sm-2 control-label">密碼</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" name="password">
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>

                        <div class="form-group"><label class="col-sm-2 control-label">確認密碼</label>
                            <div class="col-sm-10">
                                <input type="password" placeholder="" class="form-control" name="password_confirmation">
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>

                        <div class="form-group"><label class="col-sm-2 control-label">地址</label>
                            <div class="col-sm-2">
                                <select name="mem_address1" class="form-control">
                                  <option>{{$data->mem_address1}}</option>
                                </select>
                            </div>

                            <div class="col-sm-2">
                                <select name="mem_address2" class="form-control">
                                  <option>{{$data->mem_address2}}</option>
                                </select>
                            </div>

                            <div class="col-sm-6">
                                <input name="mem_address3" type="text" class="form-control" id="" placeholder="" value="{{$data->mem_address3}}">
                            </div>

                        </div>
                        <div class="hr-line-dashed"></div>

                        <div class="form-group"><label class="col-sm-2 control-label">是否啓用</label>
                            <div class="col-sm-10">
                                <?php
                                    $ck1 = "";
                                    $ck2 = "";
                                ?>
                                @if($data->mem_lock == '0')
                                    <?php $ck1 = 'checked="checked"'; ?>
                                @else
                                    <?php $ck2 = 'checked="checked"'; ?>
                                @endif
                                <label class="radio-inline"><input type="radio" value="0" id="" name="mem_lock[]" {{$ck1}}> 是 </label>
                                <label class="radio-inline"><input type="radio" value="1" id="" name="mem_lock[]" {{$ck2}}> 否 </label>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>

                        <div class="form-group"><label class="col-sm-2 control-label">註冊時間</label>
                            <div class="col-sm-10"><p class="form-control-static">{{$data->mem_createtime}}</p>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>

                        <div class="form-group"><label class="col-sm-2 control-label">登入次數</label>
                            <div class="col-sm-10"><p class="form-control-static">{{$data->mem_count}}</p>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>

                        <div class="form-group"><label class="col-sm-2 control-label">最後登入時間</label>
                            <div class="col-sm-10"><p class="form-control-static">{{$data->mem_logintime}}</p>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>

                        <div class="form-group"><label class="col-sm-2 control-label">登入IP</label>
                            <div class="col-sm-10"><p class="form-control-static">{{$data->mem_loginip}}</p>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>

                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <button class="btn btn-success" type="submit">存檔</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection