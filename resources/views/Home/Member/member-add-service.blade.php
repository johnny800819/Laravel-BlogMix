@extends('Home.Member.member-common')

@section('breadcrumb')
    <!--breadcrumb_開始-->
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="#">首頁</a></li>
                <li><a href="#">會員中心</a></li>
                <li class="active">聯絡客服</li>
            </ol>
        </div>
    </div>
    <!--breadcrumb_結束-->
@endsection

@section('member_content')
    <div class="right-row">

        <div class="col-md-12">

            <div class="panel panel-bg-img">
                <div class="panel-heading">
                    <h3 class="panel-title">聯絡客服</h3>
                </div>
                <div class="panel-body">

                    <form class="form-horizontal" action="{{url('member-add-service')}}" method="post">

                        {{csrf_field()}}

                        <div class="form-group border-dashed">
                            <label for="inputEmail3" class="col-sm-2 control-label">問題主旨</label>
                            <div class="col-sm-10">
                                <input name="slist_theme" type="text" class="form-control" id="" placeholder="">
                            </div>
                        </div>
                        <div class="form-group border-dashed">
                            <label for="inputEmail3" class="col-sm-2 control-label">問題類型</label>
                            <div class="col-sm-10">
                                <select name="slist_type" class="form-control">
                                    <option value="出貨問題">出貨問題</option>
                                    <option value="付款問題">付款問題</option>
                                    <option value="退貨問題">退貨問題</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group border-dashed">
                            <label for="inputPassword3" class="col-sm-2 control-label">回覆方式</label>
                            <div class="col-sm-10">
                                <select name="connecttype" class="form-control">
                                    <option value="system">系統線上回覆</option>
                                    <option value="email">信箱回覆（{{\Illuminate\Support\Facades\Auth::user()->email}}）</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group border-dashed">
                            <label for="inputPassword3" class="col-sm-2 control-label">問題內容</label>
                            <div class="col-sm-10">
                                <textarea name="slist_question" class="form-control" rows="10"></textarea>
                            </div>
                        </div>

                        <div class="form-group border-dashed">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-success">送出</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
