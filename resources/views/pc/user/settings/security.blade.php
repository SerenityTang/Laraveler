@extends('pc.layouts.app')
@section('title')
    {{ $user->username }} 账号安全 | @parent
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
                    <h4 class="title"><i class="iconfont icon-anquan"></i>账号安全</h4>
                    <form class="form-horizontal" role="form">

                        <div class="form-group email">
                            <label for="" class="col-sm-2 control-label">邮箱</label>
                            @if($user->email_status == 0)
                                <div class="col-md-5">
                                    <input type="text" class="form-control text-extra" id="email" name="email"
                                           placeholder="请输入绑定邮箱" value="{{ $user->email }}"
                                           @if($user->email) disabled @endif>
                                </div>
                                <div class="col-md-2 btn-bind @if($user->email != null)show-active @endif">
                                    <button type="button" class="btn btn-lg email-bind">绑定</button>
                                </div>
                                <div class="col-md-5 sc-btn show-active">
                                    <button type="button" class="btn btn-lg email-submit">提交</button>
                                    <button type="button" class="btn btn-lg email-cancel">取消</button>
                                </div>
                                <div class="col-md-5 cv-btn @if($user->email == null)show-active @endif">
                                    <button type="button" class="btn btn-lg email-change">更换</button>
                                    <button type="button" class="btn btn-lg email-verify">验证</button>
                                </div>
                            @elseif($user->email_status == 1)
                                <div class="col-md-5">
                                    <p class="binded-email">{{ substr_replace($user->email, ' **** ', 3, 6) }} <span
                                                class="email-binded-tip">已绑定</span></p>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-lg email-changed" data-toggle="modal"
                                            data-target="#verifyEmailModal">更换
                                    </button>
                                </div>
                            @endif
                        </div>

                        @if($user->mobile_status == 0)
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">手机</label>
                                <div class="col-md-5">
                                    <input type="text" class="form-control text-extra" id="mobile" name="mobile"
                                           placeholder="请输入绑定手机" value="{{--{{ $user->mobile }}--}}">
                                </div>
                            </div>
                            <div class="form-group verify">
                                <label for="" class="col-sm-2 control-label"></label>
                                <div class="col-md-5">
                                    <input type="text" class="form-control text-extra" id="verify_code"
                                           name="verify_code" maxlength="6" placeholder="请输入验证码">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-lg get-vcode">获取验证码</button>
                                </div>
                                <div class="col-md-2 mobile-bind-btn">
                                    <button type="button" class="btn btn-lg mobile-bind">绑定</button>
                                </div>
                            </div>
                        @elseif($user->mobile_status == 1)
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">手机</label>
                                <div class="col-md-5">
                                    <p class="binded-mobile">{{ substr_replace($user->mobile, ' **** ', 3, 4)}} <span
                                                class="mobile-binded-tip">已绑定</span></p>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-lg mobile-changed" data-toggle="modal"
                                            data-target="#verifyMobileModal">更换
                                    </button>
                                </div>
                            </div>
                        @endif

                        @include('pc.user.settings.partials.security_email_modal')

                        @include('pc.user.settings.partials.security_mobile_modal')
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('footer')
    <script>
        $(function () {
            //手机更换前验证与更换模态框取消隐藏
            $('.mobile-cancel-btn').click(function () {
                $('#verifyMobileModal').modal('hide');
                $('#mobileModal').modal('hide');
            });
            //手机更换前验证与更换模态框输入框聚焦去掉错误提示
            $('#new-mobile, #old-mobile').focus(function () {
                $('.mobile-modify .mobile-modify-body em.old-mobile-em, .mobile-modify .mobile-modify-body em.new-mobile-em').html('');
            });
            //手机更换前验证模态框验证提交
            $('.mobile-verify-submit-btn').click(function () {
                var old_mobile = $('#old-mobile').val();
                if (old_mobile == '') {
                    $('.mobile-modify .mobile-modify-body em.old-mobile-em').html('原手机号码 不可为空。');
                    return false;
                }
                $.ajax({
                    type: 'post',
                    url: '{{ url('/user/verify_mobile') }}',
                    data: {
                        _token: '{{csrf_token()}}',
                        'old_mobile': old_mobile,
                    },
                    cache: false,
                    success: function (res) {
                        if (res.code == 503) {
                            layer.msg(res.message, {
                                icon: 7,
                                time: 3000,
                                end: function (res) {
                                    location.href = '{{ url('/login') }}';
                                }
                            });
                        } else if (res.code == 893) {
                            layer.msg(res.message, {
                                icon: 6,
                                time: 1000,
                            });
                            $('#verifyMobileModal').modal('hide');
                            $('#mobileModal').modal('show');
                        } else if (res.code == 894) {
                            $('.mobile-modify .mobile-modify-body em.old-mobile-em').html(res.message);
                        } else if (res.code == 895) {
                            layer.msg(res.message, {
                                icon: 2,
                                time: 2000,
                            });
                        }
                    },
                    error: function () {
                        layer.msg('系统错误！', {
                            icon: 2,
                            time: 2000,
                        });
                        $('#verifyMobileModal').modal('hide');
                    }
                });
            });
            //聚焦显示验证码输入栏
            $('#new-mobile').focus(function () {
                $('.verify-modal').slideDown('slow');
                $('p.new-mobile-em').html('');
            });
            //聚焦验证码输入框隐藏错误提示
            $('#verify_code').focus(function () {
                $('em.new-verify-em').html('');
            });
            //模态框点击绑定按钮，提交验证码
            $('.mobile-submit-btn').click(function () {
                var new_mobile = $('#new-mobile').val();
                if (new_mobile == '') {
                    $('p.new-mobile-em').html('新手机号码 不可为空。');
                }
                $.ajax({
                    type: 'post',
                    url: '{{ url('/user/verify_mobile_code') }}',
                    data: {
                        _token: '{{csrf_token()}}',
                        'mobile': $('#new-mobile').val(),
                        'verify_code': $('#verify_code').val(),
                    },
                    cache: false,
                    success: function (res) {//console.log(res)
                        if (res.code == 502) {      //表单验证失败
                            $('em.new-verify-em').html(res.message[0]);
                            $('#verify_code').val('');
                        } else if (res.code == 898) {   //绑定成功
                            var data = res.data;
                            $('#mobileModal').modal('hide');
                            layer.msg(res.message, {
                                icon: 6,
                                time: 2000,
                                end: function (res) {
                                    location.href = '{{ url('/user/[data]/security') }}'.replace('[data]', data);
                                }
                            });

                        } else if (res.code == 897) {   //绑定失败
                            $('em.new-verify-em').html(res.message);
                        }
                    },
                    error: function () {
                        layer.msg('系统错误！', {
                            icon: 2,
                            time: 2000,
                        });
                        $('#mobileModal').modal('hide');
                    }
                });
            })
        });
    </script>
    <script>
        //模态框手机绑定获取验证码
        $(function () {
            $('.get-mvcode').click(function () {
                var self = $(this);
                var opts = $.extend(true, {}, options);

                changeBtn(opts.language.sending, true);
                send(opts);
            });

            //发送验证码后返回状态
            function send(opts) {
                $.ajax({
                    type: 'post',
                    url: '{{ url('/user/change_mobile_bind') }}',
                    data: {
                        _token: '{{csrf_token()}}',
                        'new_mobile': $('#new-mobile').val(),
                    },
                    cache: false,
                    success: function (res) {
                        if (res.code == 502) {        //表单验证失败
                            $('p.new-mobile-em').html(res.message[0]);
                            $('#new-mobile').val('');
                            $('.verify-modal').slideUp('slow');
                            changeBtn(opts.language.oricon, false);
                        } else if (res.code == 896) {   //手机号已存在
                            $('p.new-mobile-em').html(res.message);
                        } else if (res.code == 900) {   //发送成功
                            layer.msg(res.message, {
                                icon: 6,
                                time: 2000,
                            });
                            timer(opts.interval);/////////////////////
                        } else if (res.code == 901) {   //发送失败
                            $('em.new-verify-em').html(res.message);
                            changeBtn(opts.language.oricon, false);
                        } else if (res.code == 899) {   //重复获取
                            $('em.new-verify-em').html(res.message);
                            changeBtn(opts.language.oricon, false);
                        }
                    },
                    error: function () {
                        layer.msg('系统错误！', {
                            icon: 2,
                            time: 2000,
                        });
                        $('#new-mobile').val('');
                        $('.verify-modal').slideUp('slow');
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
                    timeId = setTimeout(function () {
                        clearTimeout(timeId);
                        changeBtn(btnText.replace('60 s', (seconds--) + ' s'), true);
                        timer(seconds);
                    }, 1000);
                }
            }

            //发送验证码按钮
            function changeBtn(content, disabled) {
                $('.get-mvcode').html(content);
                $('.get-mvcode').val(content);
                $('.get-mvcode').prop('disabled', !!disabled);
            }
        });

        var options = {
            token: null,
            interval: 60,
            voice: false,
            requestUrl: null,
            requestData: null,
            notify: function (msg, type) {
                alert(msg);
            },
            language: {
                oricon: '获取验证码',
                sending: '短信发送中...',
                failed: '请求失败，请重试',
                resendable: '60 s'
            }
        };
    </script>



    <script>
        $(function () {
            //邮箱更换模态框取消隐藏
            $('.email-cancel-btn').click(function () {
                $('#verifyEmailModal').modal('hide');
                $('#emailModal').modal('hide');
            });
            //邮箱更换模态框输入框聚焦去掉错误提示
            $('#new-email, #old-email').focus(function () {
                $('em.old-email-em, em.new-email-em').html('');
            });
            //邮箱更换前验证模态框验证
            $('.email-verify-submit-btn').click(function () {
                var old_email = $('#old-email').val();
                if (old_email == '') {
                    $('em.old-email-em').html('新邮箱地址 不可为空。');
                    return false;
                }
                $.ajax({
                    type: 'post',
                    url: '{{ url('/user/verify_email') }}',
                    data: {
                        _token: '{{csrf_token()}}',
                        'email': $('#old-email').val(),
                    },
                    cache: false,
                    success: function (res) {
                        if (res.code == 503) {
                            layer.msg(res.message, {
                                icon: 7,
                                time: 3000,
                                end: function (res) {
                                    location.href = '{{ url('/login') }}';
                                }
                            });
                        } else if (res.code == 890) {
                            layer.msg(res.message, {
                                icon: 6,
                                time: 1000,
                            });
                            $('#verifyEmailModal').modal('hide');
                            $('#emailModal').modal('show');
                        } else if (res.code == 891) {
                            $('em.old-email-em').html(res.message);
                        } else if (res.code == 892) {
                            layer.msg(res.message, {
                                icon: 2,
                                time: 2000,
                            });
                        }
                    },
                    error: function () {
                        layer.msg('系统错误！', {
                            icon: 2,
                            time: 2000,
                        });
                        $('#verifyEmailModal').modal('hide');
                    }
                });
            });
            //邮箱更换模态框点击绑定按钮提交
            $('.email-submit-btn').click(function () {
                $.ajax({
                    type: 'post',
                    url: '{{ url('/user/email_bind') }}',
                    data: {
                        _token: '{{csrf_token()}}',
                        'new_email': $('#new-email').val(),
                    },
                    cache: false,
                    success: function (res) {
                        if (res.code == 502) {
                            $('em.new-email-em').html(res.message);
                            $('#new-email').val('');        //清空输入内容
                        } else if (res.code == 908) {
                            $('em.new-email-em').html(res.message);
                            $('#new-email').val('');        //清空输入内容
                        } else if (res.code == 909) {
                            layer.msg(res.message, {
                                icon: 6,
                                time: 6000,
                            });
                            $('#emailModal').modal('hide');
                        } else if (res.code == 910) {
                            layer.msg(res.message, {
                                icon: 2,
                                time: 3000,
                            });
                            $('#emailModal').modal('hide');
                        }
                    },
                    error: function () {
                        layer.msg('系统错误！', {
                            icon: 2,
                            time: 2000,
                        });
                        $('#emailModal').modal('hide');
                    }
                });
            });
        });
    </script>






    <script>
        //初次绑定、提交邮箱地址
        $(function () {
            $('.email-bind, .email-submit').click(function () {
                $.ajax({
                    type: 'post',
                    url: '{{ url('/user/email_bind') }}',
                    data: {
                        _token: '{{csrf_token()}}',
                        'email': $('#email').val(),
                    },
                    cache: false,
                    success: function (res) {
                        if (res.code == 502) {
                            layer.msg(res.message[0], {
                                icon: 2,
                                time: 3000,
                            });
                        } else if (res.code == 908) {
                            layer.msg(res.message, {
                                icon: 5,
                                time: 3000,
                            });
                            $('#email').val('');        //清空输入内容
                        } else if (res.code == 909) {
                            layer.msg(res.message, {
                                icon: 6,
                                time: 6000,
                            });
                            $('#email').val(res.data);                  //填充输入内容
                            $('#email').attr("disabled", "disabled");    //设置input不可编辑
                            $('.btn-bind').addClass('show-active');     //绑定按钮隐藏
                            $('.cv-btn').removeClass('show-active');    //更换&验证按钮显示
                        } else if (res.code == 910) {
                            layer.msg(res.message, {
                                icon: 2,
                                time: 3000,
                            });
                            $('#email').val('');        //清空输入内容
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

            //验证邮箱地址
            $('.email-verify').click(function () {
                $.ajax({
                    type: 'post',
                    url: '{{ url('/user/email_bind/send_verify') }}',
                    data: {
                        _token: '{{csrf_token()}}',
                        'email': $('#email').val(),
                    },
                    cache: false,
                    success: function (res) {
                        if (res.code == 909) {
                            layer.msg(res.message, {
                                icon: 6,
                                time: 6000,
                            });
                        } else {
                            layer.msg('系统错误！', {
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
            //邮箱地址更换
            $('.email-change').click(function () {
                $('#email').removeAttr("disabled");    //设置input不可编辑
                $('.sc-btn').removeClass('show-active');    //提交&取消按钮显示
                $('.cv-btn').addClass('show-active');    //更换&验证按钮隐藏
            });
            //邮箱地址更换取消
            $('.email-cancel').click(function () {
                $('#email').attr("disabled", "disabled");    //设置input不可编辑
                $('.sc-btn').addClass('show-active');    //提交&取消按钮隐藏
                $('.cv-btn').removeClass('show-active');    //更换&验证按钮显示
            });
        });
    </script>




    <script>
        //初次手机绑定获取验证码
        $(function () {
            $('.get-vcode').click(function () {
                var self = $(this);
                var opts = $.extend(true, {}, defaults);

                changeBtn(opts.language.sending, true);
                send(opts);
            });

            //发送验证码后返回状态
            function send(opts) {
                $.ajax({
                    type: 'post',
                    url: '{{ url('/user/mobile_bind') }}',
                    data: {
                        _token: '{{csrf_token()}}',
                        'mobile': $('#mobile').val(),
                    },
                    cache: false,
                    success: function (res) {
                        if (res.code == 502) {      //表单验证失败
                            layer.msg(res.message[0], {
                                icon: 2,
                                time: 3000,
                            });
                            $('#mobile').val('');
                            $('.verify').slideUp('slow');
                            changeBtn(opts.language.oricon, false);
                        } else if (res.code == 896) {   //手机号已存在
                            layer.msg(res.message, {
                                icon: 5,
                                time: 2000,
                            });
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
                        $('#mobile').val('');
                        $('.verify').slideUp('slow');
                        changeBtn(opts.language.oricon, false);
                    }
                });
            }

            //倒计时
            function timer(seconds) {
                var timeId;
                var opts = $.extend(true, {}, defaults);
                var btnText = opts.language.resendable;
                btnText = typeof btnText === 'string' ? btnText : '';
                if (seconds < 0) {
                    clearTimeout(timeId);
                    changeBtn(opts.language.oricon, false);
                } else {
                    timeId = setTimeout(function () {
                        clearTimeout(timeId);
                        changeBtn(btnText.replace('60 s', (seconds--) + ' s'), true);
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

        var defaults = {
            token: null,
            interval: 60,
            voice: false,
            requestUrl: null,
            requestData: null,
            notify: function (msg, type) {
                alert(msg);
            },
            language: {
                oricon: '获取验证码',
                sending: '短信发送中...',
                failed: '请求失败，请重试',
                resendable: '60 s'
            }
        };
    </script>
    <script>
        $(function () {
            //聚焦显示验证码输入栏
            $('#mobile').focus(function () {
                $('.verify').slideDown('slow');
            });

            //点击绑定按钮，提交验证码
            $('.mobile-bind').click(function () {
                $.ajax({
                    type: 'post',
                    url: '{{ url('/user/verify_mobile_code') }}',
                    data: {
                        _token: '{{csrf_token()}}',
                        'mobile': $('#mobile').val(),
                        'verify_code': $('#verify_code').val(),
                    },
                    cache: false,
                    success: function (res) {//console.log(res)
                        if (res.code == 502) {      //表单验证失败
                            layer.msg(res.message[0], {
                                icon: 2,
                                time: 3000,
                            });
                            $('#verify_code').val('');
                        } else if (res.code == 898) {   //绑定成功
                            var data = res.data;
                            layer.msg(res.message, {
                                icon: 6,
                                time: 2000,
                                end: function (res) {
                                    location.href = '{{ url('/user/[data]/security') }}'.replace('[data]', data);
                                }
                            });

                        } else if (res.code == 897) {   //绑定失败
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
@stop