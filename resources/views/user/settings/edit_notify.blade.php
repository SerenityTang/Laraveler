@extends('layouts.app')
@section('title')
{{ $user->username }} 通知私信 | @parent
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
                <h4 class="title"><i class="iconfont icon-xiaoxi1"></i>通知私信</h4>

            </div>
        </div>
    </div>
</div>
@stop

@section('footer')
<script>

</script>
@stop