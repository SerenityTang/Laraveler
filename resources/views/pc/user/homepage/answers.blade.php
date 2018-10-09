@extends('pc.layouts.app')
@section('title')
    {{ $user->username }} 的回复 | @parent
@stop
@section('css')
    <link href="{{ asset('css/user/default.css') }}" rel="stylesheet">
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
                    <h4 class="title">@if($user->id != (Auth::check() ? Auth::user()->id : 0)) TA的回答 @else
                            我的回答 @endif</h4>

                    <ul class="list-group">
                        <li class="list-group-item list-">
                            <div class="row headtitle">
                                <div class="col-md-7">问答标题</div>
                                <div class="col-md-1 count">支持</div>
                                <div class="col-md-1 count">反对</div>
                                <div class="col-md-1 count">评论</div>
                                <div class="col-md-2">回答日期</div>
                            </div>
                        </li>
                        @foreach($answers as $answer)
                            <li class="list-group-item list-">
                                <div class="row content">
                                    <div class="col-md-12">
                                        <a href="{{ url('question/show/' . $answer->question_id) }}"
                                           title="{{ $answer->question_title }}"
                                           class="title">{{ str_limit($answer->question_title, 60) }}</a>
                                    </div>

                                    <div class="col-md-7">
                                        @if($answer->adopted_at != null)
                                            <span class="adopt-tip">已采纳</span>
                                        @endif
                                        {!! $answer->content !!}
                                    </div>
                                    <div class="col-md-1 count">{{ $answer->support_count }}</div>
                                    <div class="col-md-1 count">{{ $answer->opposition_count }}</div>
                                    <div class="col-md-1 count">{{ $answer->comment_count }}</div>
                                    <div class="col-md-2 create-time" title="{{ $answer->created_at }}">
                                        {!! $answer->created_at !!}
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
        //为支持或反对的回答添加样式
        $(function () {
            $('.main-container .right-container ul .list- p').addClass('answer-content');
        });
    </script>
    <script>
        $(".create-time").timeago();
    </script>
@stop
