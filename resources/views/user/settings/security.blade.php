@extends('layouts.app')
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
                @include('user.partials.setting-side-menu')
            </div>

            <div class="col-md-9">
                <div class="panel panel-default right-container">
                    <h4 class="title"><i class="iconfont icon-anquan"></i>账号安全</h4>
                    <form class="form-horizontal" role="form">
                        <div class="form-group email">
                            <label for="" class="col-sm-2 control-label">邮箱</label>
                            @if($user->email_status == 0)
                                <div class="col-md-5">
                                    <input type="text" class="form-control text-extra" id="email" name="email" placeholder="请输入绑定邮箱" value="{{ $user->email }}" @if($user->email) disabled @endif>
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
                                    <p class="binded-email">{{ $user->email }} <span class="binded-tip">已绑定</span></p>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-lg email-changed" data-toggle="modal" data-target="#emailModal">更换</button>
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">手机</label>
                            <div class="col-md-5">
                                <input type="text" class="form-control text-extra" id="phone" name="phone" placeholder="请输入绑定手机">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-lg phone-bind">绑定</button>
                            </div>
                        </div>
                        <div class="modal fade" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog email-modify">
                                <div class="modal-content">
                                    <div class="modal-header email-modify-header">
                                        <button type="button" class="close email-modify-close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h4 class="modal-title email-modify-title" id="myModalLabel">
                                            邮箱更换
                                        </h4>
                                    </div>
                                    <div class="modal-body email-modify-body">
                                        <div class="row">
                                            <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data" action="{{ url('/feedback') }}">
                                                <p class="email-icon">
                                                    <i class="iconfont icon-youjianyanzheng"></i>
                                                </p>
                                                <div class="form-group new-email">
                                                    <label for="" class="col-sm-3 control-label">新邮箱地址</label>
                                                    <div class="col-sm-7 extra">
                                                        <input type="text" id="new-email" name="new-email" class="form-control text-extra" placeholder="请输入更换邮箱地址">
                                                    </div>
                                                </div>
                                                <em></em>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="modal-footer email-modify-footer">
                                        <button type="button" class="btn email-submit-btn">
                                            提交
                                        </button>
                                        <button type="button" class="btn email-cancel-btn">
                                            取消
                                        </button>
                                    </div>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('footer')
    <script>
        $(function(){
            //邮箱更换模态框取消隐藏
            $('.email-cancel-btn').click(function () {
                $('#emailModal').modal('hide');
            });
            //邮箱更换模态框输入框聚焦去掉错误提示
            $('#new-email').focus(function () {
                $('.email-modify .email-modify-body em').html('');
            });
            //邮箱更换模态框提交
            $('.email-submit-btn').click(function () {
                $.ajax({
                    type: 'post',
                    url: '{{ url('/user/email_bind') }}',
                    data: {
                        _token: '{{csrf_token()}}',
                        'email': $('#new-email').val(),
                    },
                    cache: false,
                    success: function (res) {
                        if (res.code == 502) {
                            $('.email-modify .email-modify-body em').html(res.message);
                            $('#new-email').val('');        //清空输入内容
                        } else if (res.code == 908) {
                            $('.email-modify .email-modify-body em').html(res.message);
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
        $(function () {
            //绑定、提交邮箱地址
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
                            $('#email').attr("disabled","disabled");    //设置input不可编辑
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
                $('#email').attr("disabled","disabled");    //设置input不可编辑
                $('.sc-btn').addClass('show-active');    //提交&取消按钮隐藏
                $('.cv-btn').removeClass('show-active');    //更换&验证按钮显示
            });
        });
    </script>
@stop