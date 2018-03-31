@extends('layouts.app')
@section('title')
    {{ $blog->title }} | @parent
@stop

@section('css')
    <link rel="stylesheet" href="{{ url('css/blog/default.css') }}">
    <link rel="stylesheet" href="{{ url('libs/summernote/summernote.css') }}">
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
                            </div>
                        </div>

                        <div class="comment-content">
                            <ul class="list-group comment-con">
                                @foreach($comments as $comment)
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
                                                        <a href="">
                                                            <i class="iconfont icon-dianzan"></i>
                                                        </a>
                                                        <span class="like-count">{{ $comment->support_count }}</span>

                                                        <a href="javascript:void(0)" class="reply-icon">
                                                            <i class="iconfont icon-icon_reply"></i>
                                                        </a>
                                                    </span>

                                                    <span class="oper-right">
                                                        <a href=""><i class="iconfont icon-bianji1 edit-icon"></i></a>
                                                        <a href=""><i class="iconfont icon-weibiaoti544 delete-icon"></i></a>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        @foreach($mutual_comments as $mutual_comment)
                                            <div class="form-group comment-input">
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
                                                        <span title="回复"><a href="javascript:void(0);" id="reply-icon" class="reply-icon" data-user-id="{{ $mutual_comment->user_id }}" data-user-name="{{ $mutual_comment->user->username }}"><i class="iconfont icon-icon_reply"></i>回复</a></span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    @if(Auth::guest())
                        <div class="panel-footer-tip">{{dd($mutual_comments)}}
                            <div class="login-tip">
                                <p>您需要登录才可以发表评论噢！！！<a href="{{ url('login') }}">登录</a> or <a href="{{ url('register') }}">注册</a></p>
                            </div>
                        </div>
                    @else
                        @if($blog->user_id != Auth::user()->id)
                            <div class="panel-footer">
                                <form method="post" action="{{ url('comment/blog_store') }}">
                                    <input type="hidden" id="editor_token" name="_token" value="{{ csrf_token() }}" />
                                    <input type="hidden" id="comment_concent" name="comment_concent" value="">
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

            </div>
        </div>
    </div>
@stop

@section('footer')
    <script src="{{ url('libs/summernote/summernote.min.js') }}"></script>
    <script src="{{ url('libs/summernote/lang/summernote-zh-CN.js') }}"></script>
    <script src="{{ asset('libs/jquery-timeago/jquery.timeago.js') }}"></script>
    <script src="{{ asset('libs/jquery-timeago/locales/jquery.timeago.zh-CN.js') }}"></script>
    <script>
        $(".time").timeago();
    </script>
    <script>
        $(function () {
            //评论回复文本输入框切换显示隐藏
            $('.reply-icon').click(function () {
                $(this).parents('.comment-item').find('.comment-form').slideToggle('slow');
            });

            //评论右下角按钮显示隐藏
            $('.comment-content ul.comment-con li').hover(function () {
                $(this).find('.media .media-body .operation span.oper-right').fadeIn();
            }, function () {
                $(this).find('.media .media-body .operation span.oper-right').fadeOut();
            });

            //
            $('.btn-reply').click(function () {
                var postData = $("#comment-content").serializeArray();
                $.post('/comment/mutual_blog_store', postData, function(html){
                    $('.comment-content .comment-form').hide();
                    $(this).parents('.comment-item').find(".comment-input").append(html);

                    //$('.comment-count').html(parseInt($('.comment-count').html())+1);       //回答的评论数+1
                });
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
                        $("#comment_concent").val(code);
                    },
                    /*onImageUpload: function(files) {
                        upload_editor_image(files[0], 'question_summernote', 'question');
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