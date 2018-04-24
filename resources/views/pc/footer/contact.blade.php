@extends('pc.layouts.footer')
@section('title')
    联系我们 | @parent
@stop

@section('content')
    <div class="logo">
        <a class="navbar-brand all-index-logo" href="{{ url('/') }}" title="Serenity" style="padding: inherit;">
            <img class="all-index-logo-img" src="/imgs/logo.png" width="190" height="50"/>
        </a>
    </div>
    <h3>联系我们</h3>
    <p class="description">
        欢迎广大用户、合作伙伴以及开发者与我们进行联系、沟通，一起发现不平凡的未来...
    </p>
    <div class="contact-us">
        <div class="contact-con">
            <p class="intro">网站内容意见：</p>
            <p class="desc">
                网站右下角工具栏"意见反馈"按钮进行提交 或 邮箱地址：1060684139@qq.com
            </p>
        </div>

        <div class="contact-con">
            <p class="intro">广告投放合作：</p>
            <p class="desc">
                邮箱地址：postmaster@laraveler.net
            </p>
            <p class="desc">
                电话号码：13676225868
            </p>
        </div>

        <div class="contact-con">
            <p class="intro">Laraveler交流讨论群：</p>
            <p class="desc">
                QQ群号码：
            </p>
        </div>

        <div class="contact-con">
            <p class="intro">Laraveler官方微信：</p>
            <p class="desc">
                <img src="imgs/contact.jpg" alt="官方微信">
            </p>
        </div>
    </div>
@stop
