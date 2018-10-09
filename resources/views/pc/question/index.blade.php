@extends('pc.layouts.app')
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
                    <div class="panel-heading question-heading">
                        <div id="tabs" class="tabs">
                            <nav>
                                <ul>
                                    <li @if($filter === 'newest') class="tab-current" @endif><a
                                                href="{{ route('question.index') }}"><span>最新问答</span></a></li>
                                    <li @if($filter === 'hottest') class="tab-current" @endif><a
                                                href="{{ route('question.index', ['filter' => 'hottest']) }}"><span>热门问答</span></a>
                                    </li>
                                    <li @if($filter === 'reward') class="tab-current" @endif><a
                                                href="{{ route('question.index', ['filter' => 'reward']) }}"><span>悬赏问答</span></a>
                                    </li>
                                    <li @if($filter === 'unanswer') class="tab-current" @endif><a
                                                href="{{ route('question.index', ['filter' => 'unanswer']) }}"><span>零回答</span></a>
                                    </li>
                                    <li @if($filter === 'unsolve') class="tab-current" @endif><a
                                                href="{{ route('question.index', ['filter' => 'unsolve']) }}"><span>待解决</span></a>
                                    </li>
                                    <li @if($filter === 'adopt') class="tab-current" @endif><a
                                                href="{{ route('question.index', ['filter' => 'adopt']) }}"><span>已采纳</span></a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="panel-body question-body hot-date">
                        @if($filter === 'hottest')
                            <div class="hot-question">
                                <div class="btn-group date" data-toggle="buttons">
                                    <label class="btn day-sort active" data-question-id="" data-sort="default">
                                        <input type="radio" name="default" id="day-sort"> 日榜
                                    </label>
                                    <label class="btn week-sort" data-question-id="" data-sort="time">
                                        <input type="radio" name="time" id="week-sort"> 周榜
                                    </label>
                                    <label class="btn month-sort" data-question-id="" data-sort="support">
                                        <input type="radio" name="support" id="month-sort"> 月榜
                                    </label>
                                </div>
                                <ul class="list-group">
                                    @foreach($questions as $question)
                                        <li class="list-group-item hot-list-item">
                                            <h2 class="title">
                                                <a href="{{ url('question/show/' . $question->id) }}"
                                                   title="{{ $question->title }}">{{ $question->title }}</a>
                                                @foreach($question->tags as $tag)
                                                    <a href="{{ url('/tag/tag_show/'. $tag->id) }}"
                                                       class="qb-tag">{{ $tag->name }}</a>
                                                @endforeach
                                            </h2>
                                            <a class="author"
                                               href="{{ url('user/'.$question->user->personal_domain) }}">
                                                <img src="{{ App\Helpers\Helpers::get_user_avatar($question->user_id, 'small') }}"
                                                     class="avatar-24" alt="{{ $question->user->username }}">
                                                <span class="username">{{ $question->user->username }} / </span>
                                            </a>
                                            <span class="time" title="{{ $question->created_at }}">
                                                {!! $question->created_at !!}
                                            </span>

                                            <div class="ques-count">
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
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <ul class="list-group">
                                @foreach($questions as $question)
                                    <li class="list-group-item global-list-item">
                                        <h2 class="title">
                                            <a href="{{ url('question/show/' . $question->id) }}"
                                               title="{{ $question->title }}">{{ $question->title }}</a>
                                            @foreach($question->tags as $tag)
                                                <a href="{{ url('/tag/tag_show/'. $tag->id) }}"
                                                   class="qb-tag">{{ $tag->name }}</a>
                                            @endforeach
                                        </h2>
                                        <a class="author" href="{{ url('user/'.$question->user->personal_domain) }}">
                                            <img src="{{ App\Helpers\Helpers::get_user_avatar($question->user_id, 'small') }}"
                                                 class="avatar-24" alt="{{ $question->user->username }}">
                                            <span class="username">{{ $question->user->username }} / </span>
                                        </a>
                                        <span class="time" title="{{ $question->created_at }}">
                                            {!! $question->created_at !!}
                                        </span>

                                        <div class="ques-count">
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
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-md-3 side-question">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="side-question-title"><i class="iconfont icon-zuixin1 new-icon"></i>最新回答</h4>
                    </div>
                    <div class="panel-body">
                        <ul class="list-group list-new-answers">
                            @if(isset($new_answer_questions))
                                @foreach($new_answer_questions as $new_ques)
                                    <li class="list-group-item list-new-answer">
                                        <a class="new-answer"
                                           href="{{ url('question/show/'.$new_ques->id) }}">{{ str_limit($new_ques->title, 28) }}</a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading ">
                        <h4 class="side-question-title"><i class="iconfont icon-biaoqian side-ques-tag"></i>问答标签</h4>
                    </div>
                    <div class="panel-body">
                        <div id="tagscloud">
                            @if(isset($hot_tags))
                                @foreach($hot_tags as $tag)
                                    <a href="{{ url('/tag/tag_show/'. $tag->id) }}"
                                       class="tagc{{ random_int(1,9) }}">{{ $tag->name }}</a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <div class="panel panel-default warm">
                    <div class="panel-heading warm-panel-heading">
                        <h4 class="side-question-title"><i class="iconfont icon-remen warm-icon"></i>热心排行榜</h4>
                        <div class="date">
                            <a class="week" href="javascript:void(0)">周榜</a>
                            <span> · </span>
                            <a class="month" href="javascript:void(0)">月榜</a>
                        </div>
                    </div>
                    <div class="panel-body warm-panel-body">
                        <ul class="list-group">
                            <li class="list-group-item warm-user">
                                @foreach($warm_users as $warm_user)
                                    <a href="{{ url('user/'.$warm_user->personal_domain) }}">
                                        <img src="{{ App\Helpers\Helpers::get_user_avatar($warm_user->id, 'medium') }}"
                                             class="avatar-46" data-toggle="tooltip"
                                             title="{{$warm_user->username}} 回答数：{{$warm_user->answer_count}}"
                                             alt="{{ $warm_user->username }}">
                                    </a>
                                @endforeach
                            </li>
                        </ul>
                    </div>
                </div>
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
    <script src="{{ asset('libs/tag-cloud/tagscloud.js') }}"></script>
    <script>
        $(".time").timeago();
    </script>
    <script>
        $(function () {
            $("[data-toggle='tooltip']").tooltip();
        });
    </script>
    <script>
        //问答首页中热门问答分类显示 日/周/月
        $(function () {
            $('.day-sort').click(function () {
                $.get('/question/day_sort', function (html) {
                    $('.month-sort').parents('.hot-question').find('ul.list-group').empty();
                    $('.month-sort').parents('.hot-question').find('ul.list-group').append(html);
                });
            });

            $('.week-sort').click(function () {
                $.get('/question/week_sort', function (html) {
                    $('.month-sort').parents('.hot-question').find('ul.list-group').empty();
                    $('.month-sort').parents('.hot-question').find('ul.list-group').append(html);
                });
            });

            $('.month-sort').click(function () {
                $.get('/question/month_sort', function (html) {
                    $('.month-sort').parents('.hot-question').find('ul.list-group').empty();
                    $('.month-sort').parents('.hot-question').find('ul.list-group').append(html);
                });
            });
        })
    </script>
    <script>
        $('.week').click(function () {
            $.get('/question/warm_week', function (html) {
                $('.week').css('color', '#22d7bb');
                $('.week').siblings('a').css('color', '#ccc');
                $('.week').parents('.warm').find('.warm-panel-body ul.list-group').empty();
                $('.week').parents('.warm').find('.warm-panel-body ul.list-group').append(html);
            });
        });
        $('.month').click(function () {
            $.get('/question/warm_month', function (html) {
                $('.month').css('color', '#22d7bb');
                $('.month').siblings('a').css('color', '#ccc');
                $('.month').parents('.warm').find('.warm-panel-body ul.list-group').empty();
                $('.month').parents('.warm').find('.warm-panel-body ul.list-group').append(html);
            });
        });
    </script>
@stop