@extends('Home.Article.Layouts.home')

@section('info')
    <title>{{\Illuminate\Support\Facades\Config::get('web.web_title')}} | 登入/註冊</title>
    <meta name="description" content="{{\Illuminate\Support\Facades\Config::get('web.web_description')}}">
    <meta name="keywords" content="{{\Illuminate\Support\Facades\Config::get('web.web_keywords')}}">
@endsection

@section('content')

    <!--內容外框_開始-->
    <div id="body_content">

        <div class="container">

            @if (count($errors)>0 && is_object($errors))
                <div class="alert alert-danger col-sm-12 pl0">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>&nbsp&nbsp&nbsp{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @elseif(count($errors)>0)
                <div class="alert alert-danger col-sm-12 pl0">
                    <ul>
                        @foreach ($errors as $error)
                            <li>&nbsp&nbsp&nbsp{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <!--會員登入和註冊_開始-->
                <div class="member-row">

                    <!--會員登入_開始-->
                    <div class="col-md-6 col-sm-6">
                        <div class="panel panel-bg-img">
                            <div class="panel-heading">
                                <h3 class="panel-title">會員登入</h3>
                            </div>
                            <div class="panel-body">

                                <form method="post" action="{{url('/login')}}">

                                    {{csrf_field()}}

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">帳號</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                               placeholder="請輸入註冊時的Email">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">密碼</label>
                                        <input type="password" class="form-control" id="password" name="password"
                                               placeholder="請輸入註冊時的密碼">
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox"> 記住我的帳號
                                        </label>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12 pl0">
                                            <a href="#" data-toggle="modal" data-target="#myModal"><i
                                                        class="fa fa-unlock-alt mr5" aria-hidden="true"></i>忘記密碼</a>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-black mt20">登入</button>
                                </form>

                            </div>

                        </div>

                    </div>
                    <!--會員登入_結束-->

                    <!--會員註冊_開始-->
                    <div class="col-md-6 col-sm-6">
                        <div class="panel panel-bg-img">
                            <div class="panel-heading">
                                <h3 class="panel-title">會員註冊</h3>
                            </div>
                            <div class="panel-body">

                                <form method="post" action="{{url('/register')}}">

                                    {{csrf_field()}}

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">帳號</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                               placeholder="請輸入Email">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword2">密碼</label>
                                        <input type="password" class="form-control" id="password" name="password"
                                               placeholder="請輸入密碼">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword3">確認密碼</label>
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                                               placeholder="再輸入一次密碼">
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" id="blog_lock" name="mem_lock" value="0"> 我已閱讀並同意<a href="#">會員條款</a>
                                        </label>
                                    </div>
                                    <button type="submit" class="btn btn-success">加入會員</button>
                                </form>

                            </div>
                        </div>
                    </div>
                    <!--會員註冊_結束-->

                </div>
                <!--會員登入和註冊_結束-->

            </div>
        </div>
    </div>
    <!--內容外框_結束-->

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">忘記密碼</h4>
                </div>
                <div class="modal-body">

                    <form>
                        <div class="form-group">
                            <input type="email" class="form-control" id="exampleInputEmail1" placeholder="請輸入註冊時的Email">
                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success">送出</button>
                </div>
            </div>
        </div>
    </div>
@endsection
