@extends('layouts.base')
@section('title')
    用户注册 | @parent
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
                        @else
                            <span class="help-block help-block-clear username">
                                <em></em>
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
                        @else
                            <span class="help-block help-block-clear password">
                                <em></em>
                            </span>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        <div class="input-group">
                            <span class="input-group-addon p-icon{{ $errors->has('password_confirmation') ? ' p-icon-clear' : '' }}"><i class="fa fa-lock fa-fw"></i></span>
                            <input type="password" id="password_confirmation" class="form-control text{{ $errors->has('password_confirmation') ? ' text-clear' : '' }}" name="password_confirmation" placeholder="确认密码">
                        </div>
                        @if ($errors->has('password_confirmation'))
                            <span class="help-block help-block-clear">
                                <em>{{ $errors->first('password_confirmation') }}</em>
                            </span>
                        @else
                            <span class="help-block help-block-clear password_confirmation">
                                <em></em>
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
                            <input type="text" id="mobile" name="mobile" class="form-control text{{ $errors->has('mobile') ? ' text-clear' : '' }}" placeholder="手机号" value="{{ old('mobile') }}">
                        </div>
                        @if ($errors->has('mobile'))
                            <span class="help-block help-block-clear">
                                <em>{{ $errors->first('mobile') }}</em>
                            </span>
                        @else
                            <span class="help-block help-block-clear mobile">
                                <em></em>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <div class="input-group{{ $errors->has('m_code') ? ' has-error' : '' }}">
                            <span class="input-group-addon c-icon{{ $errors->has('m_code') ? ' c-icon-clear' : '' }}"><i class="fa fa-comment fa-fw"></i></span>
                            <input type="text" id="m_code" class="form-control text c-text{{ $errors->has('m_code') ? ' text-clear' : '' }}" name="m_code" style="width: 160px;" maxlength="6" placeholder="验证码" autocomplete="off">&nbsp;
                            <button id="sendVerifySmsButton" type="button" class="btn btn-success btn-flat get-btn" style="width: 128px;">获取验证码</button>
                        </div>
                        @if ($errors->has('m_code'))
                            <span class="help-block help-block-clear">
                                <em>{{ $errors->first('m_code') }}</em>
                            </span>
                        @endif
                    </div>

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
    <script src="{{asset('libs/particleground/jquery.particleground.min.js')}}"></script>
    {{--粒子背景插件及效果--}}
    <script src="{{asset('js/laravel-sms.js')}}"></script>
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
        //聚焦隐藏错误提示
        $(function () {
            $('#username').focus(function () {
                $('.username em').html('');
                //$(this).val('');
            });
            $('#password').focus(function () {
                $('.password em').html('');
                //$(this).val('');
            });
            $('#password_confirmation').focus(function () {
                $('.password_confirmation em').html('');
                //$(this).val('');
            });
            $('#mobile').focus(function () {
                $('.mobile em').html('');
                //$(this).val('');
            });
        });
    </script>
    <script>
        //获取验证码
        $(function () {
            $('#sendVerifySmsButton').click(function () {
                var self = $(this);
                var opts = $.extend(true, {}, $.fn.sms.defaults);

                changeBtn(opts.language.sending, true);
                send(opts);
            });

            /*function btnOriginCon() {
                var btnOriginContent = self.html() || self.val() || '';
                return btnOriginContent;
            }*/

            //发送验证码后返回状态
            function send(opts) {
                $.ajax({
                    type: 'post',
                    url: '{{ url('note_verify_code') }}',
                    data: {
                        _token: '{{csrf_token()}}',
                        'mobile': $('#mobile').val(),
                        'username': $('#username').val(),
                        'password': $('#password').val(),
                        'password_confirmation': $('#password_confirmation').val(),
                    },
                    cache: false,
                    success: function (res) {
                        if (res.code == 502) {
                            //console.log(res.message)
                            $('.mobile em').html(res.message['mobile']);
                            $('.username em').html(res.message['username']);
                            $('.password em').html(res.message['password']);
                            $('.password_confirmation em').html(res.message['password_confirmation']);
                            changeBtn(opts.language.oricon, false);
                        } else if (res.code == 900) {
                            layer.msg(res.message, {
                                icon: 6,
                                time: 2000,
                            });
                            timer(opts.interval);
                        } else if (res.code == 901) {
                            layer.msg(res.message, {
                                icon: 5,
                                time: 2000,
                            });
                            changeBtn(opts.language.oricon, false);
                        } else if (res.code == 899) {
                            layer.msg(res.message, {
                                icon: 5,
                                time: 3000,
                            });
                            changeBtn(opts.language.oricon, false);
                        }
                    },
                    error: function () {
                        layer.msg('系统错误！', {
                            icon: 2,
                            time: 2000,
                        });
                    }
                });
            }

            //倒计时
            function timer(seconds) {
                var timeId;
                var opts = $.extend(true, {}, $.fn.sms.defaults);
                var btnText = opts.language.resendable;
                btnText = typeof btnText === 'string' ? btnText : '';
                if (seconds < 0) {
                    clearTimeout(timeId);
                    changeBtn(opts.language.oricon, false);
                } else {
                    timeId = setTimeout(function() {
                        clearTimeout(timeId);
                        changeBtn(btnText.replace('60 秒后再次获取', (seconds--) + ' 秒后再次获取'), true);
                        timer(seconds);
                    }, 1000);
                }
            }

            //发送验证码按钮
            function changeBtn(content, disabled) {
                $('#sendVerifySmsButton').html(content);
                $('#sendVerifySmsButton').val(content);
                $('#sendVerifySmsButton').prop('disabled', !!disabled);
            }
        });

        $.fn.sms.defaults = {
            token       : null,
            interval    : 60,
            voice       : false,
            requestUrl  : null,
            requestData : null,
            notify      : function (msg, type) {
                alert(msg);
            },
            language    : {
                oricon     : '获取验证码',
                sending    : '短信发送中...',
                failed     : '请求失败，请重试',
                resendable : '60 秒后再次获取'
            }
        };
    </script>
@stop
