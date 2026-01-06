<!DOCTYPE html>
<html>
<head>
    <title>Laravel Blog</title>
    <link href="{{asset('css/Admin_login.css')}}" rel="stylesheet" >
</head>
<body>
<div id="container">
    <h1>Welcome</h1>
    @if(!empty($err_log))
    <div id="err_log">{{$err_log}}</div>
    @endif
    <span class="close-btn">
        <img src="https://cdn4.iconfinder.com/data/icons/miu/22/circle_close_delete_-128.png"></img>
    </span>
    <form action="" method="post">
        {{csrf_field()}}

        <input type="email" name="email" placeholder="E-mail">
        <input type="password" name="pass" placeholder="Password">
        <div class="g-recaptcha" data-sitekey="{{$site_data['siteKey']}}"></div>
        <script type="text/javascript" src="https://www.google.com/recaptcha/api.js?hl={{$site_data['lang']}}"></script>

        <input type="submit" id="login"></input>
        <div id="remember-container">
            <input type="checkbox" id="checkbox" class="checkbox" checked="checked"/>
            <span id="remember">Remember me</span>
            <span id="forgotten">Forgotten password</span>
        </div>
    </form>
</div>

<!-- Forgotten Password Container -->
<div id="forgotten-container">
    <h1>Forgotten</h1>
    <span class="close-btn">
        <img src="https://cdn4.iconfinder.com/data/icons/miu/22/circle_close_delete_-128.png"></img>
    </span>

    <form>
        <input type="email" name="email" placeholder="E-mail">
        <a href="#" class="orange-btn">Get new password</a>
    </form>
</div>
</body>
</html>
