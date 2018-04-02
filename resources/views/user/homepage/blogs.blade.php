@extends('layouts.app')
@section('title')
    {{ $user->username }} 的博客 | @parent
@stop
@section('css')
    <link href="{{ asset('css/user/default.css') }}" rel="stylesheet">
@stop

@section('content')
    @include('user.partials.homepage_header')

    <div class="container main-container">
        <div class="row">
            {{--左侧菜单--}}
            <div class="col-md-3">
                @include('user.partials.home-side-menu')
            </div>

            <div class="col-md-9">
                <div class="panel panel-default right-container">
                    <h4 class="title">@if($user->id != (Auth::check() ? Auth::user()->id : 0)) TA的博客 @else 我的博客 @endif</h4>

                    <ul class="list-group">
                        <li class="list-group-item list-">
                            <div class="row headtitle">
                                <div class="col-md-7">博客标题</div>
                                <div class="col-md-1 count">查看</div>
                                <div class="col-md-1 count">点赞</div>
                                <div class="col-md-1 count">收藏</div>
                                <div class="col-md-2">发布日期</div>
                            </div>
                        </li>
                        @foreach($blogs as $blog)
                            <li class="list-group-item list-">
                                <div class="row content">
                                    <div class="col-md-7">
                                        <a href="{{ url('blog/show/' . $blog->id) }}" title="{{ $blog->title }}" class="title">{{ str_limit($blog->title, 60) }}</a>
                                    </div>
                                    <div class="col-md-1 count">{{ $blog->view_count }}</div>
                                    <div class="col-md-1 count">{{ $blog->like_count }}</div>
                                    <div class="col-md-1 count">{{ $blog->favorite_count }}</div>
                                    <div class="col-md-2 create-time" title="{{ $blog->created_at }}">
                                        {!! $blog->created_at !!}
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@stop

@section('footer')
    <script src="{{ asset('libs/jquery-timeago/jquery.timeago.js') }}"></script>
    <script src="{{ asset('libs/jquery-timeago/locales/jquery.timeago.zh-CN.js') }}"></script>
    <script>
        $(".create-time").timeago();
    </script>
@stop
