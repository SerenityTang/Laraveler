@extends('layouts.app')
@section('title')
    {{ $user->username }} 的文章 | @parent
@stop
@section('css')
    <link href="{{ asset('css/user/default.css') }}" rel="stylesheet">
@stop

@section('content')
    @include('user.partials.homepage_header')

    <div class="container main-container">
        <div class="row">
            {{--左侧菜单--}}
            <div class="col-md-3">
                @include('user.partials.home-side-menu')
            </div>

            <div class="col-md-9">
                <div class="panel panel-default right-container">
                    <h4 class="title">@if($user->id != (Auth::check() ? Auth::user()->id : 0)) TA的文章 @else 我的文章 @endif</h4>

                </div>
            </div>
        </div>
    </div>
@stop

@section('footer')
    <script>

    </script>
@stop
