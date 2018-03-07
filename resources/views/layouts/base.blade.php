<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="@section('keywords'){{ Config::get('global.keywords') }}@show">
        <meta name="description" content="@section('description'){{ Config::get('global.description') }}@show">
        <meta name="author" content="{{ Config::get('global.author') }}">

        <title>@section('title'){{ Config::get('global.title') }}@show</title>

        {{--Font--}}
        <link rel="stylesheet" href="{{ asset('libs/font-awesome/css/font-awesome.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/iconfont/iconfont.css') }}">

        {{--bootstrap--}}
        <link rel="stylesheet" href="{{ asset('libs/bootstrap/css/bootstrap.css') }}">
        @section('css')
        @show

        @section('style')
        @show

        {{--JavaScript--}}

        <!--[if lt IE 9]>
        <script type="text/javascript" src="{{ asset('libs/html5shiv/3.7.3/html5shiv.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('libs/respond/1.4.2/respond.min.js') }}"></script>
        <![endif]-->

        <script src="{{ asset('libs/jQuery/jQuery-2.2.0.min.js') }}"></script>
    </head>

    <body>
        @yield('content')

        <script src="{{ asset('libs/bootstrap/js/bootstrap.min.js') }}"></script>
        <script>
            /*输入框聚焦添加样式*/
            $('.auth .text').on('focus', function () {
                $(this).addClass('text-focus');
            });
            /*输入框失去焦点去除样式*/
            $('.auth .text').on('blur', function () {
                $(this).removeClass('text-focus');
            })
        </script>
        @section('footer')
        @show
    </body>
</html>