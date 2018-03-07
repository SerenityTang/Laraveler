@extends('layouts.app')
@section('title')
    {{ $user->username }} 密码修改 | @parent
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
                    <h4 class="title"><i class="iconfont icon-mima"></i>密码修改</h4>
                    <form class="form-horizontal" role="form" method="post" action="{{ url('user/profile/modify_password') }}">
                        <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}" />

                        <div class="form-group{{ $errors->has('old_password') ? ' has-error' : '' }}">
                            <label for="" class="col-sm-2 control-label">原密码</label>
                            <div class="col-sm-7">
                                <input type="password" class="form-control text-extra" id="old_password" name="old_password" placeholder="请输入原密码">
                            </div>
                            @if ($errors->has('old_password'))
                                <span class="help-block help-block-clear">
                                    <em>{{ $errors->first('old_password') }}</em>
                                </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('new_password') ? 'errors' : '' }}">
                            <label for="" class="col-sm-2 control-label">新密码</label>
                            <div class="col-sm-7">
                                <input type="password" class="form-control text-extra" id="new_password" name="new_password" placeholder="请输入新密码">
                            </div>
                            @if ($errors->has('new_password'))
                                <span class="help-block help-block-clear">
                                    <em>{{ $errors->first('new_password') }}</em>
                                </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('password_confirmation') ? 'errors' : '' }}">
                            <label for="" class="col-sm-2 control-label">确认密码</label>
                            <div class="col-sm-7">
                                <input type="password" class="form-control text-extra" id="password_confirmation" name="password_confirmation" placeholder="请输入确认密码">
                            </div>
                            @if ($errors->has('password_confirmation'))
                                <span class="help-block help-block-clear">
                                    <em>{{ $errors->first('password_confirmation') }}</em>
                                </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-8">
                                <button type="submit" class="btn btn-success btn-lg btn-save">保存</button>
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
        //验证失败重新输入时，聚焦则把错误样式，错误提示取消
        $(function () {
            $('input').focus(function () {
                $(this).parents('.form-group').removeClass('has-error');
                $(this).parents('.form-group').find('em').html('');
            });
        });
    </script>
@stop