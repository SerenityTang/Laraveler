@extends('layouts.app')
@section('title')
    问答首页 | @parent
@stop
@section('css')
    <link rel="stylesheet" href="{{ url('css/question/default.css') }}">
    <link rel="stylesheet" href="{{ url('libs/tabs/css/component.css') }}">
    <link rel="stylesheet" href="{{ url('libs/tabs/css/demo.css') }}">
@stop
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-9 question-main">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div id="tabs" class="tabs">
                            <nav>
                                <ul>
                                    <li @if($filter === 'newest') class="tab-current" @endif><a href="{{ route('question.index') }}"><span>最新问答</span></a></li>
                                    <li @if($filter === 'hottest') class="tab-current" @endif><a href="{{ route('question.index', ['filter' => 'hottest']) }}"><span>热门问答</span></a></li>
                                    <li @if($filter === 'reward') class="tab-current" @endif><a href="{{ route('question.index', ['filter' => 'reward']) }}"><span>悬赏问答</span></a></li>
                                    <li @if($filter === 'unsolve') class="tab-current" @endif><a href="{{ route('question.index', ['filter' => 'unsolve']) }}"><span>待回答</span></a></li>
                                    <li @if($filter === 'unanswer') class="tab-current" @endif><a href="{{ route('question.index', ['filter' => 'unanswer']) }}"><span>待解决</span></a></li>
                                    <li @if($filter === 'adopt') class="tab-current" @endif><a href="{{ route('question.index', ['filter' => 'adopt']) }}"><span>已采纳</span></a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="panel-body">
                        <ul class="list-group">
                            @foreach($questions as $question)
                                <li class="list-group-item">
                                    <h2 class="title">
                                        <a href="{{ url('question/show/' . $question->id) }}" title="{{ $question->title }}">{{ $question->title }}</a>
                                    </h2>
                                    <a class="author" href="{{ url('') }}">
                                        <img src="{{ App\Helpers\Helpers::get_user_avatar($question->user_id, 'small') }}" class="avatar-24" alt="{{ $question->user->username }}">
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
                                        <span title="回答数"><i class="iconfont icon-tubiaopinglunshu"></i>{{$question->answer_count}}</span>
                                        <span>|</span>
                                        <span title="关注数"><i class="iconfont icon-guanzhu"></i>{{$question->attention_count}}</span>
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