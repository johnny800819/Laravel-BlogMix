@extends('Admin/Layouts/layout')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>客服列表</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('admin/main-page')}}">首頁</a>
                </li>
                <li class="active">
                    <strong>客服列表</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2">

        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>客服列表</h5>
                    </div>
                    <div class="ibox-content">

                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                            <tr>
                                <th class="text-center" width="10%">編號</th>
                                <th class="text-center" width="20%">主旨</th>
                                <th class="text-center" width="10%">狀態</th>
                                <th class="text-center" width="15%">詢問時間</th>
                                <th class="text-center" width="15%">回覆時間</th>
                                <th class="text-center" width="10%">會員 IP</th>
                                <th class="text-center" width="10%">問題類型</th>
                                <th class="text-center" width="10%">功能</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $d)
                                @php
                                    switch ($d->slist_status) {
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
                                <tr>
                                    <td class="text-center">{{$d->slist_id}}</td>
                                    <td class="text-left">{{$d->slist_theme}}</td>
                                    <td class="text-center">
                                        <span id="status_icon" class="badge badge-{{$icon}}">
                                            <i class="fa fa-check mr5" aria-hidden="true"></i>
                                            {{$text}}
                                        </span>
                                    </td>
                                    <td class="text-center">{{$d->slist_time}}</td>
                                    <td class="text-center">{{$d->response_time}}</td>
                                    <td class="text-center">{{$d->slist_ip}}</td>
                                    <td class="text-center">{{$d->slist_type}}</td>
                                    <td class="text-center"><a href="{{url('admin/service-list/'.$d->slist_id.'/edit')}}">編輯</a></td>
                                </tr>
                            @endforeach
                            {{--<tr>--}}
                            {{--<td class="text-center">2</td>--}}
                            {{--<td class="text-left">test01</td>--}}
                            {{--<td class="text-center"><span class="badge badge-danger"><i--}}
                            {{--class="fa fa-exclamation-circle mr5" aria-hidden="true"></i>未回覆</span></td>--}}
                            {{--<td class="text-center">2016-03-31 13:48:51</td>--}}
                            {{--<td class="text-center">2016-03-31 13:48:51</td>--}}
                            {{--<td class="text-center">61.219.41.217</td>--}}
                            {{--<td class="text-center">付款問題</td>--}}
                            {{--<td class="text-center"><a href="service-edit.html">編輯</a></td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                            {{--<td class="text-center">2</td>--}}
                            {{--<td class="text-left">test01</td>--}}
                            {{--<td class="text-center"><span class="badge badge-success"><i class="fa fa-clock-o mr5"--}}
                            {{--aria-hidden="true"></i>處理中</span>--}}
                            {{--</td>--}}
                            {{--<td class="text-center">2016-03-31 13:48:51</td>--}}
                            {{--<td class="text-center">2016-03-31 13:48:51</td>--}}
                            {{--<td class="text-center">61.219.41.217</td>--}}
                            {{--<td class="text-center">付款問題</td>--}}
                            {{--<td class="text-center"><a href="service-edit.html">編輯</a></td>--}}
                            {{--</tr>--}}
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
