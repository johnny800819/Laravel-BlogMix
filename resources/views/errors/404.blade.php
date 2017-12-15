<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="{{asset('org/Home/Article/images/favicon.png')}}">

    <title>發生錯誤了！</title>
    <link href="{{asset('org/Home/Article/css/bootstrap.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('org/Home/Article/css/style-for-pad.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('org/Home/Article/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css"/>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="{{asset('org/Home/Article/js/bootstrap.min.js')}}"></script>

    <style type="text/css">
        .sys-message h1 {
            padding: 0px 0 0 0px;
            margin: 0 auto;
            text-align: center;
            color: #434343;
            width: 400px;
        }

        .sys-message h3 {
            font-size: 24px;
            padding: 0px 0 0 0px;
            margin: 0 0 0px 0px;
            text-align: center;
            color: #434343;
        }

        .sys-message p.error {
            font-size: 14px;
            padding: 0px 0 0 0px;
            margin: 20px auto;
            text-align: center;
            width: 400px;
            color: #5b5b5b;
        }
    </style>
</head>

<body role="document">
<!--網站外框_開始-->
<div id="wrapper">

    <div class="topbar-min-fluid">
        <div class="container">
            <div class="row">

                <div class="col-md-12"></div>

            </div>
        </div>
    </div>

    <!--表頭_開始-->
    <header class="mt40">

        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">

                </div>
            </div>
        </div>


    </header>
    <!--表頭_結束-->

    <!--內容外框_開始-->
    <div id="body_content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-bg-img rwd-w990">

                        <div class="panel-body">

                            <div class="sys-message" style="margin:40px auto; width:500px;">
                                <h1><img src="{{asset('org/Home/Article/images/404-icon.png')}}" width="204" height="103"></h1>
                                <h3>Page Not Found</h3>
                                <p class="error">Sorry, but the page you are looking for has note been found. Try
                                    checking the URL for error, then hit the refresh button on your browser or try found
                                    something else in our app</p></div>

                            <div class="btn_submit">
                                <ul>
                                    <li><a href="{{url('/')}}" class="btn btn-default">回首頁</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--內容外框_結束-->


</div>
<!--網站外框_結束-->


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
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
</body>
</html>
