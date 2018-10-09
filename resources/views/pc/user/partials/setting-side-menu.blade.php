<nav class="leftnav">
    <ul>
        <li style="cursor: default;"><h4 class="left-title"><i class="iconfont icon-icon-" style="font-size: 17px;"></i>账户信息
            </h4></li>
        <li class="{{ App\Helpers\Helpers::setActive('user/'.Auth::user()->username.'/settings', 'extra') }}"><a
                    href="{{ url('user/'.Auth::user()->username.'/settings') }}"><i
                        class="iconfont icon-msnui-person-info"></i>个人信息</a></li>
        <li class="{{ App\Helpers\Helpers::setActive('user/'.Auth::user()->username.'/authenticate', 'extra') }}"><a
                    href="{{ url('user/'.Auth::user()->username.'/authenticate') }}" class=""><i
                        class="iconfont icon-shimingrenzheng"></i>实名认证</a></li>
        <li class="{{ App\Helpers\Helpers::setActive('user/'.Auth::user()->username.'/edit_password', 'extra') }}"><a
                    href="{{ url('user/'.Auth::user()->username.'/edit_password') }}" class=""><i
                        class="iconfont icon-mima"></i>密码修改</a></li>
        <li class="{{ App\Helpers\Helpers::setActive('user/'.Auth::user()->username.'/edit_notify', 'extra') }}"><a
                    href="{{ url('user/'.Auth::user()->username.'/edit_notify') }}" class=""><i
                        class="iconfont icon-xiaoxi1"></i>通知私信</a></li>
        <li class="{{ App\Helpers\Helpers::setActive('user/'.Auth::user()->username.'/security', 'extra') }}"><a
                    href="{{ url('user/'.Auth::user()->username.'/security') }}" class=""><i
                        class="iconfont icon-anquan"></i>账号安全</a></li>
        <li class="{{ App\Helpers\Helpers::setActive('user/'.Auth::user()->username.'/bindsns', 'extra') }}"><a
                    href="{{ url('user/'.Auth::user()->username.'/bindsns') }}" class=""><i
                        class="iconfont icon-msnui-bind-circle"></i>账号绑定</a></li>
    </ul>
</nav>