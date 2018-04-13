@extends('layouts.base')
@section('title')
    用户注册
@stop
@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('css/auth.css')}}" />
@stop
@section('content')
    <div class="container">
        <div class="row">
            <div class="auth">
                <form class="form-horizontal bs-example bs-example-form" role="form" method="POST" action="{{ route('register') }}">
                    {{ csrf_field() }}

                    <strong>用户注册</strong>
                    <em class="title-eng">User Register</em>
                    <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                        <div class="input-group">
                            <span class="input-group-addon u-icon{{ $errors->has('username') ? ' u-icon-clear' : '' }}"><i class="fa fa-user fa-fw"></i></span>
                            <input type="text" id="username" class="form-control text{{ $errors->has('username') ? ' text-clear' : '' }}" name="username" placeholder="用户名" value="{{ old('username') }}" autofocus>
                        </div>
                        @if ($errors->has('username'))
                            <span class="help-block help-block-clear">
                                <em>{{ $errors->first('username') }}</em>
                            </span>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <div class="input-group">
                            <span class="input-group-addon p-icon{{ $errors->has('password') ? ' p-icon-clear' : '' }}"><i class="fa fa-key fa-fw"></i></span>
                            <input type="password" id="password" class="form-control text{{ $errors->has('password') ? ' text-clear' : '' }}" name="password" placeholder="密码">
                        </div>
                        @if ($errors->has('password'))
                            <span class="help-block help-block-clear">
                                <em>{{ $errors->first('password') }}</em>
                            </span>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        <div class="input-group">
                            <span class="input-group-addon p-icon{{ $errors->has('password_confirmation') ? ' p-icon-clear' : '' }}"><i class="fa fa-lock fa-fw"></i></span>
                            <input type="password" id="password_confirm" class="form-control text{{ $errors->has('password_confirmation') ? ' text-clear' : '' }}" name="password_confirmation" placeholder="确认密码">
                        </div>
                        @if ($errors->has('password_confirmation'))
                            <span class="help-block help-block-clear">
                                <em>{{ $errors->first('password_confirmation') }}</em>
                            </span>
                        @endif
                    </div>

                    {{--<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <div class="input-group">
                            <span class="input-group-addon e-icon{{ $errors->has('email') ? ' e-icon-clear' : '' }}"><i class="fa fa-envelope-o fa-fw"></i></span>
                            <input type="email" id="email" class="form-control text{{ $errors->has('email') ? ' text-clear' : '' }}" name="email" placeholder="邮箱" value="{{ old('email') }}">
                        </div>
                        @if ($errors->has('email'))
                            <span class="help-block help-block-clear">
                                <em>{{ $errors->first('email') }}</em>
                            </span>
                        @elseif(session('message'))
                            <span class="help-block help-block-clear">
                                <em>{{ session('message') }}</em>
                            </span>
                        @endif
                    </div>--}}

                    <div class="form-group">
                        <div class="input-group{{ $errors->has('mobile') ? ' has-error' : '' }}">
                            <span class="input-group-addon m-icon{{ $errors->has('mobile') ? ' m-icon-clear' : '' }}"><i class="fa fa-mobile-phone fa-fw fa-lg"></i></span>
                            <input type="text" id="mobile" class="form-control text{{ $errors->has('mobile') ? ' text-clear' : '' }}" name="mobile" placeholder="手机号" value="{{ old('mobile') }}">
                        </div>
                        @if ($errors->has('mobile'))
                            <span class="help-block help-block-clear">
                                <em>{{ $errors->first('mobile') }}</em>
                            </span>
                        @endif
                    </div>

                    {{--<div class="form-group">
                        <div class="input-group{{ $errors->has('m_code') ? ' has-error' : '' }}">
                            <span class="input-group-addon c-icon{{ $errors->has('m_code') ? ' c-icon-clear' : '' }}"><i class="fa fa-comment fa-fw"></i></span>
                            <input type="text" id="m_code" class="form-control text c-text{{ $errors->has('m_code') ? ' text-clear' : '' }}" name="m_code" style="width: 180px;" maxlength="5" placeholder="验证码" autocomplete="off">&nbsp;
                            <button  id="get-code" type="button" class="btn btn-success btn-flat get-btn" style="width: 105px;">获取验证码</button>
                        </div>
                        @if ($errors->has('m_code'))
                            <span class="help-block help-block-clear">
                                <em>{{ $errors->first('m_code') }}</em>
                            </span>
                        @endif
                    </div>--}}

                    <div class="form-group">
                        <div class="submit-btn">
                            <button type="submit" id="register-btn" class="btn btn-success btn-block btn-flat reg-btn">注 册</button>
                        </div>
                    </div>

                    <div class="other-login">
                        <h6>社交账号登录</h6>
                        <div class="thirdparty">
                            <a href="{{ url('/auth/oauth/qq') }}" class="qq"><i class="fa fa-qq"></i></a>
                            <a href="{{ url('/auth/oauth/weibo') }}" class="weibo"><i class="fa fa-weibo"></i></a>
                            <a href="{{ url('/auth/oauth/weixin') }}" class="weixin"><i class="fa fa-weixin"></i></a>
                            <a href="{{ url('/auth/oauth/github') }}" class="github"><i class="fa fa-github"></i></a>
                        </div>
                    </div>

                    <div class="login-now">
                        <span>已有账号？</span>
                        <span><a href="{{ url('login') }}">立即登录</a></span>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('footer')
    {{--粒子背景插件及效果--}}
    <script src="{{asset('libs/particleground/jquery.particleground.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            //粒子背景特效
            $('body').particleground({
                dotColor: '#5cbdaa',
                lineColor: '#5cbdaa'
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            var time = 10;
            var timer;
            function showTime() {
                if (time < 1) {
                    clearTimeout(timer);
                    $('#get-code').attr('disabled', false);
                    $('#get-code').html('重新获取');
                    return false;
                } else {
                    time--;
                    $('#get-code').html('请稍后'+ time +'s');
                    //document.getElementById('get-code').innerHTML = '请稍后'+ time +'s';
                }
                timer = setTimeout(showTime, 1000);
            }
            $('#get-code').on('click', function () {
                $(this).attr('disabled', true);
                showTime();
            });
        });
    </script>
@stop
