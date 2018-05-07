@extends('pc.layouts.app')
@section('title')
    {{ $blog->title }} | @parent
@stop

@section('css')
    <link rel="stylesheet" href="{{ url('css/blog/default.css') }}">
    <link rel="stylesheet" href="{{ url('libs/summernote/dist/summernote.css') }}">
    <link rel="stylesheet" href="{{ url('libs/zeroModal/zeroModal.css') }}">
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-9 blog-show-main">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="blog-show-top">
                            <h1 class="blog-show-title">{{ $blog->title }}</h1>
                            <a class="author" href="{{ url('user/'.$blog->user->personal_domain) }}">
                                <span class="username"><i class="iconfont icon-gaojian-zuozhe"></i>{{ $blog->user->username }}</span>
                            </a>
                            <span class="title-extra"><i class="iconfont icon-shijian time-icon"></i></span>
                            <span class="time" title="{{ $blog->created_at }}">
                                {!! $blog->created_at !!}
                            </span>

                            <div class="blog-operate">
                                <span title="浏览数"><i class="iconfont icon-icon"></i>{{$blog->view_count}}</span>
                                <span>|</span>
                                <span title="点赞数"><i class="iconfont icon-dianzan1"></i>{{$blog->like_count}}</span>
                                <span>|</span>
                                <span title="收藏数"><i class="iconfont icon-shoucang1"></i>{{$blog->favorite_count}}</span>
                                <span>|</span>
                                <span title="评论数"><i class="iconfont icon-tubiaopinglunshu"></i>{{$blog->comment_count}}</span>
                            </div>
                        </div>

                        <p class="intro"><span>简介：</span>{{ $blog->intro }}</p>

                        {!! $blog->description !!}

                        <div class="blog-support">
                            <p class="blog-tip"><span class="ps">PS: </span>如本文对您有帮助，不妨通过一下方式支持一下博主噢 ^_^</p>
                            <div class="handle-bottom">
                                <a href="javascript:void(0)" class="like-btn @if(\App\Helpers\Helpers::support($blog->id, 'Blog', 'like') != null)active @endif" data-blog-id="{{ $blog->id }}" data-blog-uid="{{ $blog->user_id }}" data-blog-curruid="{{ Auth::check() ? Auth::user()->id : 0 }}">
                                    @if(\App\Helpers\Helpers::support($blog->id, 'Blog', 'like') != null)
                                        <i class="iconfont icon-dianzan2"></i>已点赞
                                    @else
                                        <i class="iconfont icon-dianzan2"></i>点赞
                                    @endif
                                </a>
                                <a href="javascript:void(0)" class="favorite-btn @if(\App\Helpers\Helpers::collection($blog->id, 'Blog') != null)active @endif" data-blog-id="{{ $blog->id }}" data-blog-uid="{{ $blog->user_id }}" data-blog-curruid="{{ Auth::check() ? Auth::user()->id : 0 }}">
                                    @if(\App\Helpers\Helpers::collection($blog->id, 'Blog') != null)
                                        <i class="iconfont icon-shoucang"></i>已收藏
                                    @else
                                        <i class="iconfont icon-shoucang"></i>收藏
                                    @endif
                                </a>
                                <a href="javascript:void(0)" class="admire-btn" data-blog-id="{{ $blog->id }}" data-blog-uid="{{ $blog->user_id }}" data-blog-curruid="{{ Auth::check() ? Auth::user()->id : 0 }}">
                                    <i class="iconfont icon-xuanshang"></i>赞赏
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="panel-body">
                        <div class="comment-top">
                            <h3 class="comment-count">{{ $blog->comment_count }} 条评论</h3>
                            <div class="btn-group comment-rank" data-toggle="buttons">
                                <label class="btn default-sort active" data-blog-id="{{ $blog->id }}" data-sort="default">
                                    <input type="radio" name="default" id="default"> 默认
                                </label>
                                <label class="btn time-sort" data-blog-id="{{ $blog->id }}" data-sort="time">
                                    <input type="radio" name="time" id="time"> 时间
                                </label>
                                <label class="btn support-sort" data-blog-id="{{ $blog->id }}" data-sort="support">
                                    <input type="radio" name="support" id="support"> 支持
                                </label>
                            </div>
                        </div>

                        <div class="comment-content">
                            <ul class="list-group comment-con">
                                @foreach($blog->parent_comments as $comment)
                                    <li class="list-group-item comment-item">
                                        <div class="media">
                                            <a class="media-left ans-avatar avatar-40" href="{{ url('user/'.$comment->user->personal_domain) }}">
                                                <img src="{{ App\Helpers\Helpers::get_user_avatar($comment->user_id, 'middle') }}" class="avatar-40" alt="{{ $comment->user->username }}">
                                            </a>
                                            <div class="media-body">
                                                <h4 class="media-heading">
                                                    <a class="author-name" href="{{ url('user/'.$comment->user->personal_domain) }}"><strong>{{ $comment->user->username }}</strong></a>
                                                    <span class="separate">评论于</span>
                                                    <span class="time" title="{{ $comment->created_at }}">
                                                        {!! $comment->created_at !!}
                                                    </span>
                                                </h4>

                                                {!! $comment->content !!}

                                                <div class="operation">
                                                    <span class="oper-left">
                                                        <a href="javascript:void(0)" title="支持" class="like-icon @if(\App\Helpers\Helpers::support($comment->id, 'Comment', 'support') != null)active @endif" data-comment-id="{{ $comment->id }}" data-user-id="{{ $comment->user_id }}" data-curr-uid="{{ Auth::check()?Auth::user()->id:0 }}">
                                                            <i class="iconfont icon-dianzan"></i>
                                                            <span class="like-count @if(\App\Helpers\Helpers::support($comment->id, 'Comment', 'support') != null)active @endif">{{ $comment->support_count }}</span>
                                                        </a>
                                                        @if((Auth::check() ? Auth::user()->id : 0) != $comment->user_id)
                                                            <a href="javascript:void(0)" title="回复" class="reply-icon" data-user-name="{{ $comment->user->username }}" data-user-id="{{ $comment->user_id }}">
                                                                <i class="iconfont icon-icon_reply"></i>
                                                            </a>
                                                        @endif
                                                    </span>

                                                    <span class="oper-right">
                                                        <a href="javascript:void(0)" title="编辑">
                                                            <i class="iconfont icon-bianji1 edit-icon"@if((Auth::check() ? Auth::user()->id : 0) != $comment->user_id) style="display: none;" @endif></i>
                                                        </a>
                                                        <a href="javascript:void(0)" title="删除">
                                                            <i class="iconfont icon-weibiaoti544 delete-icon"@if((Auth::check() ? Auth::user()->id : 0) != $comment->user_id) style="display: none;" @endif data-comment-id="{{ $comment->id }}"></i>
                                                        </a>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="edit-comment">
                                            <form id="edit-comment" method="post" action="{{ url('comment/edit') }}">
                                                <input type="hidden" id="editor_token" name="_token" value="{{ csrf_token() }}" />
                                                <textarea id="edit_comment_con" name="edit_comment_con" class="form-control" rows="2"></textarea>
                                                <div class="comment-part-bottom">
                                                    <button type="button" class="btn edit-cancel">取消</button>
                                                    <button type="button" class="btn edit-comment-btn" data-comment-id="{{ $comment->id }}">更改</button>
                                                </div>
                                            </form>
                                        </div>

                                        <div class="form-group comment-input">
                                            @if($comment->hasChildren())
                                                @foreach($comment->getChildren() as $mutual_comment)
                                                    <div class="mutual-comment">
                                                        <h4 class="media-heading media-heading-extra">
                                                            <a class="author reply-author" href="{{ url('user/'.$mutual_comment->user->personal_domain) }}">{{ $mutual_comment->user->username }}</a>
                                                            <span class="separate">:</span>
                                                            @if($mutual_comment->to_user_id != null)
                                                                <a class="author" href="{{ url('user/'.$mutual_comment->user->personal_domain) }}">@ {{ $mutual_comment->toUser->username }}</a>
                                                            @endif

                                                            {!! $mutual_comment->content !!}
                                                        </h4>
                                                        <span class="time" title="{{ $mutual_comment->created_at }}">
                                                            {!! $mutual_comment->created_at !!}
                                                        </span>
                                                        @if(Auth::check() && $mutual_comment->user_id != Auth::user()->id)
                                                            <span title="回复"><a href="javascript:void(0);" id="sub-reply-icon" class="sub-reply-icon" data-user-id="{{ $mutual_comment->user_id }}" data-user-name="{{ $mutual_comment->user->username }}"><i class="iconfont icon-icon_reply"></i>回复</a></span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            @endif

                                            <div class="comment-form">
                                                <form id="comment-content" method="post" action="{{ url('comment/store') }}">
                                                    <input type="hidden" id="editor_token" name="_token" value="{{ csrf_token() }}" />
                                                    <input type="hidden" id="comment_id" name="comment_id" value="{{ $comment->id }}">
                                                    <input type="hidden" id="to_user" name="to_user" value="">
                                                    <input type="hidden" id="parent_id" name="parent_id" value="{{ $comment->id }}">
                                                    <textarea id="comment_child" name="comment_child" class="form-control" rows="2"></textarea>
                                                    <div class="comment-part-bottom">
                                                        <button type="button" class="btn btn-cancel">取消</button>
                                                        <button type="button" class="btn btn-child-reply">回复</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    @if(Auth::guest())
                        <div class="panel-footer-tip">
                            <div class="login-tip">
                                <p>您需要登录才可以发表评论噢！！！<a href="{{ url('login') }}">登录</a> or <a href="{{ url('register') }}">注册</a></p>
                            </div>
                        </div>
                    @else
                        @if($blog->user_id != Auth::user()->id)
                            <div class="panel-footer">
                                <form method="post" action="{{ url('comment/blog_store') }}">
                                    <input type="hidden" id="editor_token" name="_token" value="{{ csrf_token() }}" />
                                    <input type="hidden" id="comment_content" name="comment_content" value="">
                                    <input type="hidden" id="blog_id" name="blog_id" value="{{ $blog->id }}">
                                    <div id="blog_comment_summernote" class="col-sm-9"></div>
                                    <div class="blog_comment_bottom">
                                        <button type="submit" class="btn btn-reply">发表评论</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <div class="col-xs-12 col-md-3">
                <div class="list-side-top">
                    <div class="panel panel-default other-panel">
                        <div class="panel-heading">
                            <h3 class="blogger"><i class="iconfont icon-jieshao other-icon"></i>博主介绍</h3>
                        </div>
                        <div class="panel-body">
                            <div class="media">
                                <a class="media-left blogger-avatar" href="{{ url('user/'.$blog->user->personal_domain) }}">
                                    <img src="{{ App\Helpers\Helpers::get_user_avatar($blog->user_id, 'small') }}" class="avatar-40" alt="{{ $blog->user->username }}">
                                </a>
                                <div class="media-body blogger-content">
                                    <a href="{{ url('user/'.$blog->user->personal_domain) }}" class="media-heading blogger-name">{{ $blog->user->username }}</a>
                                    <p class="blogger-data">
                                        <span>博客：{{ App\Helpers\Helpers::get_user_data($blog->user_id)->article_count }}</span>
                                        <span>·</span>
                                        <span>粉丝：{{ App\Helpers\Helpers::get_user_data($blog->user_id)->fan_count }}</span>
                                    </p>
                                    <div>
                                        @if($blog->user_id == (Auth::check()?Auth::user()->id:0))
                                            <a href="{{ url('blog/show_edit/'.$blog->id) }}" class="btn btn-edit">
                                                <i class="iconfont icon-bianji"></i>编辑
                                            </a>
                                            <a href="javascript:void(0)" class="btn btn-delete" data-blog-id="{{ $blog->id }}">
                                                <i class="iconfont icon-shanchu"></i>删除
                                            </a>
                                        @endif
                                        <a href="javascript:void(0)" class="btn btn-attention" data-user="{{ $blog->user_id }}" data-curr-user="{{ Auth::check() ? Auth::user()->id : 0 }}">
                                            @if(\App\Helpers\Helpers::attention($blog->user_id, 'User', (Auth::check() ? Auth::user()->id : 0)) == null)
                                                <i class="iconfont icon-guanzhuderen2"></i>关注
                                            @else
                                                <i class="iconfont icon-guanzhuderen2"></i>已关注
                                            @endif
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="list-side-other">
                    <div class="panel panel-default other-panel">
                        <div class="panel-heading">
                            <h3 class="other-blog"><i class="iconfont icon-qita1 other-icon"></i>{{ $blog->user->username }} 的其它博客</h3>
                        </div>
                        <div class="panel-body">
                            <ul class="list-group list-others">
                                @if($other_blogs->isEmpty())
                                    <p class="list-other-empty">{{ $blog->user->username }} 暂无其它博客</p>
                                @else
                                    @foreach($other_blogs as $other_blog)
                                        <li>
                                            <a href="{{ url('question/show/' . $other_blog->id) }}" title="{{ $other_blog->title }}">{{ str_limit($other_blog->title, 30) }}</a>
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
                            <h3 class="related-blog"><i class="iconfont icon-xiangguan related-icon"></i>相关博客</h3>
                        </div>
                        <div class="panel-body">
                            <ul class="list-group correlation-list">
                                @foreach($correlation_blogs as $correlation_blog)
                                    @if($blog->user_id != $correlation_blog->user_id)
                                        <li class="correlation-blog">
                                            <a href="{{ url('blog/show/' . $correlation_blog->id) }}" title="{{ $correlation_blog->title }}">{{ str_limit($correlation_blog->title, 30) }}</a>
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
    <script type="text/javascript" src="{{ url('libs/summernote/dist/summernote.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('libs/summernote/lang/summernote-zh-CN.js') }}"></script>
    <script src="{{ asset('libs/jquery-timeago/jquery.timeago.js') }}"></script>
    <script src="{{ asset('libs/jquery-timeago/locales/jquery.timeago.zh-CN.js') }}"></script>
    <script type="text/javascript" src="{{ asset('libs/zeroModal/zeroModal.min.js') }}"></script>
    <script>
        $(".time").timeago();
    </script>
    <script>
        //如相关问答为空，插入提示
        $(function () {
            if ($('.list-side-related ul.correlation-list').children().length === 0) {
                $('.list-side-related ul.correlation-list').html('<p class="list-other-empty">暂无相关博客</p>');
            }
        });
    </script>
    <script>
        //点赞博客
        $('.like-btn').click(function () {
            var blog_id = $(this).data('blog-id');
            var blog_uid = $(this).data('blog-uid');
            var blog_curruid = $(this).data('blog-curruid');
            @if(!\Auth::check())
                window.location.href = '{{ url('/login') }}';
            @else
                if (blog_uid != blog_curruid) {
                    $.get('/blog/like/'+blog_id, function (message) {
                        if (message == 'like') {
                            $('.like-btn').addClass('active');
                            $('.like-btn').html('<i class="iconfont icon-dianzan2"></i>'+'已点赞');
                        } else if (message == 'unlike') {
                            $('.like-btn').removeClass('active');
                            $('.like-btn').html('<i class="iconfont icon-dianzan2"></i>'+'点赞');
                        }
                    });
                } else {
                    layer.tips('不能点赞自己的博客^_^', '.like-btn', {
                        tips: [1, '#22d7bb'], //配置颜色
                    });
                }
            @endif
        });

        //收藏博客
        $('.favorite-btn').click(function () {
            var blog_id = $(this).data('blog-id');
            var blog_uid = $(this).data('blog-uid');
            var blog_curruid = $(this).data('blog-curruid');
            @if(!\Auth::check())
                window.location.href = '{{ url('/login') }}';
            @else
                if (blog_uid != blog_curruid) {
                    $.get('/blog/favorite/'+blog_id, function (message) {
                        if (message == 'favorite') {
                            $('.favorite-btn').addClass('active');
                            $('.favorite-btn').html('<i class="iconfont icon-shoucang"></i>'+'已收藏');
                        } else if (message == 'unfavorite') {
                            $('.favorite-btn').removeClass('active');
                            $('.favorite-btn').html('<i class="iconfont icon-shoucang"></i>'+'收藏');
                        }
                    });
                } else {
                    layer.tips('不能收藏自己的博客^_^', '.favorite-btn', {
                        tips: [1, '#22d7bb'], //配置颜色
                    });
                }
            @endif
        });

        //赞赏博客
        $('.admire-btn').click(function () {
            var blog_id = $(this).data('blog-id');
            var blog_uid = $(this).data('blog-uid');
            var blog_curruid = $(this).data('blog-curruid');
            @if(!\Auth::check())
                window.location.href = '{{ url('/login') }}';
            @else
                if (blog_uid != blog_curruid) {

                } else {
                    layer.tips('不能赞赏自己的博客^_^', '.admire-btn', {
                        tips: [1, '#22d7bb'], //配置颜色
                    });
                }
            @endif
        });

        //关注博主
        $(function () {
            $('.btn-attention').click(function () {
                var user = $(this).data('user');
                var curr_user = $(this).data('curr-user');
                @if(!\Auth::check())
                    window.location.href = '{{ url('/login') }}';
                @else
                    if (user != curr_user) {
                        $.ajax({
                            type : 'POST',
                            data : {
                                _token: '{{ csrf_token() }}',
                                'user': user,
                                'curr_user':curr_user
                            },
                            url : '{{ url('/user/attention_user') }}',
                            success: function (data) {
                                if (data == 'attention') {
                                    $('.btn-attention').html('<i class="iconfont icon-guanzhuderen2"></i>已关注');
                                } else if (data == 'unattention') {
                                    $('.btn-attention').html('<i class="iconfont icon-guanzhuderen2"></i>关注');
                                }
                            },
                            error: function () {
                                layer.msg('系统错误');
                            }
                        });
                    } else {
                        layer.tips('不能关注自己^_^', '.btn-attention', {
                            tips: [1, '#22d7bb'], //配置颜色
                        });
                    }
                @endif
            });
        });

        //删除博客
        $('.btn-delete').click(function () {
            var blog_id = $(this).data('blog-id');
            zeroModal.confirm("确定删除博客吗？", function() {
                $.ajax({
                    url : "{{url('/blog/destroy/[id]')}}".replace('[id]', blog_id),
                    data : {
                        _token: '{{csrf_token()}}',
                    },
                    dataType : "json",
                    type : "POST",
                    success : function (res) {
                        if(res.code == 706){
                            layer.msg(res.message, {
                                icon: 6,//提示的样式
                                time: 2000, //2秒关闭（如果不配置，默认是3秒）//设置后不需要自己写定时关闭了，单位是毫秒
                                end : function(){
                                    location.href='{{ url("/blog") }}';
                                }
                            });
                        } else if (res.code == 707) {
                            zeroModal.error(res.message);
                        }

                    },
                    error : function () {
                        zeroModal.error('系统错误！');
                    }
                });
            });
        });
    </script>
    <script>
        $(function () {
            //评论回复输入框切换显示隐藏
            $('.reply-icon').click(function () {
                var icon = $(this);
                var user_name = $(this).data('user-name');
                var user_id = $(this).data('user-id');
                $('#to_user').val(user_id);
                @if (Auth::check())
                    if (icon.parents('.comment-item').find('.comment-form').is(":hidden")) {
                        icon.parents('.comment-item').find('.comment-form textarea').val('');
                        icon.parents('.comment-item').find('.comment-form textarea').attr('placeholder', '@ '+user_name);
                        icon.parents('.comment-item').find('.comment-form').slideToggle('slow');
                    } else {
                        icon.parents('.comment-item').find('.comment-form textarea').val('');
                        icon.parents('.comment-item').find('.comment-form textarea').attr('placeholder', '@ '+user_name);
                    }
                @else
                    window.location.href = '{{ url('/login') }}';
                @endif
            });
            //子评论回复输入框切换显示隐藏
            $('.sub-reply-icon').click(function () {
                var icon = $(this);
                var user_name = $(this).data('user-name');
                var user_id = $(this).data('user-id');
                $('#to_user').val(user_id);
                @if (Auth::check())
                    if (icon.parents('.comment-item').find('.comment-form').is(":hidden")) {
                        icon.parents('.comment-item').find('.comment-form textarea').val('');
                        icon.parents('.comment-item').find('.comment-form textarea').attr('placeholder', '@ '+user_name);
                        icon.parents('.comment-item').find('.comment-form').slideToggle('slow');
                    } else {
                        icon.parents('.comment-item').find('.comment-form textarea').val('');
                        icon.parents('.comment-item').find('.comment-form textarea').attr('placeholder', '@ '+user_name);
                    }
                @else
                    window.location.href = '{{ url('/login') }}';
                @endif
            });
            //取消隐藏输入框
            $('.btn-cancel').click(function () {
                $(this).parents('.comment-item').find('.comment-form').hide('slow');
            });

            //评论右下角按钮显示隐藏
            $('.comment-content ul.comment-con .media').hover(function () {
                $(this).find('.media-body .operation span.oper-right').fadeIn();
            }, function () {
                $(this).find('.media-body .operation span.oper-right').fadeOut();
            });

            //评论回复
            $('.btn-child-reply').click(function () {
                var icon = $(this);
                var postData = icon.parents('.comment-form').find("#comment-content").serializeArray();
                $.post('/comment/mutual_blog_store', postData, function(html){
                    $('.comment-content .comment-form').hide();
                    icon.parents('.comment-item').find(".comment-input").append(html);

                    $('.comment-count').html(parseInt($('.comment-count').html())+1+' 条评论');       //回答的评论数+1
                });
            });

            //评论支持
            $('.like-icon').click(function () {
                var icon = $(this);
                var comment_id = $(this).data('comment-id');
                var user_id = $(this).data('user-id');
                var curr_uid = $(this).data('curr-uid');
                var like_count = icon.find('.like-count').html();
                @if(\Auth::check())
                    if (user_id != curr_uid) {
                        $.get('/comment/support/'+comment_id, function (message) {
                            if (message == 'support') {
                                like_count++;
                                icon.find('.like-count').html(like_count);
                                icon.find('i').css('color', '#555');
                                icon.find('.like-count').css('color', '#555');
                            } else if (message == 'unsupport') {
                                like_count--;
                                icon.find('.like-count').html(like_count);
                                icon.find('i').css('color', '#999');
                                icon.find('.like-count').css('color', '#999');
                            }
                        });
                    } else {
                        layer.tips('不能支持自己的评论^_^', '.like-icon', {
                            tips: [1, '#22d7bb'], //配置颜色
                        });
                    }
                @else
                    window.location.href = '{{ url('/login') }}';
                @endif
            });

            //编辑评论
            $('.edit-icon').click(function () {
                var icon = $(this);
                icon.parents('.media').css('display', 'none');
                icon.parents('.comment-item').find('.edit-comment').show('slow');
            });
            //编辑评论输入框取消隐藏
            $('.edit-cancel').click(function () {
                $(this).parents('.comment-item').find('.edit-comment').hide();
                $(this).parents('.comment-item').find('.media').show('slow');
            });
            //提交评论更改
            $('.edit-comment-btn').click(function () {
                var icon = $(this);
                var comment_id = $(this).data('comment-id');
                var postData = icon.parents('.edit-comment').find("#edit-comment").serializeArray();
                $.post('/comment/edit/'+comment_id, postData, function(message){
                    icon.parents('.comment-item').find('.edit-comment').hide();
                    icon.parents('.comment-item').find('.media .media-body p').html(message);
                    icon.parents('.comment-item').find('.media').show('slow');
                });
            });
            //删除评论
            $('.delete-icon').click(function () {
                var icon = $(this);
                var comment_id = $(this).data('comment-id');
                zeroModal.confirm("确定删除评论吗？", function() {
                    $.ajax({
                        url : "{{url('/comment/destroy/[id]')}}".replace('[id]', comment_id),
                        data : {
                            _token: '{{csrf_token()}}',
                        },
                        dataType : "json",
                        type : "POST",
                        success : function (res) {
                            if(res.code == 710){
                                layer.msg(res.message, {
                                    icon: 6,//提示的样式
                                    time: 2000, //2秒关闭（如果不配置，默认是3秒）//设置后不需要自己写定时关闭了，单位是毫秒
                                });
                                icon.parents('.media').css('display', 'none');
                            } else if (res.code == 711) {
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
        //问答的回复按条件显示
        $(function () {
            $('.default-sort').click(function () {
                var blog_id = $(this).data('blog-id');
                var sort = $(this).data('sort');
                $.get('/blog/sort_show/'+blog_id+'/'+sort, function (html) {
                    $('.media').empty();
                    $('.media').append(html);
                })
            });

            $('.time-sort').click(function () {
                var blog_id = $(this).data('blog-id');
                var sort = $(this).data('sort');
                $.get('/blog/sort_show/'+blog_id+'/'+sort, function (html) {
                    $('.media').empty();
                    $('.media').append(html);
                })
            });

            $('.support-sort').click(function () {
                var blog_id = $(this).data('blog-id');
                var sort = $(this).data('sort');
                $.get('/blog/sort_show/'+blog_id+'/'+sort, function (html) {
                    $('.media').empty();
                    $('.media').append(html);
                })
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#blog_comment_summernote').summernote({
                lang: 'zh-CN',
                width: 825,
                height: 180,
                placeholder:'请赶紧发表您的评论吧^_^',
                dialogsFade: true, //淡入淡出
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    //['para', ['ul', 'ol', 'paragraph']],
                    //['height', ['height']],
                    ['insert', ['picture', 'link']],
                    ['misc', ['fullscreen']]
                ],
                callbacks: {
                    onChange:function (contents, $editable) {
                        var code = $(this).summernote("code");
                        $("#comment_content").val(code);
                    },
                    /*onImageUpload: function(files) {
                        upload_editor_image(files[0], 'question_summernote', 'blog');
                    }*/
                }
            });

            $('.note-editor').addClass('blog-panel-extra');
            $('.modal .modal-dialog .modal-content .modal-header, .modal .modal-dialog .modal-content .modal-body, .modal .modal-dialog .modal-content .checkbox input').addClass('modal-extra');
            $('.modal .modal-dialog .modal-content .modal-body input.note-image-input').addClass('form-control');
            $('.blog-show-main .panel-footer .note-editor .note-statusbar').addClass('note-statusbar-extra');
        });
    </script>
@stop