@extends('pc.layouts.base')
@section('title')
    用户登录
@stop
@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('css/user/bind.css')}}" />
@stop
@section('content')
    <div class="container">
        <div class="row">
            <div class="bind">
                <form class="form-horizontal bs-example bs-example-form" role="form" method="POST" action="{{ url('/auth/bind') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="nickname" value="{{ $profile->nickname }}">
                    <input type="hidden" name="realname" value="{{ $profile->realname }}">
                    <input type="hidden" name="avatar" value="{{ $profile->avatar }}">
                    <input type="hidden" name="gender" value="{{ $profile->gender }}">
                    <input type="hidden" name="email" value="{{ $profile->email }}">
                    <input type="hidden" name="province" value="{{ $profile->province }}">
                    <input type="hidden" name="city" value="{{ $profile->city }}">
                    <input type="hidden" name="oauth_type" value="{{ $profile->oauth_type }}">
                    <input type="hidden" name="oauth_id" value="{{ $profile->oauth_id }}">
                    <input type="hidden" name="oauth_access_token" value="{{ $profile->oauth_access_token }}">
                    <input type="hidden" name="oauth_expires" value="{{ $profile->oauth_expires }}">
                    <input type="hidden" name="driver" value="{{ $driver }}">
                    <input type="hidden" name="weibo" value="{{ $profile->weibo }}">
                    <input type="hidden" name="github" value="{{ $profile->github }}">

                    <strong>完善基本资料</strong>
                    <em class="title-eng">Perfect The Info</em>
                    <div class="form-group form-group-bottom username">
                        <div class="col-sm-12 form-input input-group">
                            <input type="text" class="form-control text" id="username" name="username" placeholder="用户名" value="{{ $profile->nickname }}">
                        </div>
                        <span class="help-block help-block-clear">
                            <em></em>
                        </span>
                    </div>
                    <div class="form-group form-group-bottom mobile">
                        <div class="col-sm-12 form-input input-group">
                            <input type="text" class="form-control text" id="mobile" name="mobile" placeholder="手机号" value="{{ old('mobile') }}">
                        </div>
                        <span class="help-block help-block-clear">
                            <em></em>
                        </span>
                    </div>
                    <div class="form-group form-group-bottom password">
                        <div class="col-sm-12 form-input input-group">
                            <input type="password" class="form-control text" id="password" name="password" placeholder="密码">
                        </div>
                        <span class="help-block help-block-clear">
                            <em></em>
                        </span>
                    </div>
                    <div class="form-group form-group-bottom verify">
                        <div class="input-group">
                            <input type="text" class="form-control text" id="verify_code" name="verify_code" maxlength="6" placeholder="请输入验证码" style="width: 190px;">
                            <button type="button" class="btn btn-lg get-vcode" style="width: 128px;">获取验证码</button>
                        </div>
                        <span class="help-block help-block-clear">
                            <em></em>
                        </span>
                    </div>
                    {{--<div class="form-group form-group-bottom captcha">
                        <div class="input-group">
                            <input type="text" class="form-control text" id="captcha" name="captcha" style="width: 180px" maxlength="5" placeholder="验证码">&nbsp;
                            <img id="captcha-img" src="{{ url('/captcha/verify') }}" >
                            <span class="glyphicon glyphicon-refresh loc" title="点击换一张"></span>
                        </div>
                        <span class="help-block help-block-clear">
                            <em>{{ $errors->first('captcha') }}</em>
                        </span>
                    </div>--}}
                    <div class="form-group">
                        <div class="submit-btn">
                            <button type="button" id="login-btn" class="btn btn-success btn-block btn-flat submit" data-loading-text="正在提交..." onautocomplete="off">提 交</button>
                        </div>
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
        $('.glyphicon').click(function () {
            var captcha = $(this).prev('img');
            captcha.attr('src', '/captcha/verify?key=login&t='+(new Date()).getTime());
        })
    </script>
    {{--按下回车登录相当于鼠标点击登录按钮--}}
    <script>
        $(document).ready(function () {
            $(document).keydown(function (e) {
                var event = document.all ? window.event : e; //IE浏览器事件绑定在全局window(唯独有all属性)，其他浏览器事件局部变量传入方法中
                if (event.keyCode === 13) { //键码为13，按下enter键
                    $('#login-btn').click();
                }
            });
        });
    </script>
    <script>
        $(function () {
            /*$('#username').focusout(function () {
                var username = $('#username').val();
                if (username == '') {
                    $('#username').parents('.username').find('.help-block em').html('用户名 不可为空。');
                } else {
                    $('#username').parents('.username').find('.help-block em').html('');
                    $.ajax({
                        url: '{{ url('/auth/bind_verify') }}',
                        type: 'post',
                        data: {
                            _token: '{{csrf_token()}}',
                            'username': username,
                        },
                        cache: false,
                        success: function(res){
                            if (res.code == 906) {
                                $('#username').parents('.username').find('.help-block em').html(res.message);
                            } else if (res.code == 907) {
                                $('#username').parents('.username').find('.help-block em').html(res.message);
                            }
                        },
                        error: function(){
                            layer.msg('系统错误！', {
                                icon: 2,
                                time: 2000,
                            });
                        }
                    });
                }
            });*/
            $('#password').focusout(function () {
                var password = $('#password').val();
                if (password == '') {
                    $('#password').parents('.password').find('.help-block em').html('密码 不可为空。');
                } else {
                    $('#password').parents('.password').find('.help-block em').html('');
                    $.ajax({
                        url: '{{ url('/auth/bind_verify') }}',
                        type: 'post',
                        data: {
                            _token: '{{csrf_token()}}',
                            'password': password,
                        },
                        cache: false,
                        success: function(res){
                            if (res.code == 904) {
                                $('#password').parents('.password').find('.help-block em').html(res.message);
                            } else if (res.code == 502) {
                                $('#password').parents('.password').find('.help-block em').html(res.message);
                            }
                        },
                        error: function(){
                            layer.msg('系统错误！', {
                                icon: 2,
                                time: 2000,
                            });
                        }
                    });
                }
            });
            $('#mobile').focusout(function () {
                var username = $('#username').val();
                var mobile = $('#mobile').val();
                if (mobile == '') {
                    $('#mobile').parents('.mobile').find('.help-block em').html('手机号 不可为空。');
                    $('.password').fadeOut('slow');
                } else {
                    $('#mobile').parents('.mobile').find('.help-block em').html('');
                    $.ajax({
                        url: '{{ url('/auth/bind_verify') }}',
                        type: 'post',
                        data: {
                            _token: '{{csrf_token()}}',
                            'username': username,
                            'mobile': mobile,
                        },
                        cache: false,
                        success: function(res){
                            if (res.code == 902) {
                                $('#mobile').parents('.mobile').find('.help-block em').html(res.message);
                                $('.password').fadeOut('slow');
                            } else if (res.code == 903) {
                                $('#username').parents('.username').find('.help-block em').html('用户名可用。');
                                $('#mobile').parents('.mobile').find('.help-block em').html(res.message);
                                $('.password').fadeIn('slow');
                            } else if (res.code == 502) {
                                $('#mobile').parents('.mobile').find('.help-block em').html(res.message);
                            } else if (res.code == 906) {
                                $('#username').parents('.username').find('.help-block em').html(res.message);
                            }
                        },
                        error: function(){
                            layer.msg('系统错误！', {
                                icon: 2,
                                time: 2000,
                            });
                        }
                    });
                }
            });
            $('#verify_code').focusout(function () {
                var verify_code = $('#verify_code').val();
                if (verify_code == '') {
                    $('#verify_code').parents('.verify').find('.help-block em').html('验证码 不能为空。');
                } else {
                    $('#verify_code').parents('.verify').find('.help-block em').html('');
                    $.ajax({
                        url: '{{ url('/auth/bind_verify') }}',
                        type: 'post',
                        data: {
                            _token: '{{csrf_token()}}',
                            'verify_code': verify_code,
                        },
                        cache: false,
                        success: function(res){
                            if (res.code == 905) {
                                $('#verify_code').parents('.verify').find('.help-block em').html(res.message);
                            } else if (res.code == 502) {
                                $('#verify_code').parents('.verify').find('.help-block em').html(res.message);
                            }
                        },
                        error: function(){
                            layer.msg('系统错误！', {
                                icon: 2,
                                time: 2000,
                            });
                        }
                    });
                }
            });
        });
    </script>
    <script>
        $(function () {
            $('.submit').click(function () {
                var data = $("form").serialize();

                $.ajax({
                    url: '{{ url('/auth/bind') }}',
                    type: 'post',
                    data: data,
                    cache: false,
                    success: function (res) {
                        if (res.code == 502 && $('.bind .password').css('display') != 'none') {
                            $('#username').parents('.username').find('.help-block em').html(res.message['username']);
                            $('#password').parents('.password').find('.help-block em').html(res.message['password']);
                            $('#mobile').parents('.mobile').find('.help-block em').html(res.message['mobile']);
                            $('#verify_code').parents('.verify').find('.help-block em').html(res.message['verify_code']);
                        } else if (res.code == 502 && $('.bind .password').css('display') == 'none') {
                            $('#username').parents('.username').find('.help-block em').html(res.message['username']);
                            $('#mobile').parents('.mobile').find('.help-block em').html(res.message['mobile']);
                            $('#verify_code').parents('.verify').find('.help-block em').html(res.message['verify_code']);
                        } else if (res.code == 501) {
                            layer.msg(res.message, {
                                icon: 6,
                                time: 3000,
                                end : function(){
                                    location.href='{{ url("/") }}';
                                }
                            });
                        } else if (res.code == 911) {
                            layer.msg(res.message, {
                                icon: 2,
                                time: 2000,
                            });
                        } else if (res.code == 912) {
                            layer.msg(res.message, {
                                icon: 5,
                                time: 2000,
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

    <script>
        //手机获取验证码
        $(function () {
            $('.get-vcode').click(function () {
                var self = $(this);
                var opts = $.extend(true, {}, options);

                changeBtn(opts.language.sending, true);
                send(opts);
            });

            //发送验证码后返回状态
            function send(opts) {
                $.ajax({
                    type: 'post',
                    url: '{{ url('/auth/get_mobile_code') }}',
                    data: {
                        _token: '{{csrf_token()}}',
                        'username': $('#username').val(),
                        'password': $('#password').val(),
                        'mobile': $('#mobile').val(),
                    },
                    cache: false,
                    success: function (res) {
                        if (res.code == 502) {
                            $('#username').parents('.username').find('.help-block em').html(res.message['username']);
                            $('#password').parents('.password').find('.help-block em').html(res.message['password']);
                            $('#mobile').parents('.mobile').find('.help-block em').html(res.message['mobile']);
                            changeBtn(opts.language.oricon, false);
                        } else if (res.code == 906) {   //用户名已存在
                            $('#username').parents('.username').find('.help-block em').html('');
                            $('#username').parents('.username').find('.help-block em').html(res.message['username']);
                            changeBtn(opts.language.oricon, false);
                        } else if (res.code == 903) {   //手机号可用
                            $('#mobile').parents('.mobile').find('.help-block em').html(res.message['mobile']);
                            $('.password').fadeIn('slow');
                            changeBtn(opts.language.oricon, false);
                        } else if (res.code == 900) {   //发送成功
                            layer.msg(res.message, {
                                icon: 6,
                                time: 2000,
                            });
                            timer(opts.interval);
                        } else if (res.code == 901) {   //发送失败
                            layer.msg(res.message, {
                                icon: 5,
                                time: 2000,
                            });
                            changeBtn(opts.language.oricon, false);
                        } else if (res.code == 899) {   //重复获取
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
                $('.get-vcode').html(content);
                $('.get-vcode').val(content);
                $('.get-vcode').prop('disabled', !!disabled);
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
