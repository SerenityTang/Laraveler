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
                    <a href="{{url('user/'.$user->personal_domain.'/questions')}}" class="{{ App\Helpers\Helpers::setActive('user/'.$user->personal_domain.'/questions', 'extra') }}">TA的问答<i class="iconfont icon-1202youjiantou"></i></a>
                </li>
                <li>
                    <a href="{{url('user/'.$user->personal_domain.'/answers')}}" class="{{ App\Helpers\Helpers::setActive('user/'.$user->personal_domain.'/answers', 'extra') }}">TA的回答<i class="iconfont icon-1202youjiantou"></i></a>
                </li>
                <li>
                    <a href="{{url('user/'.$user->personal_domain.'/blogs')}}" class="{{ App\Helpers\Helpers::setActive('user/'.$user->personal_domain.'/blogs', 'extra') }}">TA的博客<i class="iconfont icon-1202youjiantou"></i></a>
                </li>
                <li>
                    <a href="{{url('user/'.$user->personal_domain.'/attentions')}}" class="{{ App\Helpers\Helpers::setActive('user/'.$user->personal_domain.'/attentions', 'extra') }}">TA的关注<i class="iconfont icon-1202youjiantou"></i></a>
                </li>
                <li>
                    <a href="{{url('user/'.$user->personal_domain.'/fans')}}" class="{{ App\Helpers\Helpers::setActive('user/'.$user->personal_domain.'/fans', 'extra') }}">TA的粉丝<i class="iconfont icon-1202youjiantou"></i></a>
                </li>
                <li>
                    <a href="{{url('user/'.$user->personal_domain.'/supports')}}" class="{{ App\Helpers\Helpers::setActive('user/'.$user->personal_domain.'/supports', 'extra') }}">TA的点赞<i class="iconfont icon-1202youjiantou"></i></a>
                </li>
                <li>
                    <a href="{{url('user/'.$user->personal_domain.'/collections')}}" class="{{ App\Helpers\Helpers::setActive('user/'.$user->personal_domain.'/collections', 'extra') }}">TA的收藏<i class="iconfont icon-1202youjiantou"></i></a>
                </li>
            </ul>
        @else
            <h3>个人中心</h3>
            <div class="line"></div>
            <ul class="menu">
                <li>
                    <a href="{{url('user/'.$user->personal_domain)}}" class="{{ App\Helpers\Helpers::setActive('user/'.$user->personal_domain, 'extra') }}">我的主页<i class="iconfont icon-1202youjiantou"></i></a>
                </li>
                <li>
                    <a href="{{url('user/'.$user->personal_domain.'/questions')}}" class="{{ App\Helpers\Helpers::setActive('user/'.$user->personal_domain.'/questions', 'extra') }}">我的问答<i class="iconfont icon-1202youjiantou"></i></a>
                </li>
                <li>
                    <a href="{{url('user/'.$user->personal_domain.'/answers')}}" class="{{ App\Helpers\Helpers::setActive('user/'.$user->personal_domain.'/answers', 'extra') }}">我的回答<i class="iconfont icon-1202youjiantou"></i></a>
                </li>
                <li>
                    <a href="{{url('user/'.$user->personal_domain.'/blogs')}}" class="{{ App\Helpers\Helpers::setActive('user/'.$user->personal_domain.'/blogs', 'extra') }}">我的博客<i class="iconfont icon-1202youjiantou"></i></a>
                </li>
                <li>
                    <a href="{{url('user/'.$user->personal_domain.'/attentions')}}" class="{{ App\Helpers\Helpers::setActive('user/'.$user->personal_domain.'/attentions', 'extra') }}">我的关注<i class="iconfont icon-1202youjiantou"></i></a>
                </li>
                <li>
                    <a href="{{url('user/'.$user->personal_domain.'/fans')}}" class="{{ App\Helpers\Helpers::setActive('user/'.$user->personal_domain.'/fans', 'extra') }}">我的粉丝<i class="iconfont icon-1202youjiantou"></i></a>
                </li>
                <li>
                    <a href="{{url('user/'.$user->personal_domain.'/supports')}}" class="{{ App\Helpers\Helpers::setActive('user/'.$user->personal_domain.'/supports', 'extra') }}">我的点赞<i class="iconfont icon-1202youjiantou"></i></a>
                </li>
                <li>
                    <a href="{{url('user/'.$user->personal_domain.'/collections')}}" class="{{ App\Helpers\Helpers::setActive('user/'.$user->personal_domain.'/collections', 'extra') }}">我的收藏<i class="iconfont icon-1202youjiantou"></i></a>
                </li>
            </ul>
        @endif
    </div>
</div>