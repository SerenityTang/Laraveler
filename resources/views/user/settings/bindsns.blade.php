@extends('layouts.app')
@section('title')
    {{ $user->username }} 账号绑定 | @parent
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
                    <h4 class="title"><i class="iconfont icon-msnui-bind-circle"></i>账号绑定</h4>
                    <div class="row bind-account">
                        <div class="col-sm-4 bind-type">
                            <i class="fa fa-qq fa-lg" style="color: #0197e6;padding-right: 6px;"></i>
                            <span>Q Q</span>
                        </div>
                        <div class="col-sm-2">
                            <a><button type="button" class="btn btn-success bind-btn">绑定账号</button></a>
                        </div>
                    </div>
                    <div class="row bind-account">
                        <div class="col-sm-4 bind-type">
                            <i class="fa fa-weixin fa-lg" style="color: #06b607;padding-right: 5px;"></i>
                            <span>微信</span>
                        </div>
                        <div class="col-sm-2">
                            <a><button type="button" class="btn btn-success bind-btn">绑定账号</button></a>
                        </div>
                    </div>
                    <div class="row bind-account">
                        <div class="col-sm-4 bind-type">
                            <i class="fa fa-weibo fa-lg" style="color: #ec0217;padding-right: 8px;"></i>
                            <span>微博</span>
                        </div>
                        <div class="col-sm-2">
                            <a><button type="button" class="btn btn-success bind-btn">绑定账号</button></a>
                        </div>
                    </div>
                    <div class="row bind-account">
                        <div class="col-sm-4 bind-type">
                            <i class="fa fa-google fa-lg" style="color: #3182f7;padding-right: 11px;"></i>
                            <span>Google</span>
                        </div>
                        <div class="col-sm-2">
                            <a><button type="button" class="btn btn-success bind-btn">绑定账号</button></a>
                        </div>
                    </div>
                    <div class="row bind-account">
                        <div class="col-sm-4 bind-type">
                            <i class="fa fa-facebook fa-lg" style="color: #4861a3;padding-left:3px;padding-right: 13px;"></i>
                            <span>Facebook</span>
                        </div>
                        <div class="col-sm-2">
                            <a><button type="button" class="btn btn-success bind-btn">绑定账号</button></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('footer')
    <script>

    </script>
@stop