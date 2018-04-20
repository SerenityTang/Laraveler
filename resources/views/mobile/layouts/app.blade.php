<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>@section('title'){{ config('global.title') }}@show</title>
    <!-- 引入 WeUI -->
    <link rel="stylesheet" href="{{ asset('libs/jquery-weui/dist/lib/weui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('libs/jquery-weui/dist/css/jquery-weui.min.css') }}">

    {{--Font--}}
    <link rel="stylesheet" href="{{ asset('libs/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/iconfont/iconfont.css') }}">

    @section('css')
    @show

    <link href="{{ asset('css/mobile.css') }}" rel="stylesheet">

    @section('style')
    @show

    <link rel="shortcut icon" href="/bitbug_favicon.ico">

    <script src="{{ asset('libs/jquery-weui/dist/lib/jquery-2.1.4.js') }}"></script>
    <script src="{{ asset('libs/layer/layer/layer.js') }}"></script>
</head>
<body>
    @include('mobile.layouts.partials.nav')

    <div class="content">
        @yield('content')
    </div>

    {{--@include('mobile.layouts.partials.footer')--}}
    @include('mobile.layouts.partials.bottom_tabar')

    <script src="{{ asset('libs/jquery-weui/dist/js/jquery-weui.min.js') }}"></script>
    @section('footer')
    @show
</body>
</html>