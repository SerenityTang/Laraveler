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
                        @foreach($user_credit_configs as $user_credit_config)
                            <tr>
                                <td>{{ $user_credit_config->behavior }}</td>
                                <td>+{{ $user_credit_config->credits }}</td>
                                <td>{{ $user_credit_config->time }}</td>
                                <td>{{ $user_credit_config->description }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('footer')
    <script src="{{ asset('libs/LeftNav/js/leftnav.js') }}"></script>
@stop