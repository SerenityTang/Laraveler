@extends('pc.layouts.app')
@section('title')
{{ $user->username }} 通知私信 | @parent
@stop
@section('css')
    <link href="{{ asset('css/user/default.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/switch/lib/honeySwitch.css') }}" rel="stylesheet">
@stop

@section('content')
<div class="container main-container">
    <div class="row">
        {{--左侧菜单--}}
        <div class="col-md-3">
            @include('pc.user.partials.setting-side-menu')
        </div>

        <div class="col-md-9">
            <div class="panel panel-default right-container">
                <h4 class="title"><i class="iconfont icon-xiaoxi1"></i>通知私信</h4>
                <div class="common-row">
                    <div class="cell-left c-l">问答被回复时</div>
                    <div class="cell-right"><span class="switch-off" id="ques-ans"></span></div>
                </div>
                <div class="common-row">
                    <div class="cell-left c-l">博客被评论时</div>
                    <div class="cell-right"><span class="switch-off" id="blog-comm"></span></div>
                </div>
                <div class="common-row">
                    <div class="cell-left c-l">被用户关注时</div>
                    <div class="cell-right"><span class="switch-off" id="user-atte"></span></div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('footer')
    <script src="{{ asset('libs/switch/lib/honeySwitch.js') }}"></script>
    <script>
        $('#ques-ans').click(function () {
            $.ajax({
                type: 'POST',
                url : '{{ url('user/notify') }}',
                data: {
                    _token: '{{csrf_token()}}',
                    'type' : 0,
                },
                success: function (data) {
                    console.log(data);
                },
                error: function () {
                    layer.msg('系统错误！', {
                        icon: 2,
                        time: 2000,
                    });
                }
            })
        });
        $('#blog-comm').click(function () {
            $.ajax({
                type: 'POST',
                url : '{{ url('user/notify') }}',
                data: {
                    _token: '{{csrf_token()}}',
                    'type' : 1,
                },
                success: function (data) {
                    console.log(data);
                },
                error: function () {
                    layer.msg('系统错误！', {
                        icon: 2,
                        time: 2000,
                    });
                }
            })
        });
        $('#user-atte').click(function () {
            $.ajax({
                type: 'POST',
                url : '{{ url('user/notify') }}',
                data: {
                    _token: '{{csrf_token()}}',
                    'type' : 2,
                },
                success: function (data) {
                    console.log(data);
                },
                error: function () {
                    layer.msg('系统错误！', {
                        icon: 2,
                        time: 2000,
                    });
                }
            })
        });
    </script>
@stop