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
    <link rel="stylesheet" href="{{ asset('libs/jquery-checkbox/css/jquery-labelauty.css') }}">
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

{{--意见反馈模态框--}}
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content feedback">
            <div class="modal-header feedback-header">
                <button type="button" class="close feedback-close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title feedback-title" id="myModalLabel">
                    意见反馈
                </h4>
            </div>
            <div class="modal-body feedback-body">
                <div class="row">
                    <form class="form-horizontal" role="form" method="post"  enctype="multipart/form-data" action="{{ url('/feedback') }}">
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">意见类型</label>
                            <div class="col-sm-9 extra">
                                <ul class="feedback">
                                    <li><input type="radio" id="content" name="feedback" value="内容意见" data-labelauty="内容意见" ></li>
                                    <li><input type="radio" id="technology" name="feedback" value="技术问题" data-labelauty="技术问题"></li>
                                    <li><input type="radio" id="other" name="feedback" value="其它" data-labelauty="其它"></li>
                                </ul>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">意见详情</label>
                            <div class="col-sm-9 extra">
                                <textarea id="description" name="description" class="form-control" placeholder="请填写具体内容并留下您宝贵的意见^_^" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">页面链接</label>
                            <div class="col-sm-9 extra">
                                <input type="text" id="personal_website" name="fb-url" class="form-control text-extra" placeholder="http://www.laraveler.net/">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">联系方式</label>
                            <div class="col-sm-9 extra">
                                <input type="text" id="personal_website" name="fb-contact" class="form-control text-extra" placeholder="请留下您的联系信息方便我们及时为您解答(QQ/邮箱)^_^">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer feedback-footer">
                <button type="button" class="btn feedback-btn">
                    提交
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

@yield('content')

@section('footer-nav')
    @include('layouts.partials.footer')
@show
<!-- Scripts -->
{{--<script src="{{ asset('js/app.js') }}"></script>--}}
<script src="{{ asset('libs/jquery-checkbox/js/jquery-labelauty.js') }}"></script>
<script src="{{ asset('libs/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('libs/typeahead.js/dist/typeahead.bundle.min.js') }}"></script>
<script src="{{ asset('js/global.js') }}"></script>
<script src="{{ asset('libs/jquery-checkbox/js/jquery-labelauty.js') }}"></script>
{{--意见反馈模态框--}}
<script>
    $(function(){
        $('.feedback input').labelauty();

        $('.feedback-btn').click(function () {
            var data = $(".feedback .feedback-body form").serializeArray();
            $.ajax({
                url: "{{ url('/feedback') }}",
                type: "post",
                dataType: "json",
                data: {
                    'data': data,
                    _token: '{{csrf_token()}}',
                },
                cache: false, //不允许有缓存
                success: function(res){
                    if (res.code == 801) {
                        $('#myModal').modal('hide');
                        layer.msg(res.message, {
                            icon: 6,
                            time: 2000,
                        });
                    } else {
                        $('#myModal').modal('hide');
                        layer.msg('系统错误！', {
                            icon: 2,
                            time: 2000,
                        });
                    }
                },
                error: function(){
                    $('#myModal').modal('hide');
                    layer.msg('系统错误！', {
                        icon: 2,
                        time: 2000,
                    });
                }
            });
        });
    });
</script>
{{--操作成功与否提示自动隐藏--}}
<script>
    $("#alert_message").delay(3000).slideUp('slow');
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
            if ($(this).parents('.search-bar').find('.search-text').val() == '') {
                $(this).parents('.search-bar').find('.search-text').addClass('search-transition');
                $(this).parents('.search-bar').find('.hot-search').fadeIn("8000");
            } else {
                $(this).parents('.search-bar').find('.search-tip').css("display", "block");
                $(this).parents('.search-bar').find('.search-text').addClass('search-transition');
            }
        });
        $(".search-text").blur(function(){
            $(this).parents('.search-bar').find('input').removeClass('search-transition');
            $(this).parents('.search-bar').find('.hot-search').hide();
        });
        $(".search-text").keyup(function() {
            $(this).parents('.search-bar').find('.hot-search').css("display", "none");
        });
        /*$(".search-text").focus(function(){
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
        });*/

        /*$(".search-text").keyup(function(){
            $(this).parents('.search-bar').find('.hot-search').css("display", "none");

            var content = $(this).parents('.search-bar').find('input').val();
            $.ajax({
                type: "post",
                url: "{{ url('/search_tip') }}",
                data: content,
                cache: false, //不允许有缓存

                /!*success:function(res){
                 var
                 if(data > 0){ //data传达过来的是Activity id
                 $('#aid').val(data);
                 console.log('自动保存成功。 活动id为 ' + data);
                 }
                 }*!/
            });
            $(this).parents('.search-bar').find('.search-tip').css("display", "block");
        });*/
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
