@extends('pc.layouts.footer')
@section('title')
    积分介绍 | @parent
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
                        <h3 class="media-heading">什么是积分？</h3>
                        <p class="content">所有Laraveler注册用户在平台进行活动（每天登录、每天签到、回答问答、发布博客等）过程中均有机会获得积分，注册Laraveler账号后即可加入积分系统，积分不可兑换现金，不可转让。</p>
                    </div>
                </div>
                <div class="media media-item">
                    <div class="media-body">
                        <h3 class="media-heading">如何获取积分？</h3>
                        <p class="content">积分可通过在平台进行登录、签到，发布问答、发布博客等方式获得，详情可查看积分规则。</p>
                    </div>
                </div>
                <div class="media media-item">
                    <div class="media-body">
                        <h3 class="media-heading">如何查询积分？</h3>
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