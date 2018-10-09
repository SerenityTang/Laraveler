<div class="comment-list">
    <h4 class="media-heading media-heading-extra">
        <a class="author reply-author"
           href="{{ url('user/'.$comment->user->personal_domain) }}">{{ $comment->user->username }}</a>
        <span class="separate">:</span>
        @if($comment->to_user_id != null)
            <a class="author"
               href="{{ url('user/'.$comment->user->personal_domain) }}">@ {{ $comment->toUser->username }}</a>
        @endif

        {!! $comment->content !!}
    </h4>
    <span class="time" title="{{ $comment->created_at }}">
            {!! $comment->created_at !!}
        </span>
    @if(Auth::check() && $comment->user_id != Auth::user()->id)
        <span title="回复"><a href="javascript:void(0);" id="reply-icon" class="reply-icon"
                            data-user-id="{{ $comment->user_id }}" data-user-name="{{ $comment->user->username }}"><i
                        class="iconfont icon-icon_reply"></i>回复</a></span>
    @endif
</div>

<script src="{{ asset('libs/jquery-timeago/jquery.timeago.js') }}"></script>
<script src="{{ asset('libs/jquery-timeago/locales/jquery.timeago.zh-CN.js') }}"></script>
<script>
    $(".time").timeago();
</script>
