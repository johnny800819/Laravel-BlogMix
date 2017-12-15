@extends('Admin/Layouts/layout')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>新增好文章</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/admin/main-page')}}">首頁</a>
                </li>
                <li>
                    <a href="{{url('/admin/article')}}">好文章管理</a>
                </li>
                <li class="active">
                    <strong>新增好文章</strong>
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
                        <h5>新增好文章</h5>
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
                        @if (count($errors) > 0 && is_object($errors))

                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="post" action="{{url('admin/article')}}" class="form-horizontal">
                            {{csrf_field()}}

                            <div class="form-group"><label class="col-sm-2 control-label">文章標題</label>

                                <div class="col-sm-10"><input name="art_title" type="text" class="form-control"></div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group"><label class="col-sm-2 control-label">發佈時間</label>

                                <div class="col-sm-10"><input name="art_time" type="date" value="{{date('Y-m-d')}}" class="form-control"></div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group"><label class="col-sm-2 control-label">主分類</label>

                                <div class="col-sm-10">
                                    <select class="form-control m-b" name="cate_id">
                                        @foreach($f_name as $f)
                                            <option value="{{$f->cate_id}}">{{$f->cate_name}}</option>
                                        @endforeach
                                    </select>
                                    <a href="#" class="form-control-link-static">新增主類別</a>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group"><label class="col-sm-2 control-label">次分類</label>

                                <div class="col-sm-10">
                                    <select disabled="disabled" class="form-control m-b" name="cate_id_sub">
                                        {{--開發中--}}
                                        <option value="0">開發中...</option>
                                    </select>
                                    <a href="#" class="form-control-link-static">新增次類別</a>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            {{--<div class="form-group"><label class="col-sm-2 control-label">右側好商品露出</label>--}}

                                {{--<div class="col-sm-10"><input type="text" class="form-control" placeholder=""><span class="help-block m-b-none">請輸入商品編號,第二個以上商品請"逗號"隔開即可</span></div>--}}
                            {{--</div>--}}

                            {{--<div class="hr-line-dashed"></div>--}}
{{--<div class="form-group"><label class="col-sm-2 control-label">顯示是否</label>--}}

                                {{--<div class="col-sm-10">--}}
                                {{--<label class="radio-inline"><input type="radio" value="" id="" name="optionsRadios" checked=""> 是 </label>--}}
                                {{--<label class="radio-inline"><input type="radio" value="" id="" name="optionsRadios"> 否 </label>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="hr-line-dashed"></div>--}}
                            <div class="form-group"><label class="col-sm-2 control-label">簡述</label>
                                <div class="col-sm-10"><textarea name="art_description" class="form-control" rows="10"></textarea>
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group"><label class="col-sm-2 control-label">標籤</label>
                                <div class="col-sm-10"><input name="art_tag" type="text" class="form-control"/>
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group"><label class="col-sm-2 control-label">縮略圖</label>
                                <div class="col-sm-2">
                                    <script src="{{asset('org/uploadify/jquery.uploadify.min.js')}}" type="text/javascript"></script>
                                    <link rel="stylesheet" type="text/css" href="{{asset('org/uploadify/uploadify.css')}}">
                                    <input id="file_upload" name="file_upload" type="file" multiple="true">
                                    <script type="text/javascript">
                                        <?php $timestamp = time();?>
                                        $(function() {
                                            $('#file_upload').uploadify({
                                                'buttonText' : '圖片上傳',
                                                'formData'     : {
                                                    'timestamp' : '<?php echo $timestamp;?>',
                                                    '_token'     : '{{csrf_token()}}'
                                                },
                                                'swf'      : '{{asset("org/uploadify/uploadify.swf")}}',
                                                'uploader' : '{{url("admin/upload")}}',
                                                'onUploadSuccess' : function(file, data, response) {
                                                    var data = jQuery.parseJSON(data);
                                                    $('#article_thumb_img').attr('src','/admin/storage/'+data.newName);
                                                    $('#art_thumb').val('admin/storage/'+data.newName);
                                                }
                                            });
                                        });
                                    </script>
                                    <span class="help-block m-b-none">建議尺寸100x100</span>
                                </div>
                                <img src="" id="article_thumb_img" class="col-sm-2 control-label" style="width: 160px; height: 100px;"></img>
                                <input type="hidden" id="art_thumb" name="art_thumb" value="">
                            </div>
                            {{--<div class="hr-line-dashed"></div>--}}
                            {{--<div class="form-group"><label class="col-sm-2 control-label">首頁列表圖片</label>--}}

                                {{--<div class="col-sm-10"><input type="file" class="form-control" name=""><span class="help-block m-b-none">建議尺寸100x100</span></div>--}}
                            {{--</div>--}}

                            {{--<div class="hr-line-dashed"></div>--}}
                            {{--<div class="form-group"><label class="col-sm-2 control-label">搜尋列表圖片</label>--}}

                                {{--<div class="col-sm-10"><input type="file" class="form-control" name=""><span class="help-block m-b-none">建議尺寸100x100</span></div>--}}
                            {{--</div>--}}

                            {{--<div class="hr-line-dashed"></div>--}}
                            {{--<div class="form-group"><label class="col-sm-2 control-label">側欄列表圖片</label>--}}

                                {{--<div class="col-sm-10"><input type="file" class="form-control" name=""><span class="help-block m-b-none">建議尺寸100x100</span></div>--}}
                            {{--</div>--}}

                            {{--<div class="hr-line-dashed"></div>--}}
                            {{--<div class="form-group"><label class="col-sm-2 control-label">針對桌機版首頁圖片</label>--}}

                                {{--<div class="col-sm-10"><input type="file" class="form-control" name=""><span class="help-block m-b-none">建議尺寸100x100,限PNG檔案格式</span></div>--}}
                            {{--</div>--}}

                            <div class="hr-line-dashed"></div>
                            <div class="form-group"><label class="col-sm-2 control-label">文章內容</label>
                                <script src="https://cdn.ckeditor.com/ckeditor5/1.0.0-alpha.1/classic/ckeditor.js"></script>
                                <div class="col-sm-10">
                                    <span class="help-block m-b-none">
                                        <textarea name="art_content" id="editor" rows="30"></textarea>
                                    </span>
                                </div>
                                <script>
                                    //這玩意兒還有待研究...
                                    ClassicEditor
                                        .create( document.querySelector( '#editor' ), {
                                            toolbar: [ 'headings', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote' ],
                                            heading: {
                                                options: [
                                                    { modelElement: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                                                    { modelElement: 'heading1', viewElement: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                                                    { modelElement: 'heading2', viewElement: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' }
                                                ]
                                            }
                                        })
                                        .catch(error => {
                                            console.log(error);
                                        });
                                </script>
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


