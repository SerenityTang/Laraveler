<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="@section('keywords'){{ config('global.keywords') }}@show">
    <meta name="description" content="@section('description'){{ config('global.description') }}@show">
    <meta name="author" content="{{ config('global.author') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@section('title'){{ config('global.title') }}@show</title>

    <!-- Styles -->
    {{--<link href="{{ asset('css/app.css') }}" rel="stylesheet">--}}

    {{--Font--}}
    <link rel="stylesheet" href="{{ asset('libs/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/iconfont/iconfont.css') }}">

    {{--bootstrap--}}
    <link rel="stylesheet" href="{{ asset('libs/bootstrap/css/bootstrap.min.css') }}">

    {{--dataTabels--}}
    {{--<link href="#" rel="stylesheet">--}}
    @section('css')
    @show

    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/cssreset.css') }}" rel="stylesheet">

    @section('style')
    @show

    <link rel="shortcut icon" href="/bitbug_favicon.ico">

    {{--JavaScript--}}
    <!--[if lt IE 9]>
    <script type="text/javascript" src="{{ asset('libs/html5shiv/3.7.3/html5shiv.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('libs/respond/1.4.2/respond.min.js') }}"></script>
    <![endif]-->

    <script src="{{ asset('libs/jQuery/jQuery-2.2.0.min.js') }}"></script>
    <script src="{{ asset('libs/layer/layer/layer.js') }}"></script>
</head>
<body>
@section('nav')
    @include('layouts.partials.nav')
@show
{{--显示操作成功与否等提示信息--}}
<div class="mt-90">
    @if ( session('message') )
        <div class="alert @if(session('message_type') === 1) alert-success @else alert-danger @endif alert-dismissible" role="alert" id="alert_message">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <p class="message"><i class="fa fa-check-circle fa-lg fa-fw"></i>{{ session('message') }}</p>
        </div>
    @endif
</div>

@yield('content')

@section('footer-nav')
    @include('layouts.partials.footer')
@show
<!-- Scripts -->
{{--<script src="{{ asset('js/app.js') }}"></script>--}}
<script src="{{ asset('libs/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/global.js') }}"></script>
{{--操作成功与否提示自动隐藏--}}
<script>
    $("#alert_message").delay(3000).fadeOut(500);
</script>
<script>
    {{-- hover 下拉 --}}
    $('ul.nav li.dropdown').hover(function() {
        $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(500);
    }, function() {
        $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(500);
    });
    {{-- 恢复父菜单的链接 --}}
    $('.navbar .dropdown > a').click(function(){
        location.href = this.href;
    });
</script>
{{--通过滚动高度设置导航的定位方式
<script type="text/javascript">
    $(document).ready(function () {
        $(window).scroll(function () {
            var topScroll = $(document).scrollTop();
            if (topScroll > 10) {
                $('#navbar-serenity').css({'position' : 'fixed','top' : 0, 'z-index' : '9999' });
            } else {
                $('#navbar-serenity').css({'position' : 'inherit' });
            }
        });
    });
</script>--}}

{{--导航搜索框--}}
<script>
    $(function(){
        $(".search-text").focus(function(){
            if ($(this).parents('.search-bar').find('input').val() == '') {
                $(this).parents('.search-bar').find('input').addClass('search-transition');
                $(this).parents('.search-bar').find('.hot-search').fadeIn("8000");
            } else {
                $(this).parents('.search-bar').find('.search-tip').css("display", "block");
            }
        });
        $(".search-text").blur(function(){
            $(this).parents('.search-bar').find('input').removeClass('search-transition');
            $(this).parents('.search-bar').find('.hot-search').fadeOut("2000");
            $(this).parents('.search-bar').find('.search-tip').css("display", "none");
        });

        $(".search-text").keyup(function(){
            $(this).parents('.search-bar').find('.hot-search').css("display", "none");

            var content = $(this).parents('.search-bar').find('input').val();
            $.ajax({
                type: "post",
                url: "{{ url('/search_tip') }}",
                data: content,
                cache: false, //不允许有缓存

                /*success:function(res){
                 var
                 if(data > 0){ //data传达过来的是Activity id
                 $('#aid').val(data);
                 console.log('自动保存成功。 活动id为 ' + data);
                 }
                 }*/
            });
            $(this).parents('.search-bar').find('.search-tip').css("display", "block");
        });
    });
</script>
{{--工具栏--}}
<script>
    $(function(){
        checkPosition();  //先执行一次判断当前位置而确定是否显示返回顶部按钮

        $('#backTop').on('click', moveTop);  //点击按钮返回顶部
        $(window).on('scroll', checkPosition);  //滚动才执行，判断位置

        function moveTop() {
            /*滚动条一般位于HTML上，chrome浏览器位于body，为了保险选中两者*/
            $('html,body').animate({scrollTop : 0}, 800);
        }
        function moveTopFast() {
            $('html,body').scrollTop(0);
        }

        function checkPosition() {
            /*返回顶部按钮显示与隐藏*/
            //var topScroll = $(document).scrollTop();
            var topScroll = $(window).scrollTop();
            var height = $(window).height();
            if (topScroll > height) {
                $('#backTop').fadeIn();
            } else {
                $('#backTop').fadeOut();
            }
        }
    });
</script>
@section('footer')
@show
@section('toolbar')
    @include('partials._toolbar')
@show
</body>
</html>
