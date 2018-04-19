@extends('pc.layouts.app')
@section('title')
    {{ $user->username }} 的粉丝 | @parent
@stop
@section('css')
    <link href="{{ asset('css/user/default.css') }}" rel="stylesheet">
@stop

@section('content')
    @include('pc.user.partials.homepage_header')

    <div class="container main-container">
        <div class="row">
            {{--左侧菜单--}}
            <div class="col-md-3">
                @include('pc.user.partials.home-side-menu')
            </div>

            <div class="col-md-9">
                <div class="panel panel-default right-container">
                    <h4 class="title">@if($user->id != (Auth::check() ? Auth::user()->id : 0)) TA的粉丝 @else 我的粉丝 @endif</h4>

                    <div class="attention-user">
                        <ul class="list-group">
                            @foreach($fans as $fan)
                                <li class="list-group-item list-">
                                    <div class="row">
                                        <div class="col-md-1">
                                            <a class="att-u-name" href="{{ url('user/'.\App\Helpers\Helpers::get_user($fan->user_id)->personal_domain) }}">
                                                <img src="{{ App\Helpers\Helpers::get_user_avatar($fan->user_id, 'medium') }}" class="avatar-46" alt="{{ \App\Helpers\Helpers::get_user($fan->user_id)->username }}">
                                            </a>
                                        </div>
                                        <div class="col-md-8">
                                            <a class="att-u-name" href="{{ url('user/'.\App\Helpers\Helpers::get_user($fan->user_id)->personal_domain) }}">{{ \App\Helpers\Helpers::get_user($fan->user_id)->username }}</a>
                                            <p class="data">
                                                积分 {{ \App\Helpers\Helpers::get_user_data($fan->user_id)->credits }}
                                                · 回答 {{ \App\Helpers\Helpers::get_user_data($fan->user_id)->answer_count }}
                                                · 粉丝 {{ \App\Helpers\Helpers::get_user_data($fan->user_id)->fan_count }}
                                            </p>
                                        </div>
                                        <div class="col-md-1 col-md-offset-2">
                                            <a class="attention-icon" style="margin-bottom: 3px;" href="javascript:void(0)" data-user="{{ $fan->user_id }}" data-curr-user="{{ Auth::check() ? Auth::user()->id : 0 }}">
                                                @if(\App\Helpers\Helpers::attention($fan->user_id, 'User', (Auth::check() ? Auth::user()->id : 0)) == null)
                                                    关注
                                                @else
                                                    已关注
                                                @endif
                                            </a>
                                            <a class="pri-letter-icon" href="">私信</a>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('footer')
    <script>
        //关注用户
        $(function () {
            $('.attention-icon').click(function () {
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
                            $('.attention-icon').html('已关注');
                        } else if (data == 'unattention') {
                            $('.attention-icon').html('关注');
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
@stop
