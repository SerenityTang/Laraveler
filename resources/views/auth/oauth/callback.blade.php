@extends('layouts.base')
@section('title')
    用户登录
@stop
@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('css/auth.css')}}" />
@stop
@section('content')
    <div class="container">
        <div class="row">
            <div class="auth">
                <form class="form-horizontal bs-example bs-example-form" role="form" method="POST" action="{{ url('/auth/bind') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="realname" value="{{ $profile->realname }}">
                    <input type="hidden" name="avatar" value="{{ $profile->avatar }}">
                    <input type="hidden" name="gender" value="{{ $profile->gender }}">
                    <input type="hidden" name="weibo" value="{{ $profile->weibo }}">
                    <input type="hidden" name="province" value="{{ $profile->province }}">
                    <input type="hidden" name="city" value="{{ $profile->city }}">
                    <input type="hidden" name="oauth_type" value="{{ $profile->oauth_type }}">
                    <input type="hidden" name="oauth_id" value="{{ $profile->oauth_id }}">
                    <input type="hidden" name="oauth_access_token" value="{{ $profile->oauth_access_token }}">
                    <input type="hidden" name="oauth_expires" value="{{ $profile->oauth_expires }}">
                    <input type="hidden" name="redirect_uri" value="{{ $redirect_uri }}">
                    <div class="form-group">
                        <label for="lastname" class="col-sm-2 control-label">用户名</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="username" name="username" placeholder="用户名" value="{{ $profile->nickname }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="lastname" class="col-sm-2 control-label">邮箱</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="email" name="email" placeholder="邮箱" value="{{ old('email') }}">
                        </div>
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
                        <div class="submit-btn">
                            <button type="submit" id="login-btn" class="btn btn-success btn-block btn-flat log-btn" data-loading-text="正在登录..." onautocomplete="off">提 交</button>
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
        $("#alert_message").delay(6000).fadeOut(500);
    </script>
@stop
