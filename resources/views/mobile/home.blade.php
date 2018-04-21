@extends('mobile.layouts.app')

@section('content')
    <div class="weui-panel weui-panel_access ques—blog">
        <div class="weui-panel__hd main-title">最新问答</div>
        <div class="weui-panel__bd">
            @foreach($new_questions as $question)
                <a href="{{ url('question/show/' . $question->id) }}" class="weui-media-box weui-media-box_appmsg list-item">
                    <div class="weui-media-box__hd list-item-img">
                        <img src="{{ App\Helpers\Helpers::get_user_avatar($question->user_id, 'small') }}" class="weui-media-box__thumb" alt="{{ $question->user->username }}">
                    </div>
                    <div class="weui-media-box__bd">
                        <h4 class="weui-media-box__title sub-title">{{ $question->title }}</h4>
                        <p class="weui-media-box__desc user_time">
                            {{ $question->user->username }} ·
                            <span class="time" title="{{ $question->created_at }}">
                            {!! $question->created_at !!}
                        </span>
                            <span class="count-icon"><i class="iconfont icon-huida"></i>{{ $question->answer_count }}</span>
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="weui-panel__ft">
            <a href="{{ url('/question') }}" class="weui-cell weui-cell_access weui-cell_link">
                <div class="weui-cell__bd more">查看更多</div>
                <span class="weui-cell__ft"></span>
            </a>
        </div>
    </div>

    <div class="weui-panel weui-panel_access ques—blog">
        <div class="weui-panel__hd main-title">最新博客</div>
        <div class="weui-panel__bd">
            @foreach($new_blogs as $blog)
                <a href="{{ url('blog/show/' . $blog->id) }}" class="weui-media-box weui-media-box_appmsg list-item">
                    <div class="weui-media-box__hd list-item-img">
                        <img src="{{ App\Helpers\Helpers::get_user_avatar($blog->user_id, 'small') }}" class="weui-media-box__thumb" alt="{{ $blog->user->username }}">
                    </div>
                    <div class="weui-media-box__bd">
                        <h4 class="weui-media-box__title sub-title">{{ $blog->title }}</h4>
                        <p class="weui-media-box__desc user_time">
                            {{ $blog->user->username }} ·
                            <span class="time" title="{{ $blog->created_at }}">
                            {!! $blog->created_at !!}
                        </span>
                            <span class="count-icon"><i class="iconfont icon-liulan"></i>{{ $blog->view_count }}</span>
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="weui-panel__ft">
            <a href="{{ url('/blog') }}" class="weui-cell weui-cell_access weui-cell_link">
                <div class="weui-cell__bd more">查看更多</div>
                <span class="weui-cell__ft"></span>
            </a>
        </div>
    </div>

    <div class="weui-panel weui-panel_access ques—blog">
        <div class="weui-panel__hd main-title">热门问答</div>
        <div class="weui-panel__bd">
            @foreach($hot_questions as $question)
                <a href="{{ url('question/show/' . $question->id) }}" class="weui-media-box weui-media-box_appmsg list-item">
                    <div class="weui-media-box__hd list-item-img">
                        <img src="{{ App\Helpers\Helpers::get_user_avatar($question->user_id, 'small') }}" class="weui-media-box__thumb" alt="{{ $question->user->username }}">
                    </div>
                    <div class="weui-media-box__bd">
                        <h4 class="weui-media-box__title sub-title">{{ $question->title }}</h4>
                        <p class="weui-media-box__desc user_time">
                            {{ $question->user->username }} ·
                            <span class="time" title="{{ $question->created_at }}">
                            {!! $question->created_at !!}
                        </span>
                            <span class="count-icon"><i class="iconfont icon-huida"></i>{{ $question->answer_count }}</span>
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="weui-panel__ft">
            <a href="{{ url('/question') }}" class="weui-cell weui-cell_access weui-cell_link">
                <div class="weui-cell__bd more">查看更多</div>
                <span class="weui-cell__ft"></span>
            </a>
        </div>
    </div>

    <div class="weui-panel weui-panel_access ques—blog">
        <div class="weui-panel__hd main-title">热门博客</div>
        <div class="weui-panel__bd">
            @foreach($hot_blogs as $blog)
                <a href="{{ url('blog/show/' . $blog->id) }}" class="weui-media-box weui-media-box_appmsg list-item">
                    <div class="weui-media-box__hd list-item-img">
                        <img src="{{ App\Helpers\Helpers::get_user_avatar($blog->user_id, 'small') }}" class="weui-media-box__thumb" alt="{{ $blog->user->username }}">
                    </div>
                    <div class="weui-media-box__bd">
                        <h4 class="weui-media-box__title sub-title">{{ $blog->title }}</h4>
                        <p class="weui-media-box__desc user_time">
                            {{ $blog->user->username }} ·
                            <span class="time" title="{{ $blog->created_at }}">
                            {!! $blog->created_at !!}
                        </span>
                            <span class="count-icon"><i class="iconfont icon-liulan"></i>{{ $blog->view_count }}</span>
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="weui-panel__ft">
            <a href="{{ url('/blog') }}" class="weui-cell weui-cell_access weui-cell_link">
                <div class="weui-cell__bd more">查看更多</div>
                <span class="weui-cell__ft"></span>
            </a>
        </div>
    </div>
@stop

@section('footer')
    <script src="{{ asset('libs/jquery-timeago/jquery.timeago.js') }}"></script>
    <script src="{{ asset('libs/jquery-timeago/locales/jquery.timeago.zh-CN.js') }}"></script>
    <script>
        $(".time").timeago();
    </script>
@stop