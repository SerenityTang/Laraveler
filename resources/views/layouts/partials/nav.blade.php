<nav id="navbar-serenity" class="navbar navbar-fixed-top">
    <div class="container-fluid navbar-container">
        <div class="navbar-header">
            <a class="navbar-brand all-index-logo" href="{{ url('/') }}" title="Serenity" style="padding: inherit;">
                <img class="all-index-logo-img" src="/imgs/logo.png" width="190" height="50"/>
            </a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-left nav-column">
                <li>
                    <a href="{{ url('/') }}" class="version-link {{ App\Helpers\Helpers::setActive('/', 'active') }}">首页</a>
                </li>

                <li>
                    <a href="{{ url('/question') }}" class="version-link {{ App\Helpers\Helpers::setActive('question', 'active') }}">问答</a>
                </li>

                <li>
                    <a href="{{ url('/blog') }}" class="version-link {{ App\Helpers\Helpers::setActive('blog', 'active') }}">博客</a>
                </li>

                <li>
                    <a href="{{ url('/tag') }}" class="version-link {{ App\Helpers\Helpers::setActive('tag', 'active') }}">标签</a>
                </li>
                
                <li>
                    <a href="{{ url('/workshow') }}" class="version-link {{ App\Helpers\Helpers::setActive('workshow', 'active') }}">作品展示</a>
                </li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li class="search-bar">
                    <form action="{{ url('') }}" method="get" target="_blank" accept-charset="utf-8">
                        <input type="text" id="search-text" class="search-text" placeholder="请输入你想要滴 ^_^ ">
                        <a href="{{ url('') }}" class="search-btn">
                            <i class="iconfont icon-sousuo"></i>
                        </a>
                        <div class="hot-search">
                            <i class="iconfont icon-jiantouarrow492"></i>
                            <div class="search-content">
                                <ul>
                                    <li><a href="">Laravel</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="search-tip">
                            <i class="iconfont icon-jiantouarrow492"></i>
                            <div class="search-content">
                                <ul>
                                    <li><a href="">前端开发</a></li>
                                    <li><a href="">Bootstrap</a></li>
                                </ul>
                            </div>
                        </div>
                    </form>
                </li>

                @if(Auth::guest())
                    <li>
                        <a href="{{ url('login') }}" class="btn btn-login">
                            <i class="iconfont icon-denglu"></i>
                            登录
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('register') }}" class="btn btn-regist">
                            <i class="iconfont icon-zhuce"></i>
                            注册
                        </a>
                    </li>
                @else
                    {{--消息--}}
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle notify-icon" data-toggle="dropdown">
                            <i class="iconfont icon-xiaoxi2"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-extra">
                            <ul id="myTab" class="nav nav-tabs">
                                <li class="active first">
                                    <a href="#news" data-toggle="tab">
                                        <i class="iconfont icon-xiaoxi"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#pri-letter" data-toggle="tab">
                                        <i class="iconfont icon-wodexiaoxi"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#attention" data-toggle="tab">
                                        <i class="iconfont icon-chakantiezigengduozhiguanzhulouzhu"></i>
                                    </a>
                                </li>
                            </ul>
                            <div id="myTabContent" class="tab-content">
                                <div class="tab-pane fade in active" id="news">
                                    <p>此功能暂未开通</p>
                                </div>
                                <div class="tab-pane fade" id="pri-letter">
                                    <p>此功能暂未开通</p>
                                </div>
                                <div class="tab-pane fade" id="attention">
                                    <p>此功能暂未开通</p>
                                </div>
                            </div>
                        </ul>
                    </li>
                    {{--发布--}}
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle publish-icon" data-toggle="dropdown">
                            <i class="iconfont icon-tianjia1"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-publish">
                            <li>
                                <a href="{{ url('question/create') }}"><i class="fa fa-comments fa-lg"></i>发布问答</a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="{{ url('blog/create') }}"><i class="fa fa-pencil fa-lg"></i>发布博客</a>
                            </li>
                        </ul>
                    </li>
                    {{--个人信息--}}
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle avatar" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="{{ App\Helpers\Helpers::get_user_avatar(Auth()->user()->id, 'medium') }}" class="avatar-46" alt="{{ Auth::user()->username }}">
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-publish">
                            {{--@if(Auth::user()->can('admin.question.manage'))
                                <li>
                                    <a href="{{ url('user/'.Auth::user()->username) }}"><i class="fa fa-gears fa-lg"></i>系统管理</a>
                                </li>
                                <li class="divider"></li>
                            @endif--}}
                            <li>
                                <a href="{{ url('user/'.Auth::user()->personal_domain) }}"><i class="fa fa-user fa-lg"></i>个人主页</a>
                            </li>
                            <li>
                                <a href="{{ url('user/'.Auth::user()->username.'/settings') }}"><i class="fa fa-gear fa-lg"></i>个人设置</a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="{{ url('logout') }}"><i class="fa fa-sign-out fa-lg"></i>安全退出</a>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
<script>
    $('#navbar-serenity .dropdown .dropdown-menu .nav li').on('click', function () {
        $(this).siblings().css('border-bottom', 'none');
        $(this).siblings().find('a').children('i').css('color', '#787878');
        $(this).css('border-bottom', '2px solid #22d7bb');
        $(this).find('i').css('color', '#22d7bb');
    })
</script>