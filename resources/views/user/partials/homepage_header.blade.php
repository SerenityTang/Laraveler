<div class="homepage-header">
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <img src="{{ App\Helpers\Helpers::get_user_avatar($user->id, 'big') }}" class="avatar-128 homepage-avatar" alt="{{ $user->username }}">
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
                    <button type="button" class="btn btn-attention"><i class="iconfont icon-guanzhu"></i>关注</button>
                    <button type="button" class="btn btn-ask"><i class="iconfont icon-tiwen"></i>向TA提问</button>
                    <button type="button" class="btn btn-pri-letter"><i class="iconfont icon-sixin"></i>私信</button>
                    @else
                    <button type="button" class="btn btn-edit"><i class="iconfont icon-bianji1"></i>编辑资料</button>
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

                    {{--<p>最近活跃于：</p>--}}
                </div>
            </div>
        </div>
    </div>
</div>