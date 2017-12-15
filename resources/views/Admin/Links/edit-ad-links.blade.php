@extends('Admin/Layouts/layout')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>編輯好文章廣告</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/admin/main-page')}}">首頁</a>
                </li>
                <li>
                    <a href="{{url('/admin/ad-links')}}">好文章廣告管理</a>
                </li>
                <li class="active">
                    <strong>編輯好文章廣告</strong>
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
                        <h5>編輯好文章廣告</h5>
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

                        這邊順邊貼個學習文...（如下）
                        patch方法用来更新局部资源，这句话我们该如何理解？
                        假设我们有一个UserInfo，里面有userId， userName， userGender等10个字段。可你的编辑功能因为需求，在某个特别的页面里只能修改userName，这时候的更新怎么做？
                        人们通常(为徒省事)把一个包含了修改后userName的完整userInfo对象传给后端，做完整更新。但仔细想想，这种做法浪费带宽。
                        于是patch诞生，只传一个userName到指定资源去，表示该请求是一个局部更新，后端仅更新接收到的字段。
                        而put虽然也是更新资源，但要求前端提供的一定是一个完整的资源对象，理论上说，如果你用了put，但却没有提供完整的UserInfo，那么缺了的那些字段应该被清空

                        <form method="post" action="{{url('admin/ad-links/'.$data->link_id)}}" class="form-horizontal">
                            {{method_field('put')}}
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

                                <div class="col-sm-10"><input name="link_title" type="text" class="form-control" value="{{$data->link_title}}"></div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group"><label class="col-sm-2 control-label">網址</label>

                                <div class="col-sm-10"><input name="link_url" type="url" class="form-control" value="{{$data->link_url}}"></div>
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
                                <div class="col-sm-10"><textarea name="link_description" class="form-control" rows="10">{{$data->link_description}}</textarea>
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group"><label class="col-sm-2 control-label">順序</label>

                                <div class="col-sm-10"><input name="link_order" value="{{$data->link_order}}" type="text" class="form-control"></div>
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