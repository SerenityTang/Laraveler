@extends('pc.layouts.app')
@section('title')
    发布博客 | @parent
@stop
@section('css')
    <link rel="stylesheet" href="{{ url('css/blog/default.css') }}">
    {{--<link rel="stylesheet" href="{{ url('libs/wangEditor-fullscreen/wangEditor-fullscreen-plugin.css') }}">--}}
    <link rel="stylesheet" href="{{ url('libs/summernote/dist/summernote.css') }}">
    <link rel="stylesheet" href="{{ asset('libs/bootstrap-select/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ url('libs/amazeui-tagsinput/amazeui.tagsinput.css') }}">
@stop
@section('style')
    <style type="text/css">
        .icon {
            width: 1em;
            height: 1em;
            vertical-align: -0.15em;
            fill: currentColor;
            overflow: hidden;
        }
    </style>
@stop
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default main-content">
                    <h4 class="big-title"><i class="iconfont icon-fabu1"></i>发布博客</h4>
                    <form class="form-horizontal" role="form" method="post" action="{{ url('blog/store') }}">
                        <input type="hidden" id="editor_token" name="_token" value="{{ csrf_token() }}"/>
                        <input type="hidden" id="desc" name="desc" value="">
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">博客来源</label>
                            <div class="col-sm-9" style="width: 20%;">
                                <select id="source" name="source" class="form-control selectpicker">
                                    <option value="0">请选择博客来源</option>
                                    <option value="1">原创</option>
                                    <option value="2">转载</option>
                                    <option value="3">翻译</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 source-display">
                                    <input type="text" class="form-control source-name text-extra" id="source_name"
                                           name="source_name" placeholder="请输入博客来源名称">
                                    <input type="text" class="form-control source-link text-extra" id="source_link"
                                           name="source_link" placeholder="请输入博客原文链接">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">博客标题</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control text-extra" id="blog_title" name="blog_title"
                                       placeholder="请输入博客标题">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">博客简介</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" id="blog_intro" name="blog_intro" rows="3"
                                          placeholder="请输入您的博客简介......"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">博客内容</label>
                            {{--<div id="editor" class="col-sm-9"><p style="color: #ccc;">请输入您的博客内容......</p></div>--}}
                            <div id="blog_summernote" class="col-sm-9"></div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">博客分类</label>
                            <div class="col-sm-9" style="width: 20%;">
                                <select id="bcategory_id" name="bcategory_id" class="form-control selectpicker">
                                    <option value="0">请选择博客分类</option>
                                    <option value="1">Laravel</option>
                                    <option value="2">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group tag-form-group">
                            <label for="" class="col-sm-2 control-label">博客标签</label>
                            <div class="col-sm-4 blog-tag">
                                <input type="text" class="form-control text-extra" id="tags" name="tags"
                                       data-role="tagsinput">
                                <div class="col-sm-5 tag-drop-tip">
                                    <ul>
                                        @foreach($tags as $tag)
                                            <li class="tip" data-tag-id="{{ $tag->id }}"
                                                data-tag-category="{{ $tag->tcategory_id }}">
                                                {{ $tag->name }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <a href="#" id="tishi" class="btn" rel="popover" data-content="请选择或输入心仪标签按空格键键入">
                                <svg class="icon" aria-hidden="true">
                                    <use xlink:href="#icon-tishi"></use>
                                </svg>
                            </a>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-8">
                                <button type="submit" class="btn btn-success btn-lg btn-save">发布博客</button>
                                <button type="button" class="btn btn-success btn-lg btn-draft">保存草稿</button>
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
    <script type="text/javascript" src="{{ url('libs/summernote/dist/summernote.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('libs/summernote/dist/lang/summernote-zh-CN.js') }}"></script>
    <script src="{{ asset('libs/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('libs/bootstrap-select/js/i18n/defaults-zh_CN.js') }}"></script>
    <script type="text/javascript" src="{{ url('libs/amazeui-tagsinput/amazeui.tagsinput.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('css/iconfont/iconfont.js') }}"></script>
    <script>
        $(function () {
            $('.blog-tag .am-tagsinput span .tt-menu').addClass('tt-drop-menu');

            $("#tishi").popover({placement: 'top', trigger: 'hover'});   //标签图标鼠标经过提示

            $('.blog-tag .am-tagsinput input').focus(function () {
                var tip = $(this).val();
                if (tip == '') {
                    $('.tag-drop-tip').slideDown();
                } else {
                    $('.tag-drop-tip').slideUp();
                }
            });
            $('.blog-tag .am-tagsinput input').blur(function () {
                $('.tag-drop-tip').slideUp();
            });
            $('.blog-tag .am-tagsinput input').keydown(function () {
                $('.tag-drop-tip').slideUp();
            });
        });
    </script>
    <script>
        //$('#tags').tagsinput();与data-am-tagsinput一样初始化作用
        var tags = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            //prefetch: '{{ url('/search/tags') }}/%QUERY',
            remote: {
                url: '{{ url('/search/tags') }}/%QUERY',
                wildcard: '%QUERY'
            }
        });

        tags.initialize();

        $('#tags').tagsinput({
            maxTags: 5,
            maxChars: 8,
            confirmKeys: [188, 32],
            trimValue: true,
            itemValue: 'value',
            itemText: 'text',
            typeaheadjs: {
                name: 'tags',
                displayKey: 'text',
                source: tags.ttAdapter()
            }
        });
        //从下拉tag列表选择并显示
        $(".tag-drop-tip ul li.tip").click(function (event) {
            var tag_value = $(this).data('tag-id');
            var tag_text = $(this).html();
            var tag_category = $(this).data('tag-category');
            $('#tags').tagsinput('add', {"value": tag_value, "text": tag_text, "category": tag_category});
        });
    </script>
    <script>
        $(function () {
            //保存发布博客
            $('.btn-save').click(function () {
                var source = $('#source').val();
                var source_name = $('#source_name').val();
                var source_link = $('#source_link').val();
                var blog_title = $('#blog_title').val();
                var blog_intro = $('#blog_intro').val();
                var desc = $('#desc').val();
                var bcategory_id = $('#bcategory_id').val();
                var tags = $('#tags').val();

                if (source == 0) {
                    layer.msg('请选择一个博客来源喔(⊙o⊙)', {
                        icon: 2,
                        time: 2000,
                    });
                    return false;
                }
                if (source == 2 || source == 3) {
                    if (source_name == '') {
                        layer.msg('博客来源名称不可为空喔(⊙o⊙)', {
                            icon: 2,
                            time: 2000,
                        });
                        return false;
                    }
                    if (source_link == '') {
                        layer.msg('博客原文链接不可为空喔(⊙o⊙)', {
                            icon: 2,
                            time: 2000,
                        });
                        return false;
                    }
                }
                if (blog_title == '') {
                    layer.msg('博客标题不可为空喔(⊙o⊙)', {
                        icon: 2,
                        time: 2000,
                    });
                    return false;
                }
                if (blog_intro != '') {
                    if (blog_intro.length > 191) {
                        layer.msg('博客简介有点长，请简短概括喔(⊙o⊙)', {
                            icon: 2,
                            time: 2000,
                        });
                        return false;
                    }
                }
                if (desc == '') {
                    layer.msg('博客内容不可为空喔(⊙o⊙)', {
                        icon: 2,
                        time: 2000,
                    });
                    return false;
                }
                if (bcategory_id == 0) {
                    layer.msg('请选择一个博客分类喔(⊙o⊙)', {
                        icon: 2,
                        time: 2000,
                    });
                    return false;
                }
                if (tags == '') {
                    layer.msg('博客标签不可为空喔(⊙o⊙)', {
                        icon: 2,
                        time: 2000,
                    });
                    return false;
                }
            });

            //保存博客草稿
            $('.btn-draft').click(function () {
                var source = $('#source').val();
                var source_name = $('#source_name').val();
                var source_link = $('#source_link').val();
                var blog_title = $('#blog_title').val();
                var desc = $('#desc').val();
                var bcategory_id = $('#bcategory_id').val();
                var tags = $('#tags').val();

                if (source == 0) {
                    layer.msg('请选择一个博客来源喔(⊙o⊙)', {
                        icon: 2,
                        time: 2000,
                    });
                    return false;
                }
                if (source == 2 || source == 3) {
                    if (source_name == '') {
                        layer.msg('博客来源名称不可为空喔(⊙o⊙)', {
                            icon: 2,
                            time: 2000,
                        });
                        return false;
                    }
                    if (source_link == '') {
                        layer.msg('博客原文链接不可为空喔(⊙o⊙)', {
                            icon: 2,
                            time: 2000,
                        });
                        return false;
                    }
                }
                if (blog_title == '') {
                    layer.msg('博客标题不可为空喔(⊙o⊙)', {
                        icon: 2,
                        time: 2000,
                    });
                    return false;
                }
                if (desc == '') {
                    layer.msg('博客内容不可为空喔(⊙o⊙)', {
                        icon: 2,
                        time: 2000,
                    });
                    return false;
                }
                if (bcategory_id == 0) {
                    layer.msg('请选择一个博客分类喔(⊙o⊙)', {
                        icon: 2,
                        time: 2000,
                    });
                    return false;
                }
                if (tags == '') {
                    layer.msg('博客标签不可为空喔(⊙o⊙)', {
                        icon: 2,
                        time: 2000,
                    });
                    return false;
                }

                $.ajax({
                    url: "{{ url('blog/store_draft') }}",
                    type: "post",
                    dataType: "json",
                    data: {
                        _token: '{{csrf_token()}}',
                        'blog_title': $('#blog_title').val(),
                        'blog_intro': $('#blog_intro').val(),
                        'desc': $('#desc').val(),
                        'bcategory_id': $('#bcategory_id').val(),
                        'source': $('#source').val(),
                        'source_name': $('#source_name').val(),
                        'source_link': $('#source_link').val(),
                        'tags': $('#tags').val(),
                    },
                    cache: false, //不允许有缓存
                    success: function (res) {
                        if (res.code == 501) {
                            layer.msg(res.message, {
                                icon: 6,
                                time: 2000,
                                end: function () {
                                    location.href = '{{ url("/blog") }}';
                                }
                            });
                        } else if (res.code == 502) {
                            layer.msg(res.message, {
                                icon: 2,
                                time: 2000,
                                end: function () {
                                    location.href = '{{ url("/blog") }}';
                                }
                            });
                        }
                    },
                    error: function () {
                        layer.msg('系统错误！', {
                            icon: 2,
                            time: 2000,
                            end: function () {
                                location.href = '{{ url("/blog") }}';
                            }
                        });
                    }
                });
            });
        });
    </script>
    <script>    //下拉菜单
        $(function () {
            $('.selectpicker').selectpicker();
        });
    </script>
    <script>
        $(document).ready(function () {
            $('#blog_summernote').summernote({
                lang: 'zh-CN',
                width: 845,
                height: 200,
                placeholder: '请输入您的博客内容......',
                dialogsFade: true, //淡入淡出
                toolbar: [
                    ['para', ['style']],
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['height', ['height']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['picture', 'link', 'table']],
                    ['misc', [/*'undo', 'redo', */'codeview', 'fullscreen', 'help']],
                ],
                callbacks: {
                    onChange: function (contents, $editable) {
                        var code = $(this).summernote("code");
                        $("#desc").val(code);
                    },
                    onImageUpload: function (files) {
                        upload_image(files[0], 'blog_summernote', 'blog');
                    }
                }
            });

            $('.note-editor').addClass('panel-extra');
            $('.modal .modal-dialog .modal-content .modal-header, .modal .modal-dialog .modal-content .modal-body, .modal .modal-dialog .modal-content .checkbox input').addClass('modal-extra');
            $('.modal .modal-dialog .modal-content .modal-body input.note-image-input').addClass('form-control');
            //富文本工具栏标题按钮下拉菜单
            $('ul.dropdown-style').css('min-width', '150px');
            $('ul.dropdown-style li a').css('padding', '0');
        });
    </script>
    <script>
        $(function () {
            $('#source').change(function () {
                var source_type = $(this).val();
                if (source_type == 0 || source_type == 1) {
                    $('.main-content .source-display').fadeOut();
                } else {
                    $('.main-content .source-display').fadeIn();
                }
            });
        });
    </script>
    {{--<script type="text/javascript">
        var E = window.wangEditor;
        var editor = new E('#editor');
        // 或者 var editor = new E( document.getElementById('editor') )

        // 自定义菜单配置
        editor.customConfig.menus = [
            //'head',  // 标题
            'bold',  // 粗体
            'italic',  // 斜体
            'underline',  // 下划线
            'strikeThrough',  // 删除线
            'foreColor',  // 文字颜色
            //'backColor',  // 背景颜色
            'link',  // 插入链接
            'list',  // 列表
            'justify',  // 对齐方式
            'quote',  // 引用
            'emoticon',  // 表情
            'image',  // 插入图片
            'table',  // 表格
            //'video',  // 插入视频
            'code',  // 插入代码
            'undo',  // 撤销
            'redo'  // 重复
        ];
        editor.customConfig.zIndex = 100;  // 配置z-index
        editor.customConfig.uploadImgServer = '{{ url('') }}';      // 配置服务器端地址
        editor.customConfig.uploadImgMaxSize = 2 * 1024 * 1024;   // 配置上传图片大小
        editor.customConfig.uploadImgTimeout = 3000;              // 配置上传超时时间
        editor.create();
        E.fullscreen.init('#editor');
    </script>--}}
@stop