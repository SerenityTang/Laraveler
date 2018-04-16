@extends('layouts.base')
@section('title')
    用户登录 | @parent
@stop
@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('css/auth.css')}}" />
@stop
@section('content')
    <div class="container">
        <div class="row">
            <div class="auth">
                <form class="form-horizontal bs-example bs-example-form" role="form" method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}
                    {{--显示操作成功与否提示--}}
                    @if ( session('message') )
                        <div class="alert @if(session('message_type') === 1) alert-success @else alert-danger @endif alert-dismissible login-toptip" role="alert" id="alert_message">
                            {{--<button type="button" class="close btn-close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>--}}
                            <p class="message"><i class="fa fa-check-circle fa-lg fa-fw"></i>{{ session('message') }}</p>
                        </div>
                    @endif
                    <strong>用户登录</strong>
                    <em class="title-eng">User Login</em>
                    <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                        <div class="input-group">
                            <span class="input-group-addon u-icon{{ $errors->has('username') ? ' u-icon-clear' : '' }}"><i class="fa fa-user fa-fw"></i></span>
                            <input type="text" class="form-control text{{ $errors->has('username') ? ' text-clear' : '' }}" id="username" name="username" placeholder="用户名 / 手机号 / 邮箱" value="{{ old('username') }}" autofocus>
                        </div>
                        @if ($errors->has('username'))
                            <span class="help-block help-block-clear">
                                <em>{{ $errors->first('username') }}</em>
                            </span>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <div class="input-group">
                            <span class="input-group-addon p-icon{{ $errors->has('password') ? ' p-icon-clear' : '' }}"><i class="fa fa-lock fa-fw"></i></span>
                            <input type="password" class="form-control text{{ $errors->has('password') ? ' text-clear' : '' }}" id="password" name="password" placeholder="密码" >
                        </div>
                        @if ($errors->has('password'))
                            <span class="help-block help-block-clear">
                                <em>{{ $errors->first('password') }}</em>
                            </span>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('captcha') ? ' has-error' : '' }}" style="margin-bottom: 8px;">
                        <div class="input-group">
                            <span class="input-group-addon c-icon{{ $errors->has('captcha') ? ' c-icon-clear' : '' }}"><i class="fa fa-check-square fa-fw"></i></span>
                            <input type="text" class="form-control text c-text{{ $errors->has('captcha') ? ' text-clear' : '' }}" id="captcha" name="captcha" style="width: 150px" maxlength="5" placeholder="验证码">&nbsp;
                            <img id="captcha-img" src="{{ url('/captcha/verify') }}" >
                            <span class="glyphicon glyphicon-refresh loc" title="点击换一张"></span>
                        </div>
                        @if ($errors->has('captcha'))
                            <span class="help-block help-block-clear">
                                <em>{{ $errors->first('captcha') }}</em>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label style="margin-left: 15px;">
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} /> 保持登录
                            </label>
                            <a href="{{ route('password.request') }}" style="display: inline-block;float: right;padding-right: 7px;" class="forget-pwd">忘记密码？</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="submit-btn">
                            <button type="submit" id="login-btn" class="btn btn-success btn-block btn-flat log-btn" data-loading-text="正在登录..." onautocomplete="off">登 录</button>
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

                    <div class="register-now">
                        <span>还没有账号？</span>
                        <span><a href="{{ url('register') }}">立即注册</a></span>
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
            captcha.attr('src', 'captcha/verify?key=login&t='+(new Date()).getTime());
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
    {{--操作成功与否提示自动隐藏--}}
    <script>
        $("#alert_message").delay(8000).fadeOut(500);
    </script>
    {{--<script>
        $(document).ready(function () {
            $("#username,#password,#captcha").on('focus',function () {
                $(this).tooltip('destroy').parents('.form-group').removeClass('has-erroerrors
            });

            $('#login-btn').click(function () {
                var $btn = $(this).button('loading'); //改变登录按钮text

                var username = $.trim($('#username').val());
                var password = $.trim($('#password').val());
                var captcha = $.trim($('#captcha').val());
                if (username === "") {
                    $("#username").tooltip({placement : "top",title : '账号不能为空',trigger : 'manual'})
                        .tooltip('show')
                        .parents('.form-group').addClass('has-erroerrors                    $btn.button('reset');
                    return false;
                } else if (password === "") {
                    $("#password").tooltip({placement : "bottom",title : '密码不能为空',trigger : 'manual'})
                        .tooltip('show')
                        .parents('.form-group').addClass('has-erroerrors                    $btn.button('reset');
                    return false;
                } else if(captcha !== undefined && captcha === "") {
                    $("#captcha").tooltip({placement : "bottom",title : '验证码不能为空',trigger : 'manual'})
                        .tooltip('show')
                        .parents('.form-group').addClass('has-erroerrors                    $btn.button('reset');
                    return false;
                } else {
                    $.ajax({
                        url : "{{url('/login')}}",
                        data : $("form").serializeArray(),
                        dataType : "json",
                        type : "POST",
                        success : function (res) {
                            if(res.code == 40101){
                                $("#captcha-img").click();
                                $("#captcha").val('');
                                layer.msg(res.message);
                                $btn.button('reset');
                            }else{
                                window.location = "/";
                            }

                        },
                        erroerrorsnction () {
                            $("#captcha-img").click();
                            $("#code").val('');
                            layer.msg('系统错误');
                            $btn.button('reset');
                        }
                    });
                }
            })
        });
    </script>--}}
@stop
