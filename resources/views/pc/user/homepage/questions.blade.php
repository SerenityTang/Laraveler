@extends('pc.layouts.app')
@section('title')
    {{ $user->username }} 的问答 | @parent
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
                    <h4 class="title">@if($user->id != (Auth::check() ? Auth::user()->id : 0)) TA的问答 @else 我的问答 @endif</h4>

                    <ul class="list-group">
                        <li class="list-group-item list-">
                            <div class="row headtitle">
                                <div class="col-md-7">问答标题</div>
                                <div class="col-md-1 count">查看</div>
                                <div class="col-md-1 count">回答</div>
                                <div class="col-md-1 count">投票</div>
                                <div class="col-md-2">发布日期</div>
                            </div>
                        </li>
                        @foreach($questions as $question)
                            <li class="list-group-item list-">
                                <div class="row content">
                                    <div class="col-md-7">
                                        <a href="{{ url('question/show/' . $question->id) }}" title="{{ $question->title }}" class="title">{{ str_limit($question->title, 60) }}</a>
                                    </div>
                                    <div class="col-md-1 count">{{ $question->view_count }}</div>
                                    <div class="col-md-1 count">{{ $question->answer_count }}</div>
                                    <div class="col-md-1 count">{{ $question->vote_count }}</div>
                                    <div class="col-md-2 create-time" title="{{ $question->created_at }}">
                                        {!! $question->created_at !!}
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
