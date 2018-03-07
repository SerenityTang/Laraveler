@extends('layouts.app')
@section('title')
    博客首页 | @parent
@stop
@section('css')
    <link rel="stylesheet" href="{{ url('css/blog/default.css') }}">
    <link rel="stylesheet" href="{{ url('libs/tabs/css/component.css') }}">
    <link rel="stylesheet" href="{{ url('libs/tabs/css/demo.css') }}">
@stop
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-9 blog-main">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div id="tabs" class="tabs">
                            <nav>
                                <ul>
                                    <li @if($filter === 'newest') class="tab-current" @endif><a href="{{ route('blog.index') }}"><span>最新博客</span></a></li>
                                    <li @if($filter === 'hottest') class="tab-current" @endif><a href="{{ route('blog.index', ['filter' => 'hottest']) }}"><span>热门博客</span></a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="panel-body">
                        <ul class="list-group">
                            @foreach($blogs as $blog)
                                <li class="list-group-item">
                                    <h2 class="title">
                                        <a href="{{ url('') }}" title="{{ $blog->title }}">{{ $blog->title }}</a>
                                    </h2>
                                    <a class="author" href="{{ url('') }}">
                                        <img src="{{ App\Helpers\Helpers::get_user_avatar($blog->user_id, 'small') }}" class="avatar-24" alt="{{ $blog->user->username }}">
                                        <span class="username">{{ $blog->user->username }} / </span>
                                    </a>
                                    <span class="time" title="{{ $blog->created_at }}">
                                        {!! $blog->created_at !!}
                                    </span>

                                    <div class="ques-count">
                                        <span title="浏览数"><i class="iconfont icon-liulan"></i>{{$blog->view_count}}</span>
                                        <span>|</span>
                                        <span title="点赞数"><i class="iconfont icon-dianzan1"></i>{{$blog->like_count}}</span>
                                        <span>|</span>
                                        <span title="收藏数"><i class="iconfont icon-shoucang1"></i>{{$blog->favorite_count}}</span>
                                        <span>|</span>
                                        <span title="评论数"><i class="iconfont icon-pinglun"></i>{{$blog->comment_count}}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-md-3">

            </div>
        </div>
    </div>
@stop

@section('footer')
    {{--<script type="text/javascript" src="{{ url('libs/tabs/js/cbpFWTabs.js') }}"></script>
    <script>
        new CBPFWTabs( document.getElementById( 'tabs' ) );
    </script>--}}
    <script src="{{ asset('libs/jquery-timeago/jquery.timeago.js') }}"></script>
    <script src="{{ asset('libs/jquery-timeago/locales/jquery.timeago.zh-CN.js') }}"></script>
    <script>
        $(".time").timeago();
    </script>
@stop