@extends('layouts.app')
@section('title')
    {{ $user->username }} 的关注 | @parent
@stop
@section('css')
    <link href="{{ asset('css/user/default.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/tabs-homepage/css/responsive-tabs.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/tabs-homepage/css/style.css') }}" rel="stylesheet">
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
                    <h4 class="title">@if($user->id != (Auth::check() ? Auth::user()->id : 0)) TA的关注 @else 我的关注 @endif</h4>

                    <div id="horizontalTab" class="tab-top">
                        <ul>
                            <li><a href="#attention-user">关注的用户</a></li>
                            <li><a href="#attention-question">关注的问答</a></li>
                        </ul>

                        <div id="attention-user" class="attention-user">
                            <ul class="list-group">
                                @foreach($atte_users as $atte_user)
                                    <li class="list-group-item list-tab">
                                        <div class="row">
                                            <div class="col-md-1">
                                                <a class="att-u-name" href="{{ url('user/'.\App\Helpers\Helpers::get_user($atte_user->entityable_id)->personal_domain) }}">
                                                    <img src="{{ App\Helpers\Helpers::get_user_avatar($atte_user->entityable_id, 'medium') }}" class="avatar-46" alt="{{ \App\Helpers\Helpers::get_user($atte_user->entityable_id)->username }}">
                                                </a>
                                            </div>
                                            <div class="col-md-8">
                                                <a class="att-u-name" href="{{ url('user/'.\App\Helpers\Helpers::get_user($atte_user->entityable_id)->personal_domain) }}">{{ \App\Helpers\Helpers::get_user($atte_user->entityable_id)->username }}</a>
                                                <p class="data">
                                                    积分 {{ \App\Helpers\Helpers::get_user_data($atte_user->entityable_id)->credits }}
                                                    · 回答 {{ \App\Helpers\Helpers::get_user_data($atte_user->entityable_id)->answer_count }}
                                                    · 粉丝 {{ \App\Helpers\Helpers::get_user_data($atte_user->entityable_id)->fan_count }}
                                                </p>
                                            </div>
                                            <div class="col-md-1 col-md-offset-2">
                                                <a class="attention-icon" style="margin-bottom: 3px;" href="javascript:void(0)" data-user="{{ $atte_user->entityable_id }}" data-curr-user="{{ Auth::check() ? Auth::user()->id : 0 }}">
                                                    @if(\App\Helpers\Helpers::attention($atte_user->entityable_id, 'User', (Auth::check() ? Auth::user()->id : 0)) == null)
                                                        关注
                                                    @else
                                                        已关注
                                                    @endif
                                                </a>
                                                <a class="pri-letter-icon" href="">私信</a>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div id="attention-question">
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
                                @foreach($atte_ques as $atte_que)
                                    <li class="list-group-item list-tab">
                                        <div class="row content">
                                            <div class="col-md-7">
                                                <a href="{{ url('question/show/' . $atte_que->entityable_id) }}" title="{{ \App\Helpers\Helpers::get_question($atte_que->entityable_id)->title }}" class="title">{{ str_limit(\App\Helpers\Helpers::get_question($atte_que->entityable_id)->title, 60) }}</a>
                                            </div>
                                            <div class="col-md-1 count">{{ \App\Helpers\Helpers::get_question($atte_que->entityable_id)->view_count }}</div>
                                            <div class="col-md-1 count">{{ \App\Helpers\Helpers::get_question($atte_que->entityable_id)->answer_count }}</div>
                                            <div class="col-md-1 count">{{ \App\Helpers\Helpers::get_question($atte_que->entityable_id)->vote_count }}</div>
                                            <div class="col-md-2 create-time" title="{{ \App\Helpers\Helpers::get_question($atte_que->entityable_id)->created_at }}">
                                                {!! \App\Helpers\Helpers::get_question($atte_que->entityable_id)->created_at !!}
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
        //tab栏切换
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
    <script>
        //关注用户
        $(function () {
           $('.attention-icon').click(function () {
               var user = $(this).data('user');
               var curr_user = $(this).data('curr-user');
               @if(!\Auth::check())
                   window.location.href = '{{ url('/login') }}';
               @else
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
                           $('.attention-icon').html('已关注');
                       } else if (data == 'unattention') {
                           $('.attention-icon').html('关注');
                       }
                   },
                   error: function () {
                       layer.msg('系统错误');
                   }
               });
               @endif
           });
        });
    </script>
@stop
