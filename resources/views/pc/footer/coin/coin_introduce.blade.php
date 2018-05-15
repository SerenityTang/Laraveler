@extends('pc.layouts.footer')
@section('title')
    L币介绍 | @parent
@stop
@section('css')
    <link href="{{ asset('libs/LeftNav/css/leftnav.css') }}" rel="stylesheet">
@stop

@section('nav')
    <div class="logo">
        <a class="navbar-brand all-index-logo" href="{{ url('/') }}" title="Serenity" style="padding: inherit;">
            <img class="all-index-logo-img" src="/imgs/logo.png" width="190" height="50"/>
            <span class="title">帮助中心</span>
        </a>
    </div>
@stop

@section('content')
    <div class="container container-top">
        <div class="row">
            <div class="col-md-3">
                @include('pc.footer.partials.help_side')
            </div>
            <div class="col-md-9">
                <div class="media media-item">
                    <div class="media-body">
                        <h3 class="media-heading">什么是L币？</h3>
                        <p class="content">L币（LB）是Laraveler的虚拟货币，1 积分相当于 1 L币，100 L币相当于人民币 1 元钱。</p>
                    </div>
                </div>
                <div class="media media-item">
                    <div class="media-body">
                        <h3 class="media-heading">如何获取L币？</h3>
                        <p class="content">L币可以通过积分兑换、充值方式获得。</p>
                    </div>
                </div>
                <div class="media media-item">
                    <div class="media-body">
                        <h3 class="media-heading">如何查询L币？</h3>
                        <p class="content">积分可通过在平台个人主页进行查询。</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('footer')
    <script src="{{ asset('libs/LeftNav/js/leftnav.js') }}"></script>
@stop