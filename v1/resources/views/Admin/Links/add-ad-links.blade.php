@extends('Admin/Layouts/layout')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>新增好文章廣告</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/admin/main-page')}}">首頁</a>
                </li>
                <li>
                    <a href="{{url('/admin/ad-links')}}">好文章廣告管理</a>
                </li>
                <li class="active">
                    <strong>新增好文章廣告</strong>
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
                        <h5>新增好文章廣告</h5>
                    </div>
                    <div class="ibox-content">
                        @if (count($errors) > 0 && is_object($errors))
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="post" action="{{url('admin/ad-links')}}" class="form-horizontal">
                            {{csrf_field()}}

                            <div class="form-group"><label class="col-sm-2 control-label">廣告位置</label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="link_page">
                                        <option value="0">首頁</option>
                                    </select>
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group"><label class="col-sm-2 control-label">顯示是否</label>

                                <div class="col-sm-10">
                                <label class="radio-inline"><input type="radio" value="11" id="" name="link_toggle" checked=""> 是 </label>
                                <label class="radio-inline"><input type="radio" value="00" id="" name="link_toggle"> 否 </label>
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group"><label class="col-sm-2 control-label">標題</label>

                                <div class="col-sm-10"><input name="link_title" type="text" class="form-control"></div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group"><label class="col-sm-2 control-label">網址</label>

                                <div class="col-sm-10"><input name="link_url" type="url" class="form-control" value="http://"></div>
                            </div>

                            {{--<div class="hr-line-dashed"></div>--}}
                            {{--<div class="form-group"><label class="col-sm-2 control-label">大圖</label>--}}

                                {{--<div class="col-sm-10"><input type="file" class="form-control" name=""><span class="help-block m-b-none">建議尺寸1170x590</span></div>--}}
                            {{--</div>--}}

                            {{--<div class="hr-line-dashed"></div>--}}
                            {{--<div class="form-group"><label class="col-sm-2 control-label">小圖</label>--}}

                                {{--<div class="col-sm-10"><input type="file" class="form-control" name=""><span class="help-block m-b-none">建議尺寸100x100</span></div>--}}
                            {{--</div>--}}

                            <div class="hr-line-dashed"></div>
                            <div class="form-group"><label class="col-sm-2 control-label">短述</label>
                                <div class="col-sm-10"><textarea name="link_description" class="form-control" rows="10"></textarea>
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group"><label class="col-sm-2 control-label">順序</label>

                                <div class="col-sm-10"><input name="link_order" value="1" type="text" class="form-control"></div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <button class="btn btn-primary" type="submit">存檔</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer">
    <div>
        <strong>Copyright</strong> © Protype All Rights Reserved
    </div>
    </div>
@endsection