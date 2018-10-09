@extends('pc.layouts.app')
@section('title')
    {{ $user->username }} 个人主页 | @parent
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
                    <div id="horizontalTab">
                        <ul>
                            <li><a href="#per_dynamic">个人动态</a></li>
                            <li><a href="#"></a></li>
                        </ul>

                        <div id="per_dynamic">
                            <ul class="list-group">
                                @foreach($per_dyns as $per_dyn)
                                    @if($per_dyn->action == 'publishQues')
                                        <li class="list-group-item dyn-item">
                                            <p class="dyn-info">{{\App\Helpers\Helpers::get_user($per_dyn->user_id)->username}}
                                                发布了问答于
                                                <span class="time" title="{{ $per_dyn->created_at }}">
                                                    {!! $per_dyn->created_at !!}
                                                </span>
                                            </p>
                                            <a href="{{ url('question/show/'.$per_dyn->source_id) }}"
                                               class="dyn-item-title">{{ $per_dyn->title }}</a>
                                            <p class="dyn-content">
                                                {!! str_limit($per_dyn->content, 1000) !!}
                                            </p>
                                        </li>
                                    @elseif($per_dyn->action == 'answerQues')
                                        <li class="list-group-item dyn-item">
                                            <p class="dyn-info">{{\App\Helpers\Helpers::get_user($per_dyn->user_id)->username}}
                                                回答了问答于
                                                <span class="time" title="{{ $per_dyn->created_at }}">
                                                    {!! $per_dyn->created_at !!}
                                                </span>
                                            </p>
                                            <a href="{{ url('question/show/'.$per_dyn->source_id) }}"
                                               class="dyn-item-title">{{ $per_dyn->title }}</a>
                                            <p class="dyn-content">
                                                {!! str_limit($per_dyn->content, 1000) !!}
                                            </p>
                                        </li>
                                    @elseif($per_dyn->action == 'voteQues')
                                        <li class="list-group-item dyn-item">
                                            <p class="dyn-info">{{\App\Helpers\Helpers::get_user($per_dyn->user_id)->username}}
                                                投票了问答于
                                                <span class="time" title="{{ $per_dyn->created_at }}">
                                                    {!! $per_dyn->created_at !!}
                                                </span>
                                            </p>
                                            <<a href="{{ url('question/show/'.$per_dyn->source_id) }}"
                                                class="dyn-item-title">{{ $per_dyn->title }}</a>
                                            <p class="dyn-content">
                                                {!! str_limit($per_dyn->content, 1000) !!}
                                            </p>
                                        </li>
                                    @elseif($per_dyn->action == 'attentionQues')
                                        <li class="list-group-item dyn-item">
                                            <p class="dyn-info">{{\App\Helpers\Helpers::get_user($per_dyn->user_id)->username}}
                                                关注了问答于
                                                <span class="time" title="{{ $per_dyn->created_at }}">
                                                    {!! $per_dyn->created_at !!}
                                                </span>
                                            </p>
                                            <a href="{{ url('question/show/'.$per_dyn->source_id) }}"
                                               class="dyn-item-title">{{ $per_dyn->title }}</a>
                                            <p class="dyn-content">
                                                {!! str_limit($per_dyn->content, 1000) !!}
                                            </p>
                                        </li>
                                    @elseif($per_dyn->action == 'collectionQues')
                                        <li class="list-group-item dyn-item">
                                            <p class="dyn-info">{{\App\Helpers\Helpers::get_user($per_dyn->user_id)->username}}
                                                收藏了问答于
                                                <span class="time" title="{{ $per_dyn->created_at }}">
                                                    {!! $per_dyn->created_at !!}
                                                </span>
                                            </p>
                                            <a href="{{ url('question/show/'.$per_dyn->source_id) }}"
                                               class="dyn-item-title">{{ $per_dyn->title }}</a>
                                            <p class="dyn-content">
                                                {!! str_limit($per_dyn->content, 1000) !!}
                                            </p>
                                        </li>
                                    @elseif($per_dyn->action == 'publishBlog')
                                        <li class="list-group-item dyn-item">
                                            <p class="dyn-info">{{\App\Helpers\Helpers::get_user($per_dyn->user_id)->username}}
                                                发布了博客于
                                                <span class="time" title="{{ $per_dyn->created_at }}">
                                                    {!! $per_dyn->created_at !!}
                                                </span>
                                            </p>
                                            <a href="{{ url('blog/show/'.$per_dyn->source_id) }}"
                                               class="dyn-item-title">{{ $per_dyn->title }}</a>
                                            <div class="dyn-content">
                                                {!! str_limit($per_dyn->content, 1000) !!}
                                            </div>
                                        </li>
                                    @elseif($per_dyn->action == 'likeBlog')
                                        <li class="list-group-item dyn-item">
                                            <p class="dyn-info">{{\App\Helpers\Helpers::get_user($per_dyn->user_id)->username}}
                                                点赞了博客于
                                                <span class="time" title="{{ $per_dyn->created_at }}">
                                                    {!! $per_dyn->created_at !!}
                                                </span>
                                            </p>
                                            <a href="{{ url('blog/show/'.$per_dyn->source_id) }}"
                                               class="dyn-item-title">{{ $per_dyn->title }}</a>
                                            <p class="dyn-content">
                                                {!! str_limit($per_dyn->content, 1000) !!}
                                            </p>
                                        </li>
                                    @elseif($per_dyn->action == 'favoriteBlog')
                                        <li class="list-group-item dyn-item">
                                            <p class="dyn-info">{{\App\Helpers\Helpers::get_user($per_dyn->user_id)->username}}
                                                收藏了博客于
                                                <span class="time" title="{{ $per_dyn->created_at }}">
                                                    {!! $per_dyn->created_at !!}
                                                </span>
                                            </p>
                                            <a href="{{ url('blog/show/'.$per_dyn->source_id) }}"
                                               class="dyn-item-title">{{ $per_dyn->title }}</a>
                                            <p class="dyn-content">
                                                {!! str_limit($per_dyn->content, 1000) !!}
                                            </p>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>

                        <div id="">

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
        $(".time").timeago();
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
