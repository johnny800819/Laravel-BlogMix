@extends('Home.Member.member-common')

@section('breadcrumb')
    <!--breadcrumb_開始-->
    <div class="row">
        <div class="col-md-12">

            <ol class="breadcrumb">
                <li><a href="{{url('/')}}">首頁</a></li>
                <li><a href="{{url('/member-profile')}}">會員中心</a></li>
                <li class="active">會員資料修改</li>
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
                    <h3 class="panel-title">會員資料修改</h3>
                </div>
                <div class="panel-body">
                    @if (count($errors)>0 && is_object($errors))
                        <div class="alert alert-danger col-sm-12 pl0">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>&nbsp&nbsp&nbsp{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @elseif(count($errors)>0)
                        <script>alert('{{$errors}}');</script>
                    @endif
                    <form method="post" action="{{url('member-profile/'.$data->id)}}" class="form-horizontal">

                        {{csrf_field()}}
                        {{method_field('patch')}}

                        <div class="form-group border-dashed">
                            <label for="inputEmail3" class="col-sm-2 control-label">姓名</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="name" placeholder=""
                                       value="{{$data->name}}">
                            </div>
                        </div>
                        <div class="form-group border-dashed">
                            <label for="inputEmail3" class="col-sm-2 control-label">帳號</label>
                            <div class="col-sm-10">
                                <p class="form-control-static">{{$data->email}}</p>
                            </div>
                        </div>
                        <div class="form-group border-dashed">
                            <label for="inputPassword3" class="col-sm-2 control-label">新密碼</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" name="password"
                                       placeholder="Password">
                            </div>
                        </div>
                        <div class="form-group border-dashed">
                            <label for="inputPassword3" class="col-sm-2 control-label">重新輸入新密碼</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" name="password_confirmation"
                                       placeholder="Password">
                            </div>
                        </div>
                        <div class="form-group border-dashed">
                            <label for="inputPassword3" class="col-sm-2 control-label">地址</label>
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
                                <input name="mem_address3" type="text" class="form-control" id="" placeholder=""
                                       value="{{$data->mem_address3}}">
                            </div>
                        </div>
                        <div class="form-group border-dashed">
                            <label for="inputEmail3" class="col-sm-2 control-label">連絡電話</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="mem_tel" placeholder=""
                                       value="{{$data->mem_tel}}">
                            </div>
                        </div>
                        <div class="form-group border-dashed">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class=" btn btn-success">儲存</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
