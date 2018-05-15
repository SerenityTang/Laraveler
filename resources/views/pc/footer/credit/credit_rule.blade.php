@extends('pc.layouts.footer')
@section('title')
    积分规则 | @parent
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
                <table class="table table-hover">
                    <caption>积分规则列表</caption>
                    <thead>
                        <tr>
                            <th>名称</th>
                            <th>分值</th>
                            <th>次数(一天内)</th>
                            <th>说明</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>登录</td>
                            <td>+2</td>
                            <td>1</td>
                            <td>每天登录Laraveler官网</td>
                        </tr>
                        <tr>
                            <td>签到</td>
                            <td>+2</td>
                            <td>1</td>
                            <td>每天Laraveler官网首页签到</td>
                        </tr>

                        <tr>
                            <td>发布问答</td>
                            <td>+3</td>
                            <td>10</td>
                            <td>发布问答到平台</td>
                        </tr>
                        <tr>
                            <td>问答被投票</td>
                            <td>+5</td>
                            <td>5</td>
                            <td>问答被其他用户投票</td>
                        </tr>
                        <tr>
                            <td>问答被收藏</td>
                            <td>+5</td>
                            <td>5</td>
                            <td>问答被其他用户收藏</td>
                        </tr>
                        <tr>
                            <td>回答问题</td>
                            <td>+5</td>
                            <td>10</td>
                            <td>回答平台用户发布的问答</td>
                        </tr>
                        <tr>
                            <td>回答问题被采纳</td>
                            <td>+10</td>
                            <td>5</td>
                            <td>回答平台用户发布的问答后被采纳</td>
                        </tr>
                        <tr>
                            <td>回答问题被支持</td>
                            <td>+5</td>
                            <td>5</td>
                            <td>回答平台用户发布的问答后被支持</td>
                        </tr>

                        <tr>
                            <td>发布博客</td>
                            <td>+10</td>
                            <td>10</td>
                            <td>发布博客到平台</td>
                        </tr>
                        <tr>
                            <td>博客被点赞</td>
                            <td>+5</td>
                            <td>5</td>
                            <td>博客被其他用户收藏</td>
                        </tr>
                        <tr>
                            <td>博客被收藏</td>
                            <td>+5</td>
                            <td>5</td>
                            <td>博客被其他用户收藏</td>
                        </tr>
                        <tr>
                            <td>博客被置顶</td>
                            <td>+10</td>
                            <td>2</td>
                            <td>博客被置顶</td>
                        </tr>
                        <tr>
                            <td>博客被推荐</td>
                            <td>+10</td>
                            <td>2</td>
                            <td>博客被推荐</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('footer')
    <script src="{{ asset('libs/LeftNav/js/leftnav.js') }}"></script>
@stop