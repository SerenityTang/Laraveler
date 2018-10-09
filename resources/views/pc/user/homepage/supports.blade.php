@extends('pc.layouts.app')
@section('title')
    {{ $user->username }} 的点赞 | @parent
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
                    <h4 class="title">@if($user->id != (Auth::check() ? Auth::user()->id : 0)) TA的点赞 @else
                            我的点赞 @endif</h4>

                    <div id="horizontalTab" class="tab-top">
                        <ul>
                            <li><a href="#support-answer">支持的回答</a></li>
                            <li><a href="#oppose-answer">反对的回答</a></li>
                            <li><a href="#support-blog">点赞的博客</a></li>
                        </ul>

                        <div id="support-answer">
                            <ul class="list-group">
                                @foreach($supp_answers as $supp_answer)
                                    <li class="list-group-item list-tab">
                                        <div class="row content">
                                            <div class="col-md-12">
                                                <a href="{{ url('question/show/' . \App\Helpers\Helpers::get_answer($supp_answer->sup_opp_able_id)->question_id) }}"
                                                   title="{{ \App\Helpers\Helpers::get_answer($supp_answer->sup_opp_able_id)->question_title }}"
                                                   class="title">{{ str_limit(\App\Helpers\Helpers::get_answer($supp_answer->sup_opp_able_id)->question_title, 60) }}</a>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="col-md-8">{!! \App\Helpers\Helpers::get_answer($supp_answer->sup_opp_able_id)->content !!}</div>
                                                <div class="col-md-2 create-time"
                                                     title="{{ \App\Helpers\Helpers::get_answer($supp_answer->sup_opp_able_id)->created_at }}">
                                                    {!! \App\Helpers\Helpers::get_answer($supp_answer->sup_opp_able_id)->created_at !!}
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div id="oppose-answer">
                            <ul class="list-group">
                                @foreach($oppo_answers as $oppo_answer)
                                    <li class="list-group-item list-tab">
                                        <div class="row content">
                                            <div class="col-md-12">
                                                <a href="{{ url('question/show/' . \App\Helpers\Helpers::get_answer($oppo_answer->sup_opp_able_id)->question_id) }}"
                                                   title="{{ \App\Helpers\Helpers::get_answer($oppo_answer->sup_opp_able_id)->question_title }}"
                                                   class="title">{{ str_limit(\App\Helpers\Helpers::get_answer($oppo_answer->sup_opp_able_id)->question_title, 60) }}</a>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="col-md-8">{!! \App\Helpers\Helpers::get_answer($oppo_answer->sup_opp_able_id)->content !!}</div>
                                                <div class="col-md-2 create-time"
                                                     title="{{ \App\Helpers\Helpers::get_answer($oppo_answer->sup_opp_able_id)->created_at }}">
                                                    {!! \App\Helpers\Helpers::get_answer($oppo_answer->sup_opp_able_id)->created_at !!}
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div id="support-blog">
                            <ul class="list-group">
                                @foreach($like_blogs as $like_blog)
                                    <li class="list-group-item list-tab">
                                        <div class="row content">
                                            <div class="col-md-10">
                                                <a href="{{ url('blog/show/' . $like_blog->sup_opp_able_id) }}"
                                                   title="{{ \App\Helpers\Helpers::get_blog($like_blog->sup_opp_able_id)->title }}"
                                                   class="title">{{ str_limit(\App\Helpers\Helpers::get_blog($like_blog->sup_opp_able_id)->title, 60) }}</a>
                                            </div>

                                            <div class="col-md-2 create-time"
                                                 title="{{ \App\Helpers\Helpers::get_blog($like_blog->sup_opp_able_id)->created_at }}">
                                                {!! \App\Helpers\Helpers::get_blog($like_blog->sup_opp_able_id)->created_at !!}
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
        //为支持或反对的回答添加样式
        $(function () {
            $('.main-container .right-container ul .list-tab p').addClass('answer-content');
        });
    </script>
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
