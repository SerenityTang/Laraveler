@extends('layouts.base')
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
                    {{--<input type="hidden" name="redirect_uri" value="{{ $redirect_uri }}">--}}
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
                    <div class="form-group form-group-bottom captcha">
                        <div class="input-group">
                            <input type="text" class="form-control text" id="captcha" name="captcha" style="width: 180px" maxlength="5" placeholder="验证码">&nbsp;
                            <img id="captcha-img" src="{{ url('/captcha/verify') }}" >
                            <span class="glyphicon glyphicon-refresh loc" title="点击换一张"></span>
                        </div>
                        <span class="help-block help-block-clear">
                            <em>{{ $errors->first('captcha') }}</em>
                        </span>
                    </div>
                    <div class="form-group">
                        <div class="submit-btn">
                            <button type="submit" id="login-btn" class="btn btn-success btn-block btn-flat submit" data-loading-text="正在提交..." onautocomplete="off">提 交</button>
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
            $('#username').focusout(function () {
                var username = $('#username').val();
                if (username == '') {
                    $('#username').parents('.username').find('.help-block em').html('用户名不可为空');
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
            });
            $('#password').focusout(function () {
                var password = $('#password').val();
                if (password == '') {
                    $('#password').parents('.password').find('.help-block em').html('密码不可为空');
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
                var mobile = $('#mobile').val();
                if (mobile == '') {
                    $('#mobile').parents('.mobile').find('.help-block em').html('手机号不可为空');
                    $('.password').fadeOut('slow');
                } else {
                    $('#mobile').parents('.mobile').find('.help-block em').html('');
                    $.ajax({
                        url: '{{ url('/auth/bind_verify') }}',
                        type: 'post',
                        data: {
                            _token: '{{csrf_token()}}',
                            'mobile': mobile,
                        },
                        cache: false,
                        success: function(res){
                            if (res.code == 902) {
                                $('#mobile').parents('.mobile').find('.help-block em').html(res.message);
                                $('.password').fadeOut('slow');
                            } else if (res.code == 903) {
                                $('#mobile').parents('.mobile').find('.help-block em').html(res.message);
                                $('.password').fadeIn('slow');
                            } else if (res.code == 502) {
                                $('#mobile').parents('.mobile').find('.help-block em').html(res.message);
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
            $('#captcha').focusout(function () {
                var captcha = $('#captcha').val();
                if (captcha == '') {
                    $('#captcha').parents('.captcha').find('.help-block em').html('验证码不可为空');
                } else {
                    $('#captcha').parents('.captcha').find('.help-block em').html('');
                    $.ajax({
                        url: '{{ url('/auth/bind_verify') }}',
                        type: 'post',
                        data: {
                            _token: '{{csrf_token()}}',
                            'captcha': captcha,
                        },
                        cache: false,
                        success: function(res){
                            if (res.code == 905) {
                                $('#captcha').parents('.captcha').find('.help-block em').html(res.message);
                            } else if (res.code == 502) {
                                $('#captcha').parents('.captcha').find('.help-block em').html(res.message);
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
    {{--<script>
        $(function () {
            $('.submit').click(function () {
                var username = $('#username').val();
                var password = $('#password').val();
                var mobile = $('#mobile').val();
                var captcha = $('#captcha').val();
                var username_text = $('#username').parents('.username').find('.help-block em').html();
                var password_text = $('#password').parents('.password').find('.help-block em').html();
                var mobile_text  = $('#mobile').parents('.mobile').find('.help-block em').html();
                var captcha_text = $('#captcha').parents('.captcha').find('.help-block em').html();
                $(this).parents('.bind').find('.help-block em').html('');
                if (username == '' || mobile == '' || password == '' || captcha == '') {
                    layer.msg('请检查输入信息是否正确', {
                        icon: 2,
                        time: 2000,
                    });
                    return false;
                } else if (username_text != '用户名可用' || password_text != '提交信息即创建新账号' || mobile_text != '手机号可用，请输入密码' || captcha_text != '验证码正确') {
                    layer.msg('请检查输入信息是否正确', {
                        icon: 2,
                        time: 2000,
                    });
                    return false;
                } else {
                    $.ajax({
                        url: '{{ url('/auth/bind') }}',
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
            });
        });
    </script>--}}
@stop
