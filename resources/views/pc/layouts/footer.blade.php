<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="@section('keywords'){{ config('global.keywords') }}@show">
    <meta name="description" content="@section('description'){{ config('global.description') }}@show">
    <meta name="author" content="{{ config('global.author') }}">

    <title>@section('title'){{ config('global.title') }}@show</title>

    {{--Font--}}
    <link rel="stylesheet" href="{{ asset('libs/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/iconfont/iconfont.css') }}">

    {{--bootstrap--}}
    <link rel="stylesheet" href="{{ asset('libs/bootstrap/css/bootstrap.min.css') }}">
    @section('css')
    @show

    <link rel="stylesheet" href="{{ asset('css/footer/default.css') }}">

    @section('style')
    @show

    <link rel="shortcut icon" href="/bitbug_favicon.ico">

    {{--JavaScript--}}

    <!--[if lt IE 9]>
    <script type="text/javascript" src="{{ asset('libs/html5shiv/3.7.3/html5shiv.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('libs/respond/1.4.2/respond.min.js') }}"></script>
    <![endif]-->

    <script src="{{ asset('libs/jQuery/jQuery-2.2.0.min.js') }}"></script>
</head>

<body>
@section('nav')
    <div class="logo">
        <a class="navbar-brand all-index-logo" href="{{ url('/') }}" title="Serenity" style="padding: inherit;">
            <img class="all-index-logo-img" src="/imgs/logo.png" width="190" height="50"/>
        </a>
    </div>
@show
@yield('content')

<script src="{{ asset('libs/bootstrap/js/bootstrap.min.js') }}"></script>
@section('footer')
@show
</body>
</html>