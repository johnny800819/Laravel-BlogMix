<style>
    html { height: 100% }
    body {
        background-image: radial-gradient(rgba(92,100,111,0.5) 0%,rgba(31,35,40,1) 100%);
    }
    .login {
        background: #eceeee;
        border: 1px solid #42464b;
        border-radius: 6px;
        height: auto;
        margin: 120px auto 0;
        width: 398px;
        text-align:center;
    }
    .login h1 {
        background-image: linear-gradient(#f1f3f3, #d4dae0);
        border-bottom: 1px solid #a6abaf;
        border-radius: 6px 6px 0 0;
        box-sizing: border-box;
        color: #727678;
        display: block;
        height: 43px;
        font: 600 14px/1 'Open Sans', sans-serif;
        padding-top: 14px;
        margin: 0;
        text-align: center;
        text-shadow: 0 -1px 0 rgba(0,0,0,0.2), 0 1px 0 #fff;
        user-select: none;
    }
    input[type="password"], input[type="text"] {
        background: url('http://i.minus.com/ibhqW9Buanohx2.png') center left no-repeat, linear-gradient(#d6d7d7, #dee0e0);
        border: 1px solid #a1a3a3;
        border-radius: 4px;
        box-shadow: 0 1px #fff;
        box-sizing: border-box;
        color: #696969;
        height: 39px;
        margin: 10px 0 10px 0;
        padding-left: 37px;
        transition: box-shadow 0.3s;
        width: 240px;
    }
    input[type="password"]:focus, input[type="text"]:focus {
        box-shadow: 0 0 4px 1px rgba(55, 166, 155, 0.3);
        outline: 0;
    }
    .show-password {
        display: block;
        height: 16px;
        margin: 26px 0 0 28px;
        width: 87px;
    }
    input[type="checkbox"] {
        cursor: pointer;
        height: 16px;
        opacity: 0;
        position: relative;
        width: 64px;
    }
    input[type="checkbox"]:checked {
        left: 29px;
        width: 58px;
    }
    .toggle {
        background: url(http://i.minus.com/ibitS19pe8PVX6.png) no-repeat;
        display: block;
        height: 16px;
        margin-top: -20px;
        width: 87px;
        z-index: -1;
    }
    input[type="checkbox"]:checked + .toggle { background-position: 0 -16px }
    .forgot {
        color: #7f7f7f;
        display: inline-block;
        float: right;
        font: 12px/1 sans-serif;
        left: -19px;
        position: relative;
        text-decoration: none;
        top: -15px;
        transition: color .4s;
    }
    .forgot:hover { color: #3b3b3b }
    input[type="submit"] {
        background-color: #37a69b;
        background-image: linear-gradient(#3db0a6,#319d91);
        border: 1px solid #256f67;
        border-radius: 4px;
        box-shadow: inset 0 1px rgba(255,255,255,0.3);
        box-sizing: border-box;
        color: #f8f8f8;
        font-weight: 700;
        height: 39px;
        margin: 24px 0 10px 0px;
        text-shadow: 0 -1px 0 #177c6a;
        width: 200px;
    }
    input[type="submit"]:hover, input[type="submit"]:focus {
        background-image: linear-gradient(#4ec7c0,#31aba3)
    }
    input[type="submit"]:active {
        background-image: linear-gradient(#319d91, #3db0a6);
        padding: 0;
    }
    .shadow {
        background: #000;
        border-radius: 12px 12px 4px 4px;
        box-shadow: 0 0 20px 10px #000;
        height: 12px;
        margin: 30px auto;
        opacity: 0.2;
        width: 270px;
    }
    #pass_ori,#pass_new,#pass_confirm{
        float:left;
    }
</style>

@extends('Admin/Layouts/layout')

@section('content')
    <form action="" method="post">
        {{csrf_field()}}

        <div class="login">

            <h1>修改密碼</h1>
            <div>
                ＊原密碼
                <br>
                <input id="password_o" name="password_o" placeholder="password" type="password" />
            </div>
            <div>
                ＊新密碼 (位數請介於6~16位)
                <br>
                <input id="password_n" name="password_n" placeholder="password" type="password" />
            </div>
            <div>
                ＊確認新密碼
                <br>
                <input id="password_c" name="password_n_confirmation" placeholder="password" type="password" />
            </div>
            <!--
              <label for="c" class="show-password">
                <input type="checkbox" id="c"/>
                <i class="toggle"></i>
              </label>
            -->
            <div>
                @if (count($errors) > 0 && is_object($errors))
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @elseif(Session::has('success'))
                    <div class="alert alert-success">
                        <ul>
                            <li>{{ Session::get('success') }}</li>
                        </ul>
                    </div>
                    @else
                @endif
            </div>
            <input type="submit" value="確認" />

        </div>
        <div class="shadow"></div>
    </form>
@endsection