@extends('pc.layouts.app')
@section('title')
    {{ $question->title }} | @parent
@stop
@section('css')
    <link rel="stylesheet" href="{{ url('css/question/default.css') }}">
    <link rel="stylesheet" href="{{ url('libs/summernote/dist/summernote.css') }}">
    <link rel="stylesheet" href="{{ url('libs/zeroModal/zeroModal.css') }}">
@stop

@section('style')
    <style type="text/css">
        .icon {
            width: 1em; height: 1em;
            vertical-align: -0.15em;
            fill: currentColor;
            overflow: hidden;
        }
    </style>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-9 ques-show-main">
                <div class="panel">
                    <div class="panel-heading panel-heading-extra">
                        <div class="ques-show-top">
                            <h1 class="ques-show-title">{{ $question->title }}</h1>
                            <a class="author" href="{{ url('user/'.$question->user->personal_domain) }}">
                                <img src="{{ App\Helpers\Helpers::get_user_avatar($question->user_id, 'small') }}" class="avatar-24" alt="{{ $question->user->username }}">
                                <span class="username">{{ $question->user->username }}</span>
                            </a>
                            <span class="title-extra">发布于</span>
                            <span class="time" title="{{ $question->created_at }}">
                                {!! $question->created_at !!}
                            </span>

                            <div class="ques-operate">
                                <span title="浏览数"><i class="iconfont icon-icon"></i>{{$question->view_count}}</span>
                                <span>|</span>
                                <span title="投票数"><i class="iconfont icon-toupiao"></i>{{$question->vote_count}}</span>
                                <span>|</span>
                                <span title="回答数"><i class="iconfont icon-tubiaopinglunshu"></i>{{$question->answer_count}}</span>
                                <span>|</span>
                                <span title="关注数"><i class="iconfont icon-guanzhu"></i>{{$question->attention_count}}</span>
                                <span>|</span>
                                <span title="收藏数"><i class="iconfont icon-shoucang1"></i>{{$question->collection_count}}</span>
                            </div>
                        </div>

                        {!! $question->description !!}

                        <div class="operation-icon">
                            @if(Auth::check() && Auth::user()->id == $question->user_id)
                                <a href="{{ url('/question/show_edit/'.$question->id) }}" class="edit-icon" title="编辑">
                                    <i class="iconfont icon-bianji"></i>编辑
                                </a>
                                <a href="javascript:void(0);" class="delete-icon" title="删除" data-question-id="{{ $question->id }}">
                                    <i class="iconfont icon-weibiaoti544" style="font-size: 20px;position: relative;top: 1px;"></i>删除
                                </a>
                                <a href="{{ url('') }}" title="追加悬赏">
                                    <i class="iconfont icon-dashangzonge"></i>追加悬赏
                                </a>
                            @endif
                            <a href="{{ url('') }}">
                                <i class="iconfont icon-web-icon-" style="font-size: 21px;position: relative;top: 2px;" title="邀请回答"></i>邀请回答
                            </a>
                        </div>

                        @include('pc.question.parts.best_answer')

                    </div>

                    <div class="panel-body">
                        <div class="answer-top">
                            <h3 class="answer-count">{{ $question->answer_count }} 个回答</h3>
                            <div class="btn-group answer-rank" data-toggle="buttons">
                                <label class="btn default-sort active" data-question-id="{{ $question->id }}" data-sort="default">
                                    <input type="radio" name="default" id="default"> 默认
                                </label>
                                <label class="btn time-sort" data-question-id="{{ $question->id }}" data-sort="time">
                                    <input type="radio" name="time" id="time"> 时间
                                </label>
                                <label class="btn support-sort" data-question-id="{{ $question->id }}" data-sort="support">
                                    <input type="radio" name="support" id="support"> 支持
                                </label>
                            </div>
                        </div>

                        <div class="answer-content">
                            @include('pc.question.parts.answer')
                        </div>
                    </div>

                    @if(Auth::guest())
                        <div class="panel-footer-tip">
                            <div class="login-tip">
                                <p>您需要登录才可以回答问题噢！！！<a href="{{ url('login') }}">登录</a> or <a href="{{ url('register') }}">注册</a></p>
                            </div>
                        </div>
                    @else
                        @if($question->user_id != Auth::user()->id && !Auth::user()->isAnswer($question->id))
                            <div class="panel-footer">
                                <form method="post" action="{{ url('answer/store') }}">
                                    <input type="hidden" id="editor_token" name="_token" value="{{ csrf_token() }}" />
                                    <input type="hidden" id="answer-content" name="answer-content" value="">
                                    <input type="hidden" id="question_id" name="question_id" value="{{ $question->id }}">
                                    <div id="ques_comment_summernote" class="col-sm-9"></div>
                                    <div class="ques_comment_bottom">
                                        <button type="submit" class="btn btn-reply">提交回答</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <div class="col-xs-12 col-md-3">
                <div class="list-side-top">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <button type="button" class="btn btn-vote" data-question-id="{{ $question->id }}" data-question-uid="{{ $question->user_id }}" data-question-curruid="{{ Auth::check() ? Auth::user()->id : 0 }}">
                                <i class="iconfont icon-toupiao1"></i>
                                @if(\App\Helpers\Helpers::vote($question->id, 'Question') != null)
                                    <span class="btn-vote-text">已投票</span>
                                @else
                                    <span class="btn-vote-text">投票</span>
                                @endif
                            </button>
                            <span class="vote-count">{{ $question->vote_count }} 投票</span>
                        </li>
                        <li class="list-group-item">
                            <button type="button" class="btn btn-attention" data-question-id="{{ $question->id }}" data-question-uid="{{ $question->user_id }}" data-question-curruid="{{ Auth::check() ? Auth::user()->id : 0 }}">
                                <i class="iconfont icon-guanzhu"></i>
                                @if(\App\Helpers\Helpers::attention($question->id, 'Question') != null)
                                    <span class="btn-attention-text">已关注</span>
                                @else
                                    <span class="btn-attention-text">关注</span>
                                @endif
                            </button>
                            <span class="attention-count">{{ $question->attention_count }} 关注</span>
                        </li>
                        <li class="list-group-item">
                            <button type="button" class="btn btn-collection" data-question-id="{{ $question->id }}" data-question-uid="{{ $question->user_id }}" data-question-curruid="{{ Auth::check() ? Auth::user()->id : 0 }}">
                                <i class="iconfont icon-shoucang"></i>
                                @if(\App\Helpers\Helpers::collection($question->id, 'Question') != null)
                                    <span class="btn-collection-text">已收藏</span>
                                @else
                                    <span class="btn-collection-text">收藏</span>
                                @endif
                            </button>
                            <span class="collection-count">{{ $question->collection_count }} 收藏</span>
                        </li>
                    </ul>
                </div>

                <div class="list-side-other">
                    <div class="panel panel-default other-panel">
                        <div class="panel-heading">
                            <h3 class="other-ques"><i class="iconfont icon-qita other-icon"></i>{{ $question->user->username }} 的其它问答</h3>
                        </div>
                        <div class="panel-body">
                            <ul class="list-group list-others">
                                @if($other_ques->isEmpty())
                                    <p class="list-other-empty">{{ $question->user->username }} 暂无其它问答</p>
                                @else
                                    @foreach($other_ques as $other_que)
                                        <li>
                                            <a href="{{ url('question/show/' . $other_que->id) }}" title="{{ $other_que->title }}">{{ str_limit($other_que->title, 30) }}</a>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="list-side-related">
                    <div class="panel panel-default related-panel">
                        <div class="panel-heading">
                            <h3 class="related-ques"><i class="iconfont icon-changjianwentixiangguanwenti related-icon"></i>相关问答</h3>
                        </div>
                        <div class="panel-body">
                            <ul class="list-group correlation-list">
                                @foreach($correlation_ques as $correlation)
                                    @if($question->user_id != $correlation->user_id)
                                        <li class="correlation-ques">
                                            <a href="{{ url('question/show/' . $correlation->id) }}" title="{{ $correlation->title }}">{{ str_limit($correlation->title, 30) }}</a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('footer')
    <script src="{{ asset('libs/jquery-timeago/jquery.timeago.js') }}"></script>
    <script src="{{ asset('libs/jquery-timeago/locales/jquery.timeago.zh-CN.js') }}"></script>
    <script type="text/javascript" src="{{ url('libs/summernote/dist/summernote.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('libs/summernote/dist/lang/summernote-zh-CN.js') }}"></script>
    <script type="text/javascript" src="{{ url('libs/zeroModal/zeroModal.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('css/iconfont/iconfont.js') }}"></script>
    <script>
        $(".time").timeago();
    </script>
    <script>
        //如相关问答为空，插入提示
        $(function () {
            if ($('.list-side-related ul.correlation-list').children().length === 0) {
                $('.list-side-related ul.correlation-list').html('<p class="list-other-empty">暂无相关问答</p>');
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#ques_comment_summernote').summernote({
                lang: 'zh-CN',
                width: 825,
                height: 180,
                placeholder:'您对此问题有何见解？赶紧回复探讨吧^_^',
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
                    onChange:function (contents, $editable) {
                        var code = $(this).summernote("code");
                        $("#answer-content").val(code);
                    },
                    onImageUpload: function(files) {
                        upload_editor_image(files[0], 'question_summernote', 'question');
                    }
                }
            });

            $('.note-editor').addClass('ques-panel-extra');
            $('.modal .modal-dialog .modal-content .modal-header, .modal .modal-dialog .modal-content .modal-body, .modal .modal-dialog .modal-content .checkbox input').addClass('modal-extra');
            $('.modal .modal-dialog .modal-content .modal-body input.note-image-input').addClass('form-control');
            $('.ques-show-main .panel-footer .note-editor .note-statusbar').addClass('note-statusbar-extra');
        });
    </script>
    <script>
        //查看问答回答的评论
        $(document).ready(function(){
            $('.comment-icon').click(function () {
                var icon = $(this);
                var entity_id = $(this).data('entity_id');
                var entity_type = $(this).data('entity_type');
                $.get('/comment/'+entity_id+'/'+entity_type, function(html){
                    icon.parents('.media').find('.media-comment').append(html);
                });

                icon.parents('.media').find('.media-comment').toggle();

                icon.parents('.media').find('.media-comment').empty();
            });
        });
    </script>
    <script>
        //问答删除
        $(function () {
            $('.delete-icon').click(function () {
                var question_id = $(this).data('question-id');
                zeroModal.confirm("确定删除问题吗？", function() {
                    $.ajax({
                        url : "{{url('/question/destroy/[id]')}}".replace('[id]', question_id),
                        data : {
                            _token: '{{csrf_token()}}',
                        },
                        dataType : "json",
                        type : "POST",
                        success : function (res) {
                            if(res.code == 701){
                                layer.msg(res.message, {
                                    icon: 6,//提示的样式
                                    time: 2000, //2秒关闭（如果不配置，默认是3秒）//设置后不需要自己写定时关闭了，单位是毫秒
                                    end : function(){
                                        location.href='{{ url("/question") }}';
                                    }
                                });
                            } else if (res.code == 702) {
                                zeroModal.error(res.message);
                            }

                        },
                        error : function () {
                            zeroModal.error('系统错误！');
                        }
                    });
                });
            });
        });
    </script>
    <script>
        //问答回答采纳
        $(function () {
            $('.adopt-icon').click(function () {
                var answer_id = $(this).data('answer-id');
                zeroModal.confirm("确定采纳该回答作为此问题最佳答案吗？", function() {
                    $.ajax({
                        url : "{{ url('/answer/adopt/[id]') }}".replace('[id]', answer_id),
                        data : {
                            _token: '{{csrf_token()}}',
                        },
                        dataType : "json",
                        type : "POST",
                        success : function (res) {
                            if(res.code == 703){
                                layer.msg(res.message, {
                                    icon: 6,
                                    time: 2000,
                                });
                                //获取最佳答案，通过ajax获取并插入指定位置
                                $.get('/question/'+answer_id+'/show_best_answer', function(html){
                                    $('.ques-show-main .panel-heading').append(html);
                                    $('.adopt-icon').css('display', 'none');
                                });
                            } else {
                                zeroModal.error('系统错误！');
                            }
                        },
                        error : function () {
                            zeroModal.error('系统错误！');
                        }
                    });
                });
            });
        });
    </script>
    <script>
        //回答支持
        $(function () {
            $('.support-icon').click(function () {
                var support_icon = $(this);
                var answer_id = $(this).data('answer-id');
                var answer_uid = $(this).data('answer-uid');
                var answer_curruid = $(this).data('answer-curruid');
                var support_count = parseInt($(this).find('.support-count').html());
                @if(!\Auth::check())
                    window.location.href = '{{ url('/login') }}';
                @else
                    if (answer_uid != answer_curruid) {
                        $.get('/answer/support/'+answer_id, function (message) {
                            if (message == 'support') {
                                support_count++;
                                //两种写法，需要把$(this)传给变量
                                support_icon.parents('.media-footer').find('.support-count').html(support_count);
                                //support_icon.find('.support-count').html(support_count);
                                support_icon.parents('.media-footer').find('.support-icon').addClass('active');
                            } else if (message == 'unsupport') {
                                support_count--;
                                support_icon.parents('.media-footer').find('.support-count').html(support_count);
                                //support_icon.find('.support-count').html(support_count);
                                support_icon.parents('.media-footer').find('.support-icon').removeClass('active');
                            }
                        });
                    } else {
                        layer.msg('不能支持自己的回答^_^',{icon: 7});
                    }
                @endif
            });
        });

        //回答反对
        $(function () {
            $('.oppose-icon').click(function () {
                var oppose_icon = $(this);
                var answer_id = $(this).data('answer-id');
                var answer_uid = $(this).data('answer-uid');
                var answer_curruid = $(this).data('answer-curruid');
                var oppose_count = parseInt($(this).find('.oppose-count').html());
                @if(!\Auth::check())
                    window.location.href = '{{ url('/login') }}';
                @else
                    if (answer_uid != answer_curruid) {
                        $.get('/answer/oppose/'+answer_id, function (message) {
                            if (message == 'opposition') {
                                oppose_count++;
                                //两种写法，需要把$(this)传给变量
                                oppose_icon.parents('.media-footer').find('.oppose-count').html(oppose_count);
                                //oppose_icon.find('.oppose_count').html(oppose_count);
                                oppose_icon.parents('.media-footer').find('.oppose-icon').addClass('active');
                            } else if (message == 'unopposition') {
                                oppose_count--;
                                oppose_icon.parents('.media-footer').find('.oppose-count').html(oppose_count);
                                //oppose_icon.find('.oppose_count').html(oppose_count);
                                oppose_icon.parents('.media-footer').find('.oppose-icon').removeClass('active');
                            }
                        });
                    } else {
                        layer.msg('不能反对自己的回答^_^',{icon: 7});
                    }
                @endif
            });
        });

        //问答投票
        $(function () {
            $('.btn-vote').click(function () {
                var question_id = $(this).data('question-id');
                var question_uid = $(this).data('question-uid');
                var question_curruid = $(this).data('question-curruid');
                var vote_count = parseInt($('.vote-count').html());
                @if(!\Auth::check())
                    window.location.href = '{{ url('/login') }}';
                @else
                    if (question_uid != question_curruid) {
                        $.get('/question/vote/'+question_id, function (message) {
                            if (message == 'vote') {
                                vote_count++;
                                $('.vote-count').html(vote_count+' 投票');
                                $('.btn-vote').html('<i class="iconfont icon-toupiao1"></i>'+'已投票');
                            } else if (message == 'unvote') {
                                vote_count--;
                                $('.vote-count').html(vote_count+' 投票');
                                $('.btn-vote').html('<i class="iconfont icon-toupiao1"></i>'+'投票');
                            }
                        });
                    } else {
                        layer.tips('不能投票自己的问答^_^', '.btn-vote', {
                            tips: [1, '#22d7bb'], //配置颜色
                        });
                    }
                @endif
            });
        });

        //问答关注
        $(function () {
            $('.btn-attention').click(function () {
                var question_id = $(this).data('question-id');
                var question_uid = $(this).data('question-uid');
                var question_curruid = $(this).data('question-curruid');
                var attention_count = parseInt($('.attention-count').html());
                @if(!\Auth::check())
                    window.location.href = '{{ url('/login') }}';
                @else
                    if (question_uid != question_curruid) {
                        $.get('/question/attention/'+question_id, function (message) {
                            if (message == 'attention') {
                                attention_count++;
                                $('.attention-count').html(attention_count+' 关注');
                                $('.btn-attention').html('<i class="iconfont icon-toupiao1"></i>'+'已关注');
                            } else if (message == 'unattention') {
                                attention_count--;
                                $('.attention-count').html(attention_count+' 关注');
                                $('.btn-attention').html('<i class="iconfont icon-toupiao1"></i>'+'关注');
                            }
                        });
                    } else {
                        layer.tips('不能关注自己的问答^_^', '.btn-attention', {
                            tips: [4, '#22d7bb'], //配置颜色
                        });
                    }
                @endif
            });
        });

        //问答收藏
        $(function () {
            $('.btn-collection').click(function () {
                var question_id = $(this).data('question-id');
                var question_uid = $(this).data('question-uid');
                var question_curruid = $(this).data('question-curruid');
                var collection_count = parseInt($('.collection-count').html());
                @if(!\Auth::check())
                    window.location.href = '{{ url('/login') }}';
                @else
                    if (question_uid != question_curruid) {
                        $.get('/question/collection/'+question_id, function (message) {
                            if (message == 'collection') {
                                collection_count++;
                                $('.collection-count').html(collection_count+' 收藏');
                                $('.btn-collection').html('<i class="iconfont icon-toupiao1"></i>'+'已收藏');
                            } else if (message == 'uncollection') {
                                collection_count--;
                                $('.collection-count').html(collection_count+' 收藏');
                                $('.btn-collection').html('<i class="iconfont icon-toupiao1"></i>'+'收藏');
                            }
                        });
                    } else {
                        layer.tips('不能收藏自己的问答^_^', '.btn-collection', {
                            tips: [3, '#22d7bb'], //配置颜色
                        });
                    }
                @endif
            });
        });
    </script>

    <script>
        //问答的回复按条件显示
        $(function () {
            $('.default-sort').click(function () {
                var question_id = $(this).data('question-id');
                var sort = $(this).data('sort');
                $.get('/answer/sort_show/'+question_id+'/'+sort, function (html) {
                    $('.answer-content').empty();
                    $('.answer-content').append(html);
                })
            });

            $('.time-sort').click(function () {
                var question_id = $(this).data('question-id');
                var sort = $(this).data('sort');
                $.get('/answer/sort_show/'+question_id+'/'+sort, function (html) {
                    $('.answer-content').empty();
                    $('.answer-content').append(html);
                })
            });

            $('.support-sort').click(function () {
                var question_id = $(this).data('question-id');
                var sort = $(this).data('sort');
                $.get('/answer/sort_show/'+question_id+'/'+sort, function (html) {
                    $('.answer-content').empty();
                    $('.answer-content').append(html);
                })
            });
        });
    </script>
@stop