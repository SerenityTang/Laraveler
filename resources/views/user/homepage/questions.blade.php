@extends('layouts.app')
@section('title')
    {{ $user->username }} 的问答 | @parent
@stop
@section('css')
    <link href="{{ asset('css/user/default.css') }}" rel="stylesheet">
@stop

@section('content')
    <div class="container main-container">
        <div class="row">
            {{--左侧菜单--}}
            <div class="col-md-3">
                @include('user.partials.home-side-menu')
            </div>

            <div class="col-md-9">
                <div class="panel panel-default right-container">
                    <h4 class="title">我的问答</h4>

                </div>
            </div>
        </div>
    </div>
@stop

@section('footer')
    <script>

    </script>
@stop
