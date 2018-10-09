@extends('pc.layouts.app')
@section('title')
    搜索 {{ $keyword }} | @parent
@stop
@section('css')
    <link rel="stylesheet" href="{{ url('libs/tabs/css/component.css') }}">
    <link rel="stylesheet" href="{{ url('libs/tabs/css/demo.css') }}">
@stop
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-12 search-main">
                <div class="panel panel-default">
                    <div class="panel-heading search-heading">
                        <div id="tabs" class="tabs">
                            <nav>
                                <ul>
                                    <li @if($filter === 'all') class="tab-current" @endif><a
                                                href="{{ route('search.show') }}?q={{ $keyword }}"><span>全部</span></a>
                                    </li>
                                    <li @if($filter === 'question') class="tab-current" @endif><a
                                                href="{{ route('search.show', ['filter' => 'question']) }}?q={{ $keyword }}"><span>问答</span></a>
                                    </li>
                                    <li @if($filter === 'blog') class="tab-current" @endif><a
                                                href="{{ route('search.show', ['filter' => 'blog']) }}?q={{ $keyword }}"><span>博客</span></a>
                                    </li>
                                    <li @if($filter === 'tag') class="tab-current" @endif><a
                                                href="{{ route('search.show', ['filter' => 'tag']) }}?q={{ $keyword }}"><span>标签</span></a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="panel-body search-body">
                        <ul class="list-group">
                            @if(isset($ques_searchs))
                                @foreach($ques_searchs as $question)
                                    <li class="list-group-item global-list-item">
                                        <h2 class="title">
                                            <a href="{{ url('question/show/' . $question->id) }}"
                                               title="{{ $question->title }}">{!! str_replace($keyword, '<span class="keyword">'.$keyword.'</span>', $question->title) !!}</a>
                                        </h2>

                                        {!! str_limit($question->description, 200) !!}

                                        <div>
                                            <a class="author"
                                               href="{{ url('user/'.$question->user->personal_domain) }}">
                                                <img src="{{ App\Helpers\Helpers::get_user_avatar($question->user_id, 'small') }}"
                                                     class="avatar-24" alt="{{ $question->user->username }}">
                                                <span class="username">{{ $question->user->username }} / </span>
                                            </a>
                                            <span class="time" title="{{ $question->created_at }}">
                                                {!! $question->created_at !!}
                                            </span>

                                            <div class="search-count">
                                                <span title="浏览数"><i
                                                            class="iconfont icon-liulan"></i>{{$question->view_count}}</span>
                                                <span>|</span>
                                                <span title="投票数"><i
                                                            class="iconfont icon-toupiao"></i>{{$question->vote_count}}</span>
                                                <span>|</span>
                                                <span title="回答数"><i
                                                            class="iconfont icon-tubiaopinglunshu"></i>{{$question->answer_count}}</span>
                                                <span>|</span>
                                                <span title="关注数"><i
                                                            class="iconfont icon-guanzhu"></i>{{$question->attention_count}}</span>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            @endif
                            @if(isset($blog_searchs))
                                @foreach($blog_searchs as $blog)
                                    <li class="list-group-item global-list-item">
                                        <h2 class="title">
                                            <a href="{{ url('blog/show/' . $blog->id) }}"
                                               title="{{ $blog->title }}">{!! str_replace($keyword, '<span class="keyword">'.$keyword.'</span>', $question->title) !!}</a>
                                        </h2>

                                        {!! str_limit($blog->description, 200) !!}

                                        <div>
                                            <a class="author" href="{{ url('user/'.$blog->user->personal_domain) }}">
                                                <img src="{{ App\Helpers\Helpers::get_user_avatar($blog->user_id, 'small') }}"
                                                     class="avatar-24" alt="{{ $blog->user->username }}">
                                                <span class="username">{{ $blog->user->username }} / </span>
                                            </a>
                                            <span class="time" title="{{ $blog->created_at }}">
                                                {!! $blog->created_at !!}
                                            </span>

                                            <div class="search-count">
                                                <span title="浏览数"><i
                                                            class="iconfont icon-liulan"></i>{{$blog->view_count}}</span>
                                                <span>|</span>
                                                <span title="点赞数"><i
                                                            class="iconfont icon-dianzan1"></i>{{$blog->like_count}}</span>
                                                <span>|</span>
                                                <span title="收藏数"><i
                                                            class="iconfont icon-shoucang1"></i>{{$blog->favorite_count}}</span>
                                                <span>|</span>
                                                <span title="评论数"><i
                                                            class="iconfont icon-pinglun"></i>{{$blog->comment_count}}</span>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('footer')
    <script src="{{ asset('libs/jquery-timeago/jquery.timeago.js') }}"></script>
    <script src="{{ asset('libs/jquery-timeago/locales/jquery.timeago.zh-CN.js') }}"></script>
    <script>
        $(".time").timeago();
    </script>
    <script>
        $('.search-main  .panel-body ul li p').addClass('desc');
    </script>
@stop