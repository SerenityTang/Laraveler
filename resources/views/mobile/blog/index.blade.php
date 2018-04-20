@extends('mobile.layouts.app')
@section('title')
    博客首页 | @parent
@stop
@section('css')
    <link rel="stylesheet" href="{{ asset('css/blog/mobile-default.css') }}">
    <link rel="stylesheet" href="{{ asset('libs/mobile-tab/swiper/swiper.min.css') }}">
    <link rel="stylesheet" href="{{ asset('libs/mobile-tab/css/page_style.css') }}">
@stop

@section('content')
    <div class="swiper-container">
        <div class="my-pagination"><ul class="my-pagination-ul"></ul></div>
        <div class="swiper-wrapper">

            <div class="swiper-slide">
                <div class="weui-cells blog-list">
                    @foreach($newest_blogs as $blog)
                        <a class="weui-cell weui-cell_access" href="{{ url('blog/show/' . $blog->id) }}">
                            <div class="weui-cell__bd">
                                <p class="blog-title">{{ $blog->title }}</p>
                            </div>
                            <div class="weui-cell__ft">
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="swiper-slide">
                <div class="weui-cells blog-list">
                    @foreach($hottest_blogs as $question)
                        <a class="weui-cell weui-cell_access" href="{{ url('blog/show/' . $blog->id) }}">
                            <div class="weui-cell__bd">
                                <p class="blog-title">{{ $blog->title }}</p>
                            </div>
                            <div class="weui-cell__ft">
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@stop

@section('footer')
    <script src="{{ asset('libs/mobile-tab/swiper/swiper.min.js') }}"></script>
    <script>
        var mySwiper = new Swiper('.swiper-container',{
            pagination: '.my-pagination-ul',
            paginationClickable: true,
            paginationBulletRender: function (index, className) {
                switch (index) {
                    case 0: name='最新博客';break;
                    case 1: name='热门博客';break;
                    default: name='';
                }
                return '<li class="' + className + '">' + name + '</li>';
            }
        })
    </script>
@stop