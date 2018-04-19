@extends('pc.layouts.footer')
@section('title')
    关于Laraveler | @parent
@stop

@section('content')
    <div class="logo">
        <a class="navbar-brand all-index-logo" href="{{ url('/') }}" title="Serenity" style="padding: inherit;">
            <img class="all-index-logo-img" src="/imgs/logo.png" width="190" height="50"/>
        </a>
    </div>
    <div class="about-us">
        <h3>关于Laraveler</h3>
        <p class="intro">简介：</p>
        <p class="description">
            <span class="name">Laraveler</span>（www.laraveler.net）创立于2018年，是中文领域的Laravel技术问答交流社区平台。Laravel是一个好框架，给我们开发带来了方便，但是在开发过程中难免遇到各种问题，
            我们条件反射会通过技术问答交流社区寻求我们心仪的答案与解决方法，在国内技术问答交流社区涉及面广，专门为Laravel打造的技术问答交流社区却比较少，所以诞生了Laraveler技术社区。
            在这里，您可以把自己遇到的问题以问答的方式发向平台，耐心等待热心者给予帮助、解答，也可搜索相关的问题，寻找您想要的答案；解决问题都是我们提高，吸收，进步的过程，此时不妨通过
            博客方式把您的体验分享到平台，与广大开发者交流学习。
        </p>

        <p class="intro">我们的宗旨：</p>
        <p class="description aim">为国内广大Laravel程序员打造一个互助、交流、分享的开源社区</p>
    </div>
@stop
