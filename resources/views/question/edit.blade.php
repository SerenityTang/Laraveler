@extends('layouts.app')
@section('title')
    编辑问答 | @parent
@stop
@section('css')
    <link rel="stylesheet" href="{{ url('css/question/default.css') }}">
    {{--<link rel="stylesheet" href="{{ url('libs/wangEditor-fullscreen/wangEditor-fullscreen-plugin.css') }}">--}}
    <link rel="stylesheet" href="{{ url('libs/summernote/summernote.css') }}">
    <link rel="stylesheet" href="{{ asset('libs/bootstrap-select/css/bootstrap-select.min.css') }}">
@stop
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default main-content">
                    <h4><i class="iconfont icon-fabu1"></i>编辑问答</h4>
                    <form class="form-horizontal" role="form" method="post" action="{{ url('question/edit/'.$question->id) }}">
                        <input type="hidden" id="editor_token" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" id="description" name="description" value="">
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">问题标题</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control text-extra" id="question_title" name="question_title" value="{{ $question->title }}" placeholder="请简要概括您的问题，并以问号结束 ^_^">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">问题描述</label>
                            {{--<div id="editor" class="col-sm-9"><p style="color: #ccc;">请输入您的问题描述......</p></div>--}}
                            <div id="question_summernote" class="col-sm-9">{!! $question->description !!}</div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">问题分类</label>
                            <div class="col-sm-9" style="width: 20%;">
                                <select id="qcategory_id" name="qcategory_id" class="form-control selectpicker">
                                    <option value="0" @if($question->qcategory_id == 0) selected @endif>请选择问题分类</option>
                                    <option value="1" @if($question->qcategory_id == 1) selected @endif>Laravel</option>
                                    <option value="2" @if($question->qcategory_id == 2) selected @endif>Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-8">
                                <button type="submit" class="btn btn-success btn-lg btn-save">编辑问题</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('footer')
    {{--<script type="text/javascript" src="{{ url('libs/wangEditor/release/wangEditor.js') }}"></script>
    <script type="text/javascript" src="{{ url('libs/wangEditor-fullscreen/wangEditor-fullscreen-plugin.js') }}"></script>--}}
    <script type="text/javascript" src="{{ url('libs/summernote/summernote.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('libs/summernote/lang/summernote-zh-CN.js') }}"></script>
    <script type="text/javascript" src="{{ url('libs/bootstrap-filestyle/bootstrap-filestyle.min.js') }}"></script>
    <script src="{{ asset('libs/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('libs/bootstrap-select/js/i18n/defaults-zh_CN.js') }}"></script>
    <script>    //下拉菜单
        $(function () {
            $('.selectpicker').selectpicker();
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#question_summernote').summernote({
                lang: 'zh-CN',
                width: 845,
                height: 200,
                placeholder:'请输入您的问题描述......',
                dialogsFade: true, //淡入淡出
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    //['height', ['height']],
                    ['insert', ['picture', 'link']],
                    ['misc', ['undo', 'redo', 'fullscreen']]
                ],
                callbacks: {
                    onChange:function (contents, $editable) {
                        var code = $(this).summernote("code");
                        $("#description").val(code);
                    },
                    onImageUpload: function(files) {
                        upload_editor_image(files[0], 'question_summernote', 'question');
                    }
                }
            });

            $('.note-editor').addClass('panel-extra');
            $('.modal .modal-dialog .modal-content .modal-header, .modal .modal-dialog .modal-content .modal-body, .modal .modal-dialog .modal-content .checkbox input').addClass('modal-extra');
            $('.modal .modal-dialog .modal-content .modal-body input.note-image-input').addClass('form-control');
        });
    </script>
    <script>
        $(":file").filestyle({
            'placeholder' : '图片地址',
            'buttonText': '选择图片',
            'badge': false,
            input: false
        });
    </script>
@stop