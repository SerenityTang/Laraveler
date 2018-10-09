<div class="weui-tabbar bottom-tabbar bottom-tabbar-display">
    <a href="/" class="weui-tabbar__item bottom-item {{ App\Helpers\Helpers::setActive('/', 'active') }}">
        {{--<span class="weui-badge" style="position: absolute;top: -.4em;right: 1em;"></span>--}}
        <div class="weui-tabbar__icon">
            <i class="iconfont icon-shouye shouye-icon active-color"></i>
        </div>
        <p class="weui-tabbar__label">首页</p>
    </a>
    <a href="{{ url('/question') }}"
       class="weui-tabbar__item bottom-item {{ App\Helpers\Helpers::setActive('question', 'active') }}">
        <div class="weui-tabbar__icon">
            <i class="iconfont icon-wenda wenda-icon active-color"></i>
        </div>
        <p class="weui-tabbar__label">问答</p>
    </a>
    <a href="{{ url('/blog') }}"
       class="weui-tabbar__item bottom-item {{ App\Helpers\Helpers::setActive('blog', 'active') }}">
        <div class="weui-tabbar__icon">
            <i class="iconfont icon-wenzhang blog-icon active-color"></i>
        </div>
        <p class="weui-tabbar__label">博客</p>
    </a>
    <a href="{{ url('/tag') }}"
       class="weui-tabbar__item bottom-item {{ App\Helpers\Helpers::setActive('tag', 'active') }}">
        <div class="weui-tabbar__icon">
            <i class="iconfont icon-iconset0170 tag-icon active-color"></i>
        </div>
        <p class="weui-tabbar__label">标签</p>
    </a>
    <a href="{{ url('/workshow') }}"
       class="weui-tabbar__item bottom-item {{ App\Helpers\Helpers::setActive('workshow', 'active') }}">
        <div class="weui-tabbar__icon">
            <i class="iconfont icon-web web-icon active-color"></i>
        </div>
        <p class="weui-tabbar__label">作品</p>
    </a>
</div>