<div class="side-menu">
    <div>

    </div>
    <div class="list-wapper">
        <h3>个人中心</h3>
        <div class="line"></div>
        <ul class="menu">
            <li>
                <a href="{{url('user/'.Auth::user()->username)}}" class="{{ App\Helpers\Helpers::setActive('user/'.Auth::user()->username, 'extra') }}">我的主页<i class="iconfont icon-1202youjiantou"></i></a>
            </li>
            <li>
                <a href="{{url('user/'.Auth::user()->username.'question')}}" class="{{ App\Helpers\Helpers::setActive('user/'.Auth::user()->username.'question', 'extra') }}">我的问答<i class="iconfont icon-1202youjiantou"></i></a>
            </li>
            <li>
                <a href="{{url('user/'.Auth::user()->username.'/replies')}}" class="{{ App\Helpers\Helpers::setActive('user/'.Auth::user()->username.'/replies', 'extra') }}">我的回复<i class="iconfont icon-1202youjiantou"></i></a>
            </li>
            <li>
                <a href="{{url('user/'.Auth::user()->username.'/articles')}}" class="{{ App\Helpers\Helpers::setActive('user/'.Auth::user()->username.'/articles', 'extra') }}">我的文章<i class="iconfont icon-1202youjiantou"></i></a>
            </li>
            <li>
                <a href="{{url('user/'.Auth::user()->username.'/following')}}" class="{{ App\Helpers\Helpers::setActive('user/'.Auth::user()->username.'/following', 'extra') }}">我的关注<i class="iconfont icon-1202youjiantou"></i></a>
            </li>
            <li>
                <a href="{{url('user/'.Auth::user()->username.'/fans')}}" class="{{ App\Helpers\Helpers::setActive('user/'.Auth::user()->username.'/fans', 'extra') }}">我的粉丝<i class="iconfont icon-1202youjiantou"></i></a>
            </li>
            <li>
                <a href="{{url('user/'.Auth::user()->username.'/likes')}}" class="{{ App\Helpers\Helpers::setActive('user/'.Auth::user()->username.'/likes', 'extra') }}">我的点赞<i class="iconfont icon-1202youjiantou"></i></a>
            </li>
            <li>
                <a href="{{url('user/'.Auth::user()->username.'/favorites')}}" class="{{ App\Helpers\Helpers::setActive('user/'.Auth::user()->username.'/favorites', 'extra') }}">我的收藏<i class="iconfont icon-1202youjiantou"></i></a>
            </li>
        </ul>
    </div>
</div>