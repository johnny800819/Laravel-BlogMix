@extends('Admin/Layouts/layout')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>編輯網站配置</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/admin/main-page')}}">首頁</a>
                </li>
                <li>
                    <a href="{{url('admin/config')}}">網站配置管理</a>
                </li>
                <li class="active">
                    <strong>編輯網站配置</strong>
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
                        <h5>編輯網站配置</h5>
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
                        <form method="post" action="{{url('admin/config/'.$data->conf_id)}}" class="form-horizontal">
                            {{method_field('put')}}
                            {{csrf_field()}}

                            <div class="hr-line-dashed"></div>
                            <div class="form-group"><label class="col-sm-2 control-label">標題</label>

                                <div class="col-sm-10"><input name="conf_title" type="text" class="form-control" value="{{$data->conf_title}}"></div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group"><label class="col-sm-2 control-label">名稱</label>

                                <div class="col-sm-10"><input name="conf_name" type="text" class="form-control" value="{{$data->conf_name}}"></div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group"><label class="col-sm-2 control-label">類型</label>
                                <div class="col-sm-10">
                                    <label class="radio-inline"><input type="radio" value="input" onclick="ShowValue()" name="field_type"> input &nbsp;&nbsp;</label>
                                    <label class="radio-inline"><input type="radio" value="textarea" onclick="ShowValue()" name="field_type"> textarea &nbsp;&nbsp;</label>
                                    <label class="radio-inline"><input type="radio" value="radio" onclick="ShowValue()" name="field_type"> radio </label>
                                    <label class="radio-inline"><input type="radio" value="select" onclick="ShowValue()" name="field_type"> select </label>
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div id="field_value" class="form-group"><label class="col-sm-2 control-label">類型值</label>

                                <div class="col-sm-10">
                                    <input name="field_value" type="text" class="form-control" value="{{$data->field_value}}">
                                    <div>例如︰選擇radio時, 1｜開啟 0｜關閉 諸如此類</div>
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group"><label class="col-sm-2 control-label">短述</label>
                                <div class="col-sm-10"><textarea name="conf_tips" class="form-control" rows="10">{{$data->conf_tips}}</textarea>
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group"><label class="col-sm-2 control-label">順序</label>

                                <div class="col-sm-10"><input name="conf_order" value="{{$data->conf_order}}" type="text" class="form-control"></div>
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


    <script>
        $(document).ready(function () {
            $('input[name=field_type][value={!! $data->field_type !!}]').prop('checked',true);
            ShowValue();
        })
        function ShowValue() {
            var val = $('input[name=field_type]:checked').val();
            if(val == 'radio' ||
                val == 'select'){
                $('div #field_value').show();
            }else{
                $('div #field_value').hide();
            }
        }
    </script>
@endsection