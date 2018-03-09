<div class="side-menu">
    <div>

    </div>
    <div class="list-wapper">
        @if($user->id != (Auth::check() ? Auth::user()->id : 0))
            <h3>TA的个人中心</h3>
            <div class="line"></div>
            <ul class="menu">
                <li>
                    <a href="{{url('user/'.$user->personal_domain)}}" class="{{ App\Helpers\Helpers::setActive('user/'.$user->username, 'extra') }}">TA的主页<i class="iconfont icon-1202youjiantou"></i></a>
                </li>
                <li>
                    <a href="{{url('user/'.$user->personal_domain.'/questions')}}" class="{{ App\Helpers\Helpers::setActive('user/'.$user->personal_domain.'question', 'extra') }}">TA的问答<i class="iconfont icon-1202youjiantou"></i></a>
                </li>
                <li>
                    <a href="{{url('user/'.$user->personal_domain.'/replies')}}" class="{{ App\Helpers\Helpers::setActive('user/'.$user->personal_domain.'/replies', 'extra') }}">TA的回复<i class="iconfont icon-1202youjiantou"></i></a>
                </li>
                <li>
                    <a href="{{url('user/'.$user->personal_domain.'/articles')}}" class="{{ App\Helpers\Helpers::setActive('user/'.$user->personal_domain.'/articles', 'extra') }}">TA的文章<i class="iconfont icon-1202youjiantou"></i></a>
                </li>
                <li>
                    <a href="{{url('user/'.$user->personal_domain.'/attentions')}}" class="{{ App\Helpers\Helpers::setActive('user/'.$user->personal_domain.'/following', 'extra') }}">TA的关注<i class="iconfont icon-1202youjiantou"></i></a>
                </li>
                <li>
                    <a href="{{url('user/'.$user->personal_domain.'/fans')}}" class="{{ App\Helpers\Helpers::setActive('user/'.$user->personal_domain.'/fans', 'extra') }}">TA的粉丝<i class="iconfont icon-1202youjiantou"></i></a>
                </li>
                <li>
                    <a href="{{url('user/'.$user->personal_domain.'/likes')}}" class="{{ App\Helpers\Helpers::setActive('user/'.$user->personal_domain.'/likes', 'extra') }}">TA的点赞<i class="iconfont icon-1202youjiantou"></i></a>
                </li>
                <li>
                    <a href="{{url('user/'.$user->personal_domain.'/favorites')}}" class="{{ App\Helpers\Helpers::setActive('user/'.$user->personal_domain.'/favorites', 'extra') }}">TA的收藏<i class="iconfont icon-1202youjiantou"></i></a>
                </li>
            </ul>
        @else
            <h3>个人中心</h3>
            <div class="line"></div>
            <ul class="menu">
                <li>
                    <a href="{{url('user/'.Auth::user()->username)}}" class="{{ App\Helpers\Helpers::setActive('user/'.Auth::user()->username, 'extra') }}">我的主页<i class="iconfont icon-1202youjiantou"></i></a>
                </li>
                <li>
                    <a href="{{url('user/'.Auth::user()->username.'/questions')}}" class="{{ App\Helpers\Helpers::setActive('user/'.Auth::user()->username.'question', 'extra') }}">我的问答<i class="iconfont icon-1202youjiantou"></i></a>
                </li>
                <li>
                    <a href="{{url('user/'.Auth::user()->username.'/replies')}}" class="{{ App\Helpers\Helpers::setActive('user/'.Auth::user()->username.'/replies', 'extra') }}">我的回复<i class="iconfont icon-1202youjiantou"></i></a>
                </li>
                <li>
                    <a href="{{url('user/'.Auth::user()->username.'/articles')}}" class="{{ App\Helpers\Helpers::setActive('user/'.Auth::user()->username.'/articles', 'extra') }}">我的文章<i class="iconfont icon-1202youjiantou"></i></a>
                </li>
                <li>
                    <a href="{{url('user/'.Auth::user()->username.'/attentions')}}" class="{{ App\Helpers\Helpers::setActive('user/'.Auth::user()->username.'/following', 'extra') }}">我的关注<i class="iconfont icon-1202youjiantou"></i></a>
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
        @endif
    </div>
</div>