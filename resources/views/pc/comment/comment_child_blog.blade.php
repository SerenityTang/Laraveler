<div class="mutual-comment">
    <h4 class="media-heading media-heading-extra">
        <a class="author reply-author" href="{{ url('user/'.$mutual_comment->user->personal_domain) }}">{{ $mutual_comment->user->username }}</a>
        <span class="separate">:</span>
        @if($mutual_comment->to_user_id != null)
            <a class="author" href="{{ url('user/'.$mutual_comment->user->personal_domain) }}">@ {{ $mutual_comment->toUser->username }}</a>
        @endif

        {!! $mutual_comment->content !!}
    </h4>
    <span class="time" title="{{ $mutual_comment->created_at }}">
                                                            {!! $mutual_comment->created_at !!}
                                                        </span>
    @if(Auth::check() && $mutual_comment->user_id != Auth::user()->id)
        <span title="回复"><a href="javascript:void(0);" id="sub-reply-icon" class="sub-reply-icon" data-user-id="{{ $mutual_comment->user_id }}" data-user-name="{{ $mutual_comment->user->username }}"><i class="iconfont icon-icon_reply"></i>回复</a></span>
    @endif
</div>