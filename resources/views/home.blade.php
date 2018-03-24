@extends('layouts.app')

@section('css')

@stop

@section('content')
    <div class="container home-container">
        <div class="row">
            <div class="col-xs-12 col-md-9">
                <div class="row">
                    <div class="col-md-6">
                        <div class="panel home-bg">
                            <div class="panel-heading home-heading">
                                <h3 class="panel-title">
                                    <a href="{{ route('question.index') }}" class="section-title">最新问答</a>
                                    <a href="{{ route('question.index') }}" class="section-more">more</a>
                                </h3>
                            </div>
                            <div class="panel-body home-body">
                                <ul class="list-group">
                                    @foreach($new_questions as $question)
                                        <li class="list-group-item home-list-item">
                                            <div class="media">
                                                <a class="media-left home-media-left" href="{{ url('user/'.$question->user->personal_domain) }}">
                                                    <img src="{{ App\Helpers\Helpers::get_user_avatar($question->user_id, 'small') }}" class="avatar-32" alt="{{ $question->user->username }}">
                                                </a>
                                                <div class="media-body">
                                                    <h4 class="media-heading home-media-heading">
                                                        <a href="{{ url('question/show/' . $question->id) }}" title="{{ $question->title }}" class="h_q_title">{{ $question->title }}</a>
                                                    </h4>
                                                    <a href="{{ url('user/'.$question->user->personal_domain) }}" class="h_q_user">{{ $question->user->username }} / </a>
                                                    <span class="time" title="{{ $question->created_at }}">
                                                        {!! $question->created_at !!}
                                                    </span>
                                                    <span title="回答数" class="badge h_q_answer">{{ $question->answer_count }}</span>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="panel home-bg">
                            <div class="panel-heading home-heading">
                                <h3 class="panel-title">
                                    <a href="{{ route('question.index', ['filter' => 'hottest']) }}" class="section-title">热门问答</a>
                                    <a href="{{ route('question.index', ['filter' => 'hottest']) }}" class="section-more">more</a>
                                </h3>
                            </div>
                            <div class="panel-body home-body">
                                <ul class="list-group">
                                    @foreach($hot_questions as $question)
                                        <li class="list-group-item home-list-item">
                                            <div class="media">
                                                <a class="media-left home-media-left" href="{{ url('user/'.$question->user->personal_domain) }}">
                                                    <img src="{{ App\Helpers\Helpers::get_user_avatar($question->user_id, 'small') }}" class="avatar-32" alt="{{ $question->user->username }}">
                                                </a>
                                                <div class="media-body">
                                                    <h4 class="media-heading home-media-heading">
                                                        <a href="{{ url('question/show/' . $question->id) }}" title="{{ $question->title }}" class="h_q_title">{{ $question->title }}</a>
                                                    </h4>
                                                    <a href="{{ url('user/'.$question->user->personal_domain) }}" class="h_q_user">{{ $question->user->username }} / </a>
                                                    <span class="time" title="{{ $question->created_at }}">
                                                        {!! $question->created_at !!}
                                                    </span>
                                                    <span title="回答数" class="badge h_q_answer">{{ $question->answer_count }}</span>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="panel home-bg">
                            <div class="panel-heading home-heading">
                                <h3 class="panel-title">
                                    <a href="{{ route('blog.index') }}" class="section-title">最新博客</a>
                                    <a href="{{ route('blog.index') }}" class="section-more">more</a>
                                </h3>
                            </div>
                            <div class="panel-body home-body">

                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="panel home-bg">
                            <div class="panel-heading home-heading">
                                <h3 class="panel-title">
                                    <a href="{{ route('blog.index', ['filter' => 'hottest']) }}" class="section-title">热门博客</a>
                                    <a href="{{ route('blog.index', ['filter' => 'hottest']) }}" class="section-more">more</a>
                                </h3>
                            </div>
                            <div class="panel-body home-body">

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-md-3">
                <div class="well well-lg sign-in">
                    <p class="sign-in-title">今天，您签到了吗？</p>
                    <div class="sign-in-content">
                        <button type="button" class="btn btn-signin">签到</button>
                    </div>
                </div>

                <div class="panel panel-default side-tag">
                    <div class="panel-heading">
                        <h3 class="hot-tag"><i class="iconfont icon-huodongbiaoqian"></i>热门标签</h3>
                    </div>

                    <div class="panel-body">

                    </div>
                </div>

                <div class="panel panel-default side-rank">
                    <div class="panel-heading side-question-rank">
                        <h3 class="side-question-title"><i class="iconfont icon-paihangbang rank-icon"></i>排行榜</h3>
                        <div class="rank">
                            <a class="active-rank" href="javascript:void(0)">活跃</a>
                            <span> | </span>
                            <a class="credit-rank" href="javascript:void(0)">积分</a>
                        </div>
                    </div>

                    <div class="panel-body">
                        <ol class="list-group list-actives">
                            @foreach($active_users as $active_user)
                                <li class="list-active">
                                    <img src="{{ App\Helpers\Helpers::get_user_avatar($active_user->id, 'small') }}" class="avatar-27" alt="{{ $active_user->username }}">
                                    <a href="{{ url('user/'.$active_user->personal_domain) }}">{{ $active_user->username }}</a>
                                    <span class="credit"><span title="回答数">{{ $active_user->answer_count }}</span> / <span title="博客数">{{ $active_user->article_count }}</span></span>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('footer')
    <script src="{{ asset('libs/jquery-timeago/jquery.timeago.js') }}"></script>
    <script src="{{ asset('libs/jquery-timeago/locales/jquery.timeago.zh-CN.js') }}"></script>
    <script>
        $(".time").timeago();
    </script>
    <script>
        $('.btn-signin').click(function () {
            $(this).html('已签到');
            $(this).css('background-color', '#66d2c1');
        });
    </script>
    <script>
        $(function () {
            //活跃排行榜
            $('.active-rank').click(function () {
                $.get('/question/active_rank', function (html) {
                    $('.side-rank .panel-heading .rank a.credit-rank').css('color', '#ccc');
                    $('.side-rank .panel-body').empty();
                    $('.side-rank .panel-body').append(html);
                    $('.side-rank .panel-heading .rank a.active-rank').css('color', '#22d7bb');
                });
            });

            //积分排行榜
            $('.credit-rank').click(function () {
                $.get('/question/credit_rank', function (html) {
                    $('.side-rank .panel-heading .rank a.active-rank').css('color', '#ccc');
                    $('.side-rank .panel-body').empty();
                    $('.side-rank .panel-body').append(html);
                    $('.side-rank .panel-heading .rank a.credit-rank').css('color', '#22d7bb');
                });
            });
        });
    </script>
@stop
