@extends('Admin.Layouts.layout')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>文章類別列表</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('/admin/main-page')}}">首頁</a>
            </li>
            <li class="active">
                <strong>次類別列表</strong>
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
                <h5>次類別列表</h5>
            </div>
            <div class="ibox-content">
            <div class="explanation">
                <h4 class="note">操作流程：</h4>
                <ul class="decimal">
                    <li>Step1：按下【新增主類別】按鈕,輸入您的【主類別】名稱。</li>
                    <li>Step2：按下【新增次類別】按鈕,輸入您的【次類別】名稱後,並指定到【對應的主類別】當中。</li>
                    <li>Step3：新增文章時,選擇此文章歸屬在那一種類別中。</li>
                </ul>
            </div>
            <div class="mb10"><button type="button" class="btn btn-primary" data-toggle="modal" data-backdrop="static" data-target="#myModal"><i class="fa fa-plus mr5" aria-hidden="true"></i>新增次類別</button></div>
            <table class="table table-striped table-bordered table-hover dataTables-example" >
            <thead>
            <tr>
                <th class="text-center" width="10%">編號</th>
                <th class="text-center" width="40%">次類別名稱</th>
                <th class="text-center" width="40%">對應主類別名稱</th>
                <th class="text-center" width="10%">功能</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $v)
            <tr>
                <td class="text-center">{{$v->cate_id_sub}}</td>
                <td class="text-left">{{$v->cate_name_sub}}</td>
                <td class="text-left">{{$v->cate_name}}</td>
                <td class="text-center">
                    <a href="#" onclick="editCategory('{{$v->cate_id_sub}}')" class="mr5">編輯</a>
                    <a href="#" onclick="delCategory('{{$v->cate_id_sub}}')">刪除</a>
                </td>
            </tr>
            @endforeach
            </tbody>
            </table>
            </div>
        </div>
        </div>
    </div>
</div>

<!-- Button trigger modal -->

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">新增主類別</h4>
            </div>
            <div class="modal-body">

                <form name="addInfo">
                    {{csrf_field()}}
                    <div class="alert alert-danger" style="display: none" id="err_div">
                        <ul>
                            <li id="err_msg"></li>
                        </ul>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputPassword1">對應的主類別名稱</label>
                        <select name="cate_pid_sub" class="form-control">
                            @foreach($f_name as $f)
                                <option value="{{$f->cate_id}}">{{$f->cate_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">名稱</label>
                        <input type="text" class="form-control" name="cate_name_sub" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">標題</label>
                        <input type="text" class="form-control" name="cate_title_sub" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">關鍵字</label>
                        <input type="text" class="form-control" name="cate_keyword_sub" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">描述</label>
                        <textarea class="form-control" rows="5" name="cate_description_sub"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">排序</label>
                        <input type="text" class="form-control" name="cate_order_sub" placeholder="">
                    </div>
                    <input type="hidden" name="ModalType">
                    <input type="hidden" name="cate_id_sub">
                </form>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" onclick="CreateCategory()" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<script>
    var ModalClearFlag = 'on';
    var ModalType = 'Add';
    //新增或編輯後送出
    function CreateCategory() {
        $('[name="addInfo"]').attr('method','post');
        $('[name="addInfo"]').attr('acction','{{url('admin/article-category-sub')}}');
        //判斷資料規則
        var cate_name_sub = $('[name="cate_name_sub"]').val();
        var cate_pid_sub = $('[name="cate_pid_sub"] option:selected').val();
        if(cate_name_sub == ""){
            $('#err_div').css('display','');
            $('#err_msg').html('名稱不能為空！');
            return;
        }
        if(cate_pid_sub === undefined){
            $('#err_div').css('display','');
            $('#err_msg').html('請選擇主類別！');
            return;
        }
        $('[name="ModalType"]').val(ModalType);
        ModalType = 'Add';
        $('[name="addInfo"]').submit();
    }
    //編輯類別
    function editCategory(cate_id) {
        ModalType = 'Edit';
        $.ajax({
            type: "GET",
            url: 'article-category-sub/'+cate_id+'/edit',
            success: function( data ) {
                $('[name="cate_pid_sub"]').val(data.cate_pid_sub);
                $('[name="cate_name_sub"]').val(data.cate_name_sub);
                $('[name="cate_title_sub"]').val(data.cate_title_sub);
                $('[name="cate_keyword_sub"]').val(data.cate_keyword_sub);
                $('[name="cate_description_sub"]').val(data.cate_description_sub);
                $('[name="cate_order_sub"]').val(data.cate_order_sub);
                $('[name="cate_id_sub"]').val(cate_id);
                ModalClearFlag = 'close';
                $('#err_div').css('display','none');
                $('#myModal').modal('show');
                ModalClearFlag = 'on';
                /*setTimeout(function(){
                },2000);*/
            }
        });
    }
    //DELETE類別
    function delCategory(cate_id) {
        var c = confirm("確認要刪除此項目嗎？");
        if(c){
            $.post("{{url('admin/article-category-sub/')}}/"+cate_id,
            {
                '_method':'DELETE',
                '_token':'{{csrf_token()}}',
                'cate_id': cate_id,
            },
            function(data){
                if(data.result){
                    window.location.reload();
                };
            });
        }
    }
    function ModalClear(flag) {
        if(flag != 'close'){
            //控制Modal開啟關閉事件
            $('#err_div').css('display','none');
            $('[name="cate_pid_sub"]').val('');
            $('[name="cate_name_sub"]').val('');
            $('[name="cate_title_sub"]').val('');
            $('[name="cate_keyword_sub"]').val('');
            $('[name="cate_description_sub"]').val('');
            $('[name="cate_order_sub"]').val('');
            $('[name="cate_id_sub"]').val('');
        }
    }
    $('#myModal').on('show.bs.modal', function () {
        ModalClear(ModalClearFlag);
    })
</script>
@endsection
