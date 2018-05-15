@extends('pc.layouts.footer')
@section('title')
    帮助中心 | @parent
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
                <div class="panel-group" id="accordion">
                    <div class="panel-group" id="accordion">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="title" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                        积分模块
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse in">
                                <div class="panel-body panel-item">
                                    <ol class="item">
                                        <li>积分介绍</li>
                                        <li>积分规则</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="title" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                        L币模块
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseTwo" class="panel-collapse collapse">
                                <div class="panel-body panel-item">
                                    <ol class="item">
                                        <li>L币介绍</li>
                                        <li>L币规则</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="title" data-toggle="collapse" data-parent="#accordion"
                                       href="#collapseThree">
                                        点击我进行展开，再次点击我进行折叠。第 3 部分
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseThree" class="panel-collapse collapse">
                                <div class="panel-body">
                                    Nihil anim keffiyeh helvetica, craft beer labore wes anderson
                                    cred nesciunt sapiente ea proident. Ad vegan excepteur butcher
                                    vice lomo.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('footer')
    <script src="{{ asset('libs/LeftNav/js/leftnav.js') }}"></script>
@stop