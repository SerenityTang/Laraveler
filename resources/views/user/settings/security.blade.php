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
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">绑定邮箱</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control text-extra" id="email" name="email" placeholder="请输入绑定邮箱">
                            </div>
                            <div class="col-sm-2">
                                <button type="button" class="btn btn-success btn-lg email-bind">绑定</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">绑定手机</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control text-extra" id="phone" name="phone" placeholder="请输入绑定手机">
                            </div>
                            <div class="col-sm-2">
                                <button type="button" class="btn btn-success btn-lg phone-bind">绑定</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('footer')
    <script>
        $('.email-bind').click(function () {
            $.ajax({
                type: 'post',
                url: '{{ url('/user/email_bind') }}',
                data: {
                    _token: '{{csrf_token()}}',
                    'email': $('#email').val(),
                },
                cache: false,
                success: function (res) {
                    if (res.code == 901) {
                        layer.msg(res.message, {
                            icon: 6,
                            time: 3000,
                        });
                    }
                },
                error: function () {
                    
                }
            })
        })
    </script>
@stop