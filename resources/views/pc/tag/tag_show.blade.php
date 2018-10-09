@extends('pc.layouts.app')
@section('title')
    标签云 | @parent
@stop
@section('css')
    <link rel="stylesheet" href="{{ url('css/tag/default.css') }}">
    <link rel="stylesheet" href="{{ url('libs/tag-tab/css/style.css') }}">
@stop
@section('content')
    <div class="container">
        <div class="row">
            <div class="page-header title-header">
                <h2 class="tag-title">{{ $tag->name }}</h2>
                @if(Auth::check() && \App\Helpers\Helpers::attention($tag->id, 'Tag', Auth::user()->id) != null)
                    <a href="javascript:void(0)" class="attention-btn" data-tag-id="{{ $tag->id }}">已关注
                        | {{ $tag->attention_count }}</a>
                @else
                    <a href="javascript:void(0)" class="attention-btn" data-tag-id="{{ $tag->id }}">关注
                        | {{ $tag->attention_count }}</a>
                @endif
            </div>
            <p class="title-header-desc">{{ $tag->description }}</p>
        </div>
        <div class="row">
            <div class="col-md-9">
                <div class="tabox">
                    <div class="hd tab-header">
                        <ul>
                            <li class="">技术问答</li>
                            <li class="">博客文章</li>
                        </ul>
                    </div>
                    <div class="bd">
                        <ul class="lh list-group question-con" style="display: none;">
                            @foreach($questions as $question)
                                <li class="list-group-item global-list-item">
                                    <h2 class="title">
                                        <a href="{{ url('question/show/' . $question->id) }}"
                                           title="{{ $question->title }}">{{ $question->title }}</a>
                                    </h2>
                                    <a class="author" href="{{ url('user/'.$question->user->personal_domain) }}">
                                        <img src="{{ App\Helpers\Helpers::get_user_avatar($question->user_id, 'small') }}"
                                             class="avatar-24" alt="{{ $question->user->username }}">
                                        <span class="username">{{ $question->user->username }} / </span>
                                    </a>
                                    <span class="time" title="{{ $question->created_at }}">
                                        {!! $question->created_at !!}
                                    </span>

                                    <div class="ques-count">
                                        <span title="浏览数"><i class="iconfont icon-liulan"></i>{{$question->view_count}}</span>
                                        <span>|</span>
                                        <span title="投票数"><i class="iconfont icon-toupiao"></i>{{$question->vote_count}}</span>
                                        <span>|</span>
                                        <span title="回答数"><i
                                                    class="iconfont icon-tubiaopinglunshu"></i>{{$question->answer_count}}</span>
                                        <span>|</span>
                                        <span title="关注数"><i
                                                    class="iconfont icon-guanzhu"></i>{{$question->attention_count}}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        <ul class="lh list-group blog-con" style="display: none;">
                            @foreach($blogs as $blog)
                                <li class="list-group-item global-list-item">
                                    <h2 class="title">
                                        <a href="{{ url('blog/show/'.$blog->id) }}"
                                           title="{{ $blog->title }}">{{ $blog->title }}</a>
                                    </h2>
                                    <a class="author" href="{{ url('') }}">
                                        <img src="{{ App\Helpers\Helpers::get_user_avatar($blog->user_id, 'small') }}"
                                             class="avatar-24" alt="{{ $blog->user->username }}">
                                        <span class="username">{{ $blog->user->username }} / </span>
                                    </a>
                                    <span class="time" title="{{ $blog->created_at }}">
                                        {!! $blog->created_at !!}
                                    </span>

                                    <div class="ques-count">
                                        <span title="浏览数"><i
                                                    class="iconfont icon-liulan"></i>{{$blog->view_count}}</span>
                                        <span>|</span>
                                        <span title="点赞数"><i
                                                    class="iconfont icon-dianzan1"></i>{{$blog->like_count}}</span>
                                        <span>|</span>
                                        <span title="收藏数"><i
                                                    class="iconfont icon-shoucang1"></i>{{$blog->favorite_count}}</span>
                                        <span>|</span>
                                        <span title="评论数"><i class="iconfont icon-pinglun"></i>{{$blog->comment_count}}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-3">

            </div>
        </div>
    </div>
@stop

@section('footer')
    <script src="{{ asset('libs/jquery-timeago/jquery.timeago.js') }}"></script>
    <script src="{{ asset('libs/jquery-timeago/locales/jquery.timeago.zh-CN.js') }}"></script>
    <script type="text/javascript" src="{{ asset('libs/tag-tab/js/jquery.SuperSlide.js') }}"></script>
    <script type="text/javascript">
        jQuery(".tabox").slide({delayTime: 0});
    </script>
    <script>
        $(".time").timeago();
    </script>
    <script>
        $('.attention-btn').click(function () {
            var tag_id = $(this).data('tag-id');
            var atten_count = parseInt($(this).html().replace(/[^0-9]/ig, ""));  //获取a标签(按钮)中数字
            var icon = $(this);
            $.ajax({
                type: 'POST',
                url: '{{ url('/tag/attention/[id]') }}'.replace('[id]', tag_id),
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function (data) {
                    if (data == 'unattention') {
                        atten_count--;
                        icon.html('关注 | ' + atten_count);
                    } else if (data == 'attention') {
                        atten_count++;
                        icon.html('已关注 | ' + atten_count);
                    }
                },
                error: function () {

                }
            });
        });
    </script>
@stop