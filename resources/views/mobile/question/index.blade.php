@extends('mobile.layouts.app')
@section('title')
    问答首页 | @parent
@stop
@section('css')
    <link rel="stylesheet" href="{{ asset('css/question/mobile-default.css') }}">
    <link rel="stylesheet" href="{{ asset('libs/mobile-tab/swiper/swiper.min.css') }}">
    <link rel="stylesheet" href="{{ asset('libs/mobile-tab/css/page_style.css') }}">
@stop

@section('content')
    <div class="swiper-container">
        <div class="my-pagination">
            <ul class="my-pagination-ul"></ul>
        </div>
        <div class="swiper-wrapper">

            <div class="swiper-slide">
                <div class="weui-cells question-list">
                    @foreach($newest_ques as $question)
                        <a class="weui-cell weui-cell_access" href="{{ url('question/show/' . $question->id) }}">
                            <div class="weui-cell__bd">
                                <p class="question-title">{{ $question->title }}</p>
                            </div>
                            <div class="weui-cell__ft">
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="swiper-slide">
                <div class="weui-cells question-list">
                    @foreach($hottest_ques as $question)
                        <a class="weui-cell weui-cell_access" href="{{ url('question/show/' . $question->id) }}">
                            <div class="weui-cell__bd">
                                <p class="question-title">{{ $question->title }}</p>
                            </div>
                            <div class="weui-cell__ft">
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="swiper-slide">
                <div class="weui-cells question-list">
                    @foreach($reward_ques as $question)
                        <a class="weui-cell weui-cell_access" href="{{ url('question/show/' . $question->id) }}">
                            <div class="weui-cell__bd">
                                <p class="question-title">{{ $question->title }}</p>
                            </div>
                            <div class="weui-cell__ft">
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="swiper-slide">
                <div class="weui-cells question-list">
                    @foreach($unanswer_ques as $question)
                        <a class="weui-cell weui-cell_access" href="{{ url('question/show/' . $question->id) }}">
                            <div class="weui-cell__bd">
                                <p class="question-title">{{ $question->title }}</p>
                            </div>
                            <div class="weui-cell__ft">
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="swiper-slide">
                <div class="weui-cells question-list">
                    @foreach($adopt_ques as $question)
                        <a class="weui-cell weui-cell_access" href="{{ url('question/show/' . $question->id) }}">
                            <div class="weui-cell__bd">
                                <p class="question-title">{{ $question->title }}</p>
                            </div>
                            <div class="weui-cell__ft">
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
@stop

@section('footer')
    <script src="{{ asset('libs/mobile-tab/swiper/swiper.min.js') }}"></script>
    <script>
        var mySwiper = new Swiper('.swiper-container', {
            pagination: '.my-pagination-ul',
            paginationClickable: true,
            paginationBulletRender: function (index, className) {
                switch (index) {
                    case 0:
                        name = '最新';
                        break;
                    case 1:
                        name = '热门';
                        break;
                    case 2:
                        name = '悬赏';
                        break;
                    case 3:
                        name = '零回答';
                        break;
                    case 4:
                        name = '已采纳';
                        break;
                    default:
                        name = '';
                }
                return '<li class="' + className + '">' + name + '</li>';
            }
        })
    </script>
@stop