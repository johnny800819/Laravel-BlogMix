<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Blog-後台管理</title>
    <link rel="shortcut icon" href="{{asset('img/favicon.ico')}}" type="image/x-icon" />
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('font-awesome/css/font-awesome.css')}}" rel="stylesheet">

    <!-- Data Tables -->
    <link href="{{asset('css/plugins/dataTables/dataTables.bootstrap.css')}}" rel="stylesheet">
    <link href="{{asset('css/plugins/dataTables/dataTables.responsive.css')}}" rel="stylesheet">
    <link href="{{asset('css/plugins/dataTables/dataTables.tableTools.min.css')}}" rel="stylesheet">

    <link href="{{asset('css/animate.css')}}" rel="stylesheet">
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
    <link href="{{asset('css/skin.css')}}" rel="stylesheet">
</head>

<!-- Mainly scripts -->
<script src="{{asset('js/jquery-2.1.1.js')}}"></script>

<body>
<div id="wrapper">

    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav" id="side-menu">
                <li class="profile-element"><img src="{{asset('img/logo_demo220_155.jpg')}}" width="220" height="155"></li>
                <li><a href="{{url('admin/main-page')}}"><span class="nav-label">首頁</span> </a></li>
                <li><a href="{{url('admin/member')}}"><span class="nav-label">會員列表</span> </a></li>
                <li><a href="{{url('admin/service-list')}}"><span class="nav-label">客服列表</span></a></li>
                {{--<li><a href="cooperation-list.html"><span class="nav-label">合作提案</span></a></li>--}}
                {{--<li><a href="order-list.html"><span class="nav-label">訂單列表</span></a></li>--}}
                <li><a href="{{url('admin/admin-list')}}"><span class="nav-label">管理者列表</span></a></li>
                {{--<li>--}}
                    {{--<a href="#"><span class="nav-label">好商品管理</span> <span class="fa arrow"></span></a>--}}
                    {{--<ul class="nav nav-second-level">--}}
                        {{--<li ><a href="product-category-0.html">主類別管理</a></li>--}}
                        {{--<li ><a href="product-category-1.html">次類別管理</a></li>--}}
                        {{--<li ><a href="product-list.html">商品列表</a></li>--}}
                        {{--<li ><a href="product-ad-list.html">廣告列表</a></li>--}}
                    {{--</ul>--}}
                {{--</li>--}}
                <li>
                    <a href="#"><span class="nav-label">好文章管理</span> <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li ><a href="{{url('admin/article-category-main')}}">主類別管理</a></li>
                        <li ><a href="{{url('admin/article-category-sub')}}">次類別管理</a></li>
                        <li ><a href="{{url('admin/article')}}">文章列表</a></li>
                        <li ><a href="{{url('admin/ad-links')}}">廣告列表</a></li>
                    </ul>
                </li>
                <li><a href="{{url('admin/config')}}"><span class="nav-label">網站配置</span></a></li>
            </ul>
        </div>
    </nav>

    <div id="page-wrapper" class="img-bg">
    <div class="row border-bottom">
        <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
            <ul class="nav navbar-top-links navbar-right">
                <li>
                    <span class="m-r-sm text-muted welcome-message">admin</span>
                </li>
                <li>
                    <a href="{{url('admin/pass-edit')}}">
                    <span class="#">修改密碼</span>
                </li>
                <li>
                    <a href="{{url('admin/admin-logout')}}">
                        <i class="fa fa-sign-out"></i> Log out
                    </a>
                </li>
            </ul>

        </nav>
    </div>

    @yield('content')

    <div class="footer">
        <div>
            <strong>Copyright</strong> © Protype All Rights Reserved
        </div>
    </div>
    </div>
</div>

<!-- Page-Level Scripts -->
<script>
    $(document).ready(function() {
        $('.dataTables-example').dataTable({
            responsive: true,
            "ordering": false,
            "dom": 'T<"clear">lfrtip',
            "tableTools": {
                "sSwfPath": "js/plugins/dataTables/swf/copy_csv_xls_pdf.swf"
            }
        });

        /* Init DataTables */
        var oTable = $('#editable').dataTable();

        /* Apply the jEditable handlers to the table */
        oTable.$('td').editable( '../example_ajax.php', {
            "callback": function( sValue, y ) {
                var aPos = oTable.fnGetPosition( this );
                oTable.fnUpdate( sValue, aPos[0], aPos[1] );
            },
            "submitdata": function ( value, settings ) {
                return {
                    "row_id": this.parentNode.getAttribute('id'),
                    "column": oTable.fnGetPosition( this )[2]
                };
            },

            "width": "90%",
            "height": "100%"
        } );


    });

    function fnClickAddRow() {
        $('#editable').dataTable().fnAddData( [
            "Custom row",
            "New row",
            "New row",
            "New row",
            "New row" ] );

    }
</script>
<style>
    body.DTTT_Print {
        background: #fff;

    }
    .DTTT_Print #page-wrapper {
        margin: 0;
        background:#fff;
    }

    button.DTTT_button, div.DTTT_button, a.DTTT_button {
        border: 1px solid #e7eaec;
        background: #fff;
        color: #676a6c;
        box-shadow: none;
        padding: 6px 8px;
    }
    button.DTTT_button:hover, div.DTTT_button:hover, a.DTTT_button:hover {
        border: 1px solid #d2d2d2;
        background: #fff;
        color: #676a6c;
        box-shadow: none;
        padding: 6px 8px;
    }

    .dataTables_filter label {
        margin-right: 5px;

    }
</style>
</body>

</html>

<script src="{{asset('js/bootstrap.min.js')}}"></script>
<script src="{{asset('js/plugins/metisMenu/jquery.metisMenu.js')}}"></script>
<script src="{{asset('js/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>
<script src="{{asset('js/plugins/jeditable/jquery.jeditable.js')}}"></script>

<!-- Data Tables -->
<script src="{{asset('js/plugins/dataTables/jquery.dataTables.js')}}"></script>
<script src="{{asset('js/plugins/dataTables/dataTables.bootstrap.js')}}"></script>
<script src="{{asset('js/plugins/dataTables/dataTables.responsive.js')}}"></script>
<script src="{{asset('js/plugins/dataTables/dataTables.tableTools.min.js')}}"></script>

<!-- Custom and plugin javascript -->
<script src="{{asset('js/inspinia.js')}}"></script>
<script src="{{asset('js/plugins/pace/pace.min.js')}}"></script>