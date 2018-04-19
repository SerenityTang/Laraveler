@extends('pc.layouts.app')
@section('title')
    {{ $user->username }} 的收藏 | @parent
@stop
@section('css')
    <link href="{{ asset('css/user/default.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/tabs-homepage/css/responsive-tabs.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/tabs-homepage/css/style.css') }}" rel="stylesheet">
@stop

@section('content')
    @include('pc.user.partials.homepage_header')

    <div class="container main-container">
        <div class="row">
            {{--左侧菜单--}}
            <div class="col-md-3">
                @include('pc.user.partials.home-side-menu')
            </div>

            <div class="col-md-9">
                <div class="panel panel-default right-container">
                    <h4 class="title">@if($user->id != (Auth::check() ? Auth::user()->id : 0)) TA的收藏 @else 我的收藏 @endif</h4>

                    <div id="horizontalTab" class="tab-top">
                        <ul>
                            <li><a href="#collection-question">收藏的问答</a></li>
                            <li><a href="#collection-blog">收藏的博客</a></li>
                        </ul>

                        <div id="collection-question">
                            <ul class="list-group">
                                <li class="list-group-item list-tab">
                                    <div class="row headtitle">
                                        <div class="col-md-7">问答标题</div>
                                        <div class="col-md-1 count">查看</div>
                                        <div class="col-md-1 count">回答</div>
                                        <div class="col-md-1 count">投票</div>
                                        <div class="col-md-2">发布日期</div>
                                    </div>
                                </li>
                                @foreach($coll_ques as $coll_que)
                                    <li class="list-group-item list-tab">
                                        <div class="row content">
                                            <div class="col-md-7">
                                                <a href="{{ url('question/show/' . $coll_que->entityable_id) }}" title="{{ \App\Helpers\Helpers::get_question($coll_que->entityable_id)->title }}" class="title">{{ str_limit(\App\Helpers\Helpers::get_question($coll_que->entityable_id)->title, 60) }}</a>
                                            </div>
                                            <div class="col-md-1 count">{{ \App\Helpers\Helpers::get_question($coll_que->entityable_id)->view_count }}</div>
                                            <div class="col-md-1 count">{{ \App\Helpers\Helpers::get_question($coll_que->entityable_id)->answer_count }}</div>
                                            <div class="col-md-1 count">{{ \App\Helpers\Helpers::get_question($coll_que->entityable_id)->vote_count }}</div>
                                            <div class="col-md-2 create-time" title="{{ \App\Helpers\Helpers::get_question($coll_que->entityable_id)->created_at }}">
                                                {!! \App\Helpers\Helpers::get_question($coll_que->entityable_id)->created_at !!}
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div id="collection-blog">
                            <ul class="list-group">
                                <li class="list-group-item list-tab">
                                    <div class="row headtitle">
                                        <div class="col-md-7">博客标题</div>
                                        <div class="col-md-1 count">查看</div>
                                        <div class="col-md-1 count">点赞</div>
                                        <div class="col-md-1 count">收藏</div>
                                        <div class="col-md-2">发布日期</div>
                                    </div>
                                </li>
                                @foreach($coll_blogs as $coll_blog)
                                    <li class="list-group-item list-tab">
                                        <div class="row content">
                                            <div class="col-md-7">
                                                <a href="{{ url('blog/show/' . $coll_blog->entityable_id) }}" title="{{ \App\Helpers\Helpers::get_blog($coll_blog->entityable_id)->title }}" class="title">{{ str_limit(\App\Helpers\Helpers::get_blog($coll_blog->entityable_id)->title, 60) }}</a>
                                            </div>
                                            <div class="col-md-1 count">{{ \App\Helpers\Helpers::get_blog($coll_blog->entityable_id)->view_count }}</div>
                                            <div class="col-md-1 count">{{ \App\Helpers\Helpers::get_blog($coll_blog->entityable_id)->like_count }}</div>
                                            <div class="col-md-1 count">{{ \App\Helpers\Helpers::get_blog($coll_blog->entityable_id)->favorite_count }}</div>
                                            <div class="col-md-2 create-time" title="{{ \App\Helpers\Helpers::get_blog($coll_blog->entityable_id)->created_at }}">
                                                {!! \App\Helpers\Helpers::get_blog($coll_blog->entityable_id)->created_at !!}
                                            </div>
                                        </div>
                                    </li>
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
    <script src="{{ asset('libs/tabs-homepage/js/jquery.responsiveTabs.js') }}"></script>
    <script src="{{ asset('libs/jquery-timeago/jquery.timeago.js') }}"></script>
    <script src="{{ asset('libs/jquery-timeago/locales/jquery.timeago.zh-CN.js') }}"></script>
    <script>
        $(".create-time").timeago();
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            var $tabs = $('#horizontalTab');
            $tabs.responsiveTabs({
                rotate: false,
                startCollapsed: 'accordion',
                collapsible: 'accordion',
                setHash: true,
                disabled: [3, 4],
                click: function (e, tab) {
                    $('.info').html('Tab <strong>' + tab.id + '</strong> clicked!');
                },
                activate: function (e, tab) {
                    $('.info').html('Tab <strong>' + tab.id + '</strong> activated!');
                },
                activateState: function (e, state) {
                    //console.log(state);
                    $('.info').html('Switched from <strong>' + state.oldState + '</strong> state to <strong>' + state.newState + '</strong> state!');
                }
            });
        });
    </script>
@stop
