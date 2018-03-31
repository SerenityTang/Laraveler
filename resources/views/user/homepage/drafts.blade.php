@extends('layouts.app')
@section('title')
    {{ $user->username }} 的关注 | @parent
@stop
@section('css')
    <link href="{{ asset('css/user/default.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/tabs-homepage/css/responsive-tabs.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/tabs-homepage/css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('libs/zeroModal/zeroModal.css') }}">
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
                    <h4 class="title">我的草稿</h4>

                    <div id="horizontalTab" class="tab-top">
                        <ul>
                            <li><a href="#question-draft">问答草稿</a></li>
                            <li><a href="#blog-draft">博客草稿</a></li>
                        </ul>

                        <div id="question-draft">
                            <ul class="list-group">
                                @foreach($questions as $question)
                                    <li class="list-group-item list-tab">
                                        <div class="row content">
                                            <div class="col-md-7">
                                                @if($question->title == null)
                                                    <a href="{{ url('question/show_edit/' . $question->id) }}" title="" class="title">
                                                        此问答未定义标题
                                                    </a>
                                                @else
                                                    <a href="{{ url('question/show_edit/' . $question->id) }}" title="{{ $question->title }}" class="title">
                                                        {{ str_limit($question->title, 60) }}
                                                    </a>
                                                @endif
                                                <span class="seperator">保存于</span>
                                            </div>
                                            <div class="col-md-3 create-time" title="{{ $question->created_at }}">
                                                {!! $question->created_at !!}
                                            </div>

                                            <div class="col-md-2 edit-icon">
                                                <a href="{{ url('question/show_edit/' . $question->id) }}">编辑</a>
                                                <span>·</span>
                                                <a href="javascript:void(0)" class="q-reject-icon" data-question-id="{{ $question->id }}">舍弃</a>
                                            </div>

                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div id="blog-draft">
                            <ul class="list-group">
                                @foreach($blogs as $blog)
                                    <li class="list-group-item list-tab">
                                        <div class="row content">
                                            <div class="col-md-7">
                                                @if($blog->title == null)
                                                    <a href="{{ url('question/show/' . $blog->id) }}" title="" class="title">此博客未定义标题</a>
                                                @else
                                                    <a href="{{ url('question/show/' . $blog->id) }}" title="{{ $blog->title }}" class="title">{{ str_limit($blog->title, 60) }}</a>
                                                @endif
                                                <span class="seperator">保存于</span>
                                            </div>

                                            <div class="col-md-3 create-time" title="{{ $blog->created_at }}">
                                                {!! $blog->created_at !!}
                                            </div>

                                            <div class="col-md-2 edit-icon">
                                                <a href="">编辑</a>
                                                <span>·</span>
                                                <a href="javascript:void(0)" class="b-reject-icon" data-blog-id="{{ $blog->id }}">舍弃</a>
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
    <script type="text/javascript" src="{{ url('libs/zeroModal/zeroModal.min.js') }}"></script>
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
        //舍弃草稿
        $('.q-reject-icon').click(function () {
            var question_id = $(this).data('question-id');
            zeroModal.confirm("确定舍弃问题草稿吗？", function() {
                $.ajax({
                    url : "{{url('/question/abandon/[id]')}}".replace('[id]', question_id),
                    data : {
                        _token: '{{csrf_token()}}',
                    },
                    dataType : "json",
                    type : "POST",
                    success : function (res) {
                        if(res.code == 704){
                            layer.msg(res.message, {
                                icon: 6,//提示的样式
                                time: 2000, //2秒关闭（如果不配置，默认是3秒）//设置后不需要自己写定时关闭了，单位是毫秒
                                /*end : function(){
                                    location.href='{{ url("/question") }}';
                                }*/
                            });
                        } else if (res.code == 705) {
                            zeroModal.error(res.message);
                        }

                    },
                    error : function () {
                        zeroModal.error('系统错误！');
                    }
                });
            });
        });

        $('.b-reject-icon').click(function () {
            var blog_id = $(this).data('blog-id');
            zeroModal.confirm("确定舍弃博客草稿吗？", function() {
                $.ajax({
                    url : "{{url('/blog/abandon/[id]')}}".replace('[id]', blog_id),
                    data : {
                        _token: '{{csrf_token()}}',
                    },
                    dataType : "json",
                    type : "POST",
                    success : function (res) {
                        if(res.code == 708){
                            layer.msg(res.message, {
                                icon: 6,//提示的样式
                                time: 2000, //2秒关闭（如果不配置，默认是3秒）//设置后不需要自己写定时关闭了，单位是毫秒
                                /*end : function(){
                                    location.href='{{ url("/question") }}';
                                }*/
                            });
                        } else if (res.code == 709) {
                            zeroModal.error(res.message);
                        }

                    },
                    error : function () {
                        zeroModal.error('系统错误！');
                    }
                });
            });
        });
    </script>
@stop
