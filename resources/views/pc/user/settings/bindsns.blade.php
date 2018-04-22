@extends('pc.layouts.app')
@section('title')
    {{ $user->username }} 账号绑定 | @parent
@stop
@section('css')
    <link href="{{ asset('css/user/default.css') }}" rel="stylesheet">
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
                    <h4 class="title"><i class="iconfont icon-msnui-bind-circle"></i>账号绑定</h4>
                    <div class="row bind-account">
                        <div class="col-sm-4 bind-type">
                            <i class="fa fa-qq qq"></i>
                            <span>Q Q</span>
                        </div>
                        <div class="col-sm-4">
                            @if(\App\Helpers\Helpers::bindsns(Auth::user()->id, 'qq') == null)
                                <a href="{{ url('auth/oauth/qq') }}?{{ http_build_query(['redirect_uri' => request()->url()]) }}" role="button" class="btn bind-btn">
                                    绑定账号
                                </a>
                            @else
                                <a class="btn binded"><i class="iconfont icon-dagou"></i>已绑定</a>
                                <a class="btn unbinded">
                                    <i class="iconfont icon-bind-remove"></i>解除绑定
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="row bind-account">
                        <div class="col-sm-4 bind-type">
                            <i class="fa fa-weixin weixin"></i>
                            <span>微信</span>
                        </div>
                        <div class="col-sm-4">
                            @if(\App\Helpers\Helpers::bindsns(Auth::user()->id, 'weixin') == null)
                                <a href="{{ url('auth/oauth/weixin') }}?{{ http_build_query(['redirect_uri' => request()->url()]) }}" role="button" class="btn bind-btn">
                                    绑定账号
                                </a>
                            @else
                                <a class="btn binded"><i class="iconfont icon-dagou"></i>已绑定</a>
                                <a class="btn unbinded">
                                    <i class="iconfont icon-bind-remove"></i>解除绑定
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="row bind-account">
                        <div class="col-sm-4 bind-type">
                            <i class="fa fa-weibo weibo"></i>
                            <span>微博</span>
                        </div>
                        <div class="col-sm-4">
                            @if(\App\Helpers\Helpers::bindsns(Auth::user()->id, 'weibo') == null)
                                <a href="{{ url('auth/oauth/weibo') }}?{{ http_build_query(['redirect_uri' => request()->url()]) }}" role="button" class="btn bind-btn">
                                    绑定账号
                                </a>
                            @else
                                <a class="btn binded"><i class="iconfont icon-dagou"></i>已绑定</a>
                                <a href="{{ url('user/unbind/weibo') }}?{{ http_build_query(['redirect_uri' => request()->url()]) }}" class="btn unbinded">
                                    <i class="iconfont icon-bind-remove"></i>解除绑定
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="row bind-account">
                        <div class="col-sm-4 bind-type">
                            <i class="fa fa-github github"></i>
                            <span>Github</span>
                        </div>
                        <div class="col-sm-4">
                            @if(\App\Helpers\Helpers::bindsns(Auth::user()->id, 'github') == null)
                                <a href="{{ url('auth/oauth/github') }}?{{ http_build_query(['redirect_uri' => request()->url()]) }}" role="button" class="btn bind-btn">
                                    绑定账号
                                </a>
                            @else
                                <a class="btn binded"><i class="iconfont icon-dagou"></i>已绑定</a>
                                <a class="btn unbinded">
                                    <i class="iconfont icon-bind-remove"></i>解除绑定
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="row bind-account">
                        <div class="col-sm-4 bind-type">
                            <i class="fa fa-google google"></i>
                            <span>Google</span>
                        </div>
                        <div class="col-sm-4">
                            <a role="button" class="btn bind-btn">绑定账号</a>
                        </div>
                    </div>
                    <div class="row bind-account">
                        <div class="col-sm-4 bind-type">
                            <i class="fa fa-facebook facebook"></i>
                            <span>Facebook</span>
                        </div>
                        <div class="col-sm-4">
                            <a role="button" class="btn bind-btn">绑定账号</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('footer')
    <script>
        //鼠标经过显示解除绑定按钮
        $(function () {
            $('.bind-account').hover(function () {
                var icon = $(this);
                icon.find('a.unbinded').css('display', 'inline-block');
            }, function () {
                var icon = $(this);
                icon.find('a.unbinded').css('display', 'none');
            });
        })
    </script>
@stop