<div class="homepage-header">
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <a href="{{ url('user/'.$user->personal_domain) }}"><img src="{{ App\Helpers\Helpers::get_user_avatar($user->id, 'big') }}" class="avatar-128 homepage-avatar" alt="{{ $user->username }}"></a>
            </div>

            <div class="col-md-6 homepage-middle">
                <h3 class="homepage-username">
                    {{ $user->username }}
                    <span>于{{ date('Y-m-d', strtotime($user->created_at)) }}加入Serenity的大家庭！</span>
                </h3>

                <p class="homepage-info">
                    <span>@if($user->gender == 0) 男 @else 女 @endif</span>
                    <span>{{ $user->province }} {{ $user->city }}</span>
                    <span>{{ $user->career_direction }}</span>
                </p>
                <p class="homepage-desc">
                    @if(empty($user->description))
                    {{ $user->username }}很懒，签名神马都没留下~~~
                    @else
                    {{ $user->description }}
                    @endif
                </p>

                <div class="homepage-btn">
                    @if($user->id != (Auth::check() ? Auth::user()->id : 0))
                        <a class="btn btn-attention @if(\App\Helpers\Helpers::attention($user->id, 'User', (Auth::check() ? Auth::user()->id : 0)) != null) active @endif" data-user="{{ $user->id }}" data-curr-user="{{ Auth::check() ? Auth::user()->id : 0 }}">
                            @if(\App\Helpers\Helpers::attention($user->id, 'User', (Auth::check() ? Auth::user()->id : 0)) == null)
                                <i class="iconfont icon-guanzhu"></i>关注
                            @else
                                <i class="iconfont icon-guanzhu"></i>已关注
                            @endif
                        </a>
                        <a class="btn btn-ask"><i class="iconfont icon-tiwen"></i>向TA提问</a>
                        <a class="btn btn-pri-letter"><i class="iconfont icon-sixin"></i>私信</a>
                    @else
                        <a href="{{ url('user/'.Auth::user()->username.'/settings') }}" class="btn btn-edit"><i class="iconfont icon-bianji1"></i>编辑资料</a>
                    @endif
                </div>
            </div>

            <div class="col-md-4 homepage-bottom">
                <div class="row">
                    <div class="col-md-12">
                        <div class="item">
                            <span class="count">{{ $user_data->coins }}</span>
                            <span class="text">金币</span>
                        </div>
                        <div class="item">
                            <span class="count">{{ $user_data->credits }}</span>
                            <span class="text">积分</span>
                        </div>
                        <div class="item">
                            <span class="count">{{ $user_data->attention_count }}</span>
                            <span class="text">关注</span>
                        </div>
                        <div class="item">
                            <span class="count">{{ $user_data->fan_count }}</span>
                            <span class="text">粉丝</span>
                        </div>
                        {{--<div class="item">
                            <span class="count">{{ $user_data->answer_count }}</span>
                            <span class="text">回答</span>
                        </div>--}}
                        <div class="item">
                            <span class="count">{{ $user_data->article_count }}</span>
                            <span class="text">博客</span>
                        </div>
                    </div>

                    <div class="col-md-12 dynamic">
                        <p><i class="iconfont icon-fangwenliang"></i> 主页被访问次数：{{ $user_data->view_count }}</p>
                        <p>最近登录：{{ $user->last_login_at }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        $('.btn-attention').click(function () {
            var user = $(this).data('user');
            var curr_user = $(this).data('curr-user');
            @if(!\Auth::check())
                window.location.href = '{{ url('/login') }}';
            @else
                $.ajax({
                    type : 'POST',
                    data : {
                        _token: '{{ csrf_token() }}',
                        'user': user,
                        'curr_user':curr_user
                    },
                    url : '{{ url('/user/attention_user') }}',
                    success: function (data) {
                        if (data == 'attention') {
                            $('.btn-attention').html('<i class="iconfont icon-guanzhu"></i>已关注');
                            $('.btn-attention').addClass('active');
                        } else if (data == 'unattention') {
                            $('.btn-attention').html('<i class="iconfont icon-guanzhu"></i>关注');
                            $('.btn-attention').removeClass('active');
                        }
                    },
                    error: function () {
                        layer.msg('系统错误');
                    }
                });
            @endif
        });
    });
</script>