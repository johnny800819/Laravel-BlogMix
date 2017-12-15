@extends('Admin/Layouts/layout')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>編輯客服信件</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('admin/main-page')}}">首頁</a>
                </li>
                <li>
                    <a href="{{url('admin/service-list')}}">客服列表</a>
                </li>
                <li class="active">
                    <strong>編輯客服信件</strong>
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
                        <h5>編輯客服信件</h5>
                    </div>
                    <div class="ibox-content">
                        <form action="{{url('admin/service-list/'.$data->slist_id)}}" method="post" class="form-horizontal">

                            {{csrf_field()}}
                            {{method_field('patch')}}

                            <div class="form-group"><label class="col-sm-2 control-label">編號</label>

                                <div class="col-sm-10"><p class="form-control-static">{{$data->slist_id}}</div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group"><label class="col-sm-2 control-label">回覆狀態</label>
                                @php
                                    switch ($data->slist_status) {
                                        case 1:
                                            $icon = 'success';
                                            $text = '處理中';
                                        break;
                                        case 0:
                                            $icon = 'danger';
                                            $text = '未回覆';
                                        break;
                                        case 2:
                                            $icon = 'primary';
                                            $text = '已回覆';
                                        break;
                                    }
                                @endphp
                                <div class="col-sm-10"><p class="form-control-static">{{$text}}</div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group"><label class="col-sm-2 control-label">發問時間</label>
                                <div class="col-sm-10"><p class="form-control-static">{{$data->slist_time}}</p>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group"><label class="col-sm-2 control-label">會員帳號</label>
                                <div class="col-sm-10"><p class="form-control-static">{{$data->email}}</p>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group"><label class="col-sm-2 control-label">會員暱稱</label>
                                <div class="col-sm-10"><p class="form-control-static">{{$data->name}}</p>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group"><label class="col-sm-2 control-label">登入IP</label>
                                <div class="col-sm-10"><p class="form-control-static">{{$data->slist_ip}}</p>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group"><label class="col-sm-2 control-label">問題主旨</label>
                                <div class="col-sm-10"><p class="form-control-static">{{$data->slist_theme}}</p>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group"><label class="col-sm-2 control-label">問題內容</label>
                                <div class="col-sm-10"><p class="form-control-static">{{$data->slist_question}}</p>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group"><label class="col-sm-2 control-label">客服狀態</label>
                                <div class="col-sm-10">
                                    <label class="radio-inline"><input type="radio" value="0" id="s0"
                                                                       name="optionsRadios[]"> 未回覆
                                    </label>
                                    <label class="radio-inline"><input type="radio" value="1" id="s1"
                                                                       name="optionsRadios[]"> 處理中
                                    </label>
                                    <label class="radio-inline"><input type="radio" value="2" id="s2"
                                                                       name="optionsRadios[]"> 已回覆
                                    </label>
                                </div>
                                <script>
                                    $('#s{{$data->slist_status}}').attr('checked','true');
                                </script>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group"><label class="col-sm-2 control-label">回覆內容</label>
                                <div class="col-sm-10">
                                    <textarea name="slist_response" class="form-control" rows="10">{{$data->slist_response}}</textarea>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <button class="btn btn-success" type="submit">存檔</button>
                                    <a href="{{url('admin/service-list')}}"><button class="btn btn-cancel">取消</button></a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection