@extends('pc.layouts.base')
@section('title')
    找回密码 | @parent
@stop
@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('css/auth.css')}}" />
@stop
@section('content')
@section('content')
    <div class="container">
        <div class="row">
            <div class="auth">
                <form class="form-horizontal bs-example bs-example-form" role="form" method="POST" action="{{ route('password.email') }}">
                    {{ csrf_field() }}
                    <strong>重置密码</strong>
                    <em class="title-eng">Reset Password</em>

                    <div class="first-step">
                        <div class="form-group forget-u">
                            <div class="input-group">
                                <span class="input-group-addon u-icon"><i class="fa fa-user fa-fw"></i></span>
                                <input type="text" class="form-control text" id="username" name="username" placeholder="请输入登录手机号 / 邮箱" value="{{ old('username') }}" autofocus>
                            </div>
                            <span class="help-block help-block-clear">
                            <em></em>
                        </span>
                        </div>
                        <div class="form-group p-captcha">
                            <div class="input-group">
                                <span class="input-group-addon c-icon"><i class="fa fa-check-square fa-fw"></i></span>
                                <input type="text" class="form-control text c-text" id="captcha" name="captcha" style="width: 150px" maxlength="4" placeholder="验证码">&nbsp;
                                <img id="captcha-img" class="captcha" src="{{ url('/captcha/verify') }}" >
                                <span class="glyphicon glyphicon-refresh loc" title="点击换一张"></span>
                            </div>
                            <span class="help-block help-block-clear">
                            <em></em>
                        </span>
                        </div>
                        <div class="form-group forget-verify">
                            <div class="input-group">
                                <span class="input-group-addon c-icon"><i class="fa fa-comment fa-fw"></i></span>
                                <input type="text" id="m_code" class="form-control text c-text" name="m_code" style="width: 160px;" maxlength="6" placeholder="验证码" autocomplete="off">&nbsp;
                                <button id="sendVerifySmsButton" type="button" class="btn btn-success btn-flat get-btn" style="width: 128px;">获取验证码</button>
                            </div>
                            <span class="help-block help-block-clear">
                            <em></em>
                        </span>
                        </div>
                        <div class="form-group forget-sub-btn">
                            <div class="submit-btn">
                                <button type="button" id="forget-btn" class="btn btn-success btn-block btn-flat forget-btn" onautocomplete="off" disabled>提 交</button>
                            </div>
                        </div>
                    </div>

                    <div class="second-step">
                        <div class="form-group password">
                            <div class="input-group">
                                <span class="input-group-addon p-icon"><i class="fa fa-key fa-fw"></i></span>
                                <input type="password" id="password" class="form-control text" name="password" placeholder="请输入新密码">
                            </div>
                            <span class="help-block help-block-clear">
                                <em></em>
                            </span>
                        </div>
                        <div class="form-group forget-sub-btn">
                            <div class="submit-btn">
                                <button type="button" id="reset-btn" class="btn btn-success btn-block btn-flat reset-btn" onautocomplete="off">提 交</button>
                            </div>
                        </div>
                    </div>

                    <div class="register-now">
                        <span>记起密码？</span>
                        <span><a href="{{ url('login') }}">返回登录</a></span>
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
    {{--图片验证码更换--}}
    <script>
        $('.glyphicon').click(function () {
            var captcha = $(this).prev('img');
            captcha.attr('src', '/captcha/verify?key=login&t='+(new Date()).getTime());
        });
        $('.captcha').click(function () {
            $(this).attr('src', '/captcha/verify?key=login&t='+(new Date()).getTime());
        });
    </script>
    <script>
        $(function () {
            $('.forget-btn').click(function () {
                var data = $("form").serialize();   //获取所有表单数据
                $.ajax({
                    type: 'POST',
                    url: '{{ url('forgot_submit') }}',
                    data: data,
                    cache: false,
                    success: function (res) {
                        if (res.code == 502) {
                            $('.forget-verify em').html(res.message['m_code']);
                        } else if (res.code == 501) {
                            $('.first-step').css('display', 'none');
                            $('.second-step').css('display', 'block');
                        }
                    },
                    error: function () {
                        layer.msg('系统错误！', {
                            icon: 2,
                            time: 2000,
                        });
                    }
                });
            });

            $('.reset-btn').click(function () {
                $.ajax({
                    type: 'POST',
                    url: '{{ url('reset_submit') }}',
                    data: {
                        _token: '{{csrf_token()}}',
                        'username': $('#username').val(),
                        'password': $('#password').val(),
                    },
                    cache: false,
                    success: function (res) {
                        if (res.code == 502) {
                            $('.password em').html(res.message['password']);
                        } else if (res.code == 913) {
                            layer.msg(res.message, {
                                icon: 6,
                                time: 2000,
                                end : function(){
                                    location.href='{{ url("/login") }}';
                                }
                            });
                        }
                    },
                    error: function () {
                        layer.msg('系统错误！', {
                            icon: 2,
                            time: 2000,
                        });
                    }
                });
            });
        });
    </script>
    {{--根据用户输入手机号显示获取手机验证码栏--}}
    <script>
        $(function () {
            $('#username').keyup(function () {
                var username = $('#username').val();    //获取输入手机号或邮箱
                var pattern = /^1[34578]\d{9}$/;    //验证手机号规则
                if (pattern.test($.trim(username)) == true) {
                    $('.forget-verify').fadeIn('slow');
                }else {
                    $('.forget-verify').fadeOut('slow');
                }
            });
        });

        $('#captcha, #username, #m_code, #password').focus(function () {
            $(this).parents('.forget-u').find('em').html('');
            $(this).parents('.p-captcha').find('em').html('');
            $(this).parents('.forget-verify').find('em').html('');
            $(this).parents('.password').find('em').html('');
        });
    </script>
    <script>
        //获取手机验证码前验证表单
        $(function () {
            $('.get-btn').click(function () {
                var self = $(this);
                var opts = $.extend(true, {}, options);
                var data = $("form").serialize();   //获取所有表单数据

                changeBtn(opts.language.sending, true);
                send(opts, data);
            });

            //发送验证码后返回状态
            function send(opts, data) {
                $.ajax({
                    type: 'POST',
                    url: '{{ url('mobile_verify_code') }}',
                    data: data,
                    cache: false,
                    success: function (res) {
                        if (res.code == 502) {
                            $('.p-captcha em').html(res.message['captcha']);
                            changeBtn(opts.language.oricon, false);
                        } else if (res.code == 895) {
                            $('.forget-u em').html(res.message);
                            changeBtn(opts.language.oricon, false);
                        } else if (res.code == 900) {
                            layer.msg(res.message, {
                                icon: 6,
                                time: 2000,
                            });
                            timer(opts.interval);
                            $('.forget-btn').attr('disabled', false);
                        } else if (res.code == 901) {
                            layer.msg(res.message, {
                                icon: 5,
                                time: 2000,
                            });
                            changeBtn(opts.language.oricon, false);
                        } else if (res.code == 899) {
                            layer.msg('您一分钟内已发送过验证码，您可重新输入已获取的手机验证码 ^_^', {
                                icon: 5,
                                time: 3000,
                            });
                            changeBtn(opts.language.oricon, false);
                            $('.forget-btn').attr('disabled', false);
                        }
                    },
                    error: function () {
                        layer.msg('系统错误！', {
                            icon: 2,
                            time: 2000,
                        });
                        changeBtn(opts.language.oricon, false);
                    }
                });
            }

            //倒计时
            function timer(seconds) {
                var timeId;
                var opts = $.extend(true, {}, options);
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
                $('.get-btn').html(content);
                $('.get-btn').val(content);
                $('.get-btn').prop('disabled', !!disabled);
            }
        });

        var options = {
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
