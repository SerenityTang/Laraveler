<ul class="list-group comment-con">
    @foreach($comments as $comment)
        <li class="list-group-item comment-item">
            <div class="media">
                <a class="media-left ans-avatar avatar-40" href="{{ url('user/'.$comment->user->personal_domain) }}">
                    <img src="{{ App\Helpers\Helpers::get_user_avatar($comment->user_id, 'middle') }}" class="avatar-40" alt="{{ $comment->user->username }}">
                </a>
                <div class="media-body">
                    <h4 class="media-heading">
                        <a class="author-name" href="{{ url('user/'.$comment->user->personal_domain) }}"><strong>{{ $comment->user->username }}</strong></a>
                        <span class="separate">评论于</span>
                        <span class="time" title="{{ $comment->created_at }}">
                            {!! $comment->created_at !!}
                        </span>
                    </h4>

                    {!! $comment->content !!}

                    <div class="operation">
                        <span class="oper-left">
                            <a href="">
                                <i class="iconfont icon-dianzan"></i>
                            </a>
                            <span class="like-count">{{ $comment->support_count }}</span>

                            <a href="javascript:void(0)" class="reply-icon">
                                <i class="iconfont icon-icon_reply"></i>
                            </a>
                        </span>

                        <span class="oper-right">
                            <a href=""><i class="iconfont icon-bianji1 edit-icon"></i></a>
                            <a href=""><i class="iconfont icon-weibiaoti544 delete-icon"></i></a>
                        </span>
                    </div>
                </div>
            </div>

            @foreach($mutual_comments as $comment)
                <div class="form-group comment-input">
                    <div class="mutual-comment">
                        <h4 class="media-heading media-heading-extra">
                            <a class="author reply-author" href="{{ url('user/'.$comment->user->personal_domain) }}">{{ $comment->user->username }}</a>
                            <span class="separate">:</span>
                            @if($comment->to_user_id != null)
                                <a class="author" href="{{ url('user/'.$comment->user->personal_domain) }}">@ {{ $comment->toUser->username }}</a>
                            @endif

                            {!! $comment->content !!}
                        </h4>
                        <span class="time" title="{{ $comment->created_at }}">
                            {!! $comment->created_at !!}
                        </span>
                        @if(Auth::check() && $comment->user_id != Auth::user()->id)
                            <span title="回复"><a href="javascript:void(0);" id="reply-icon" class="reply-icon" data-user-id="{{ $comment->user_id }}" data-user-name="{{ $comment->user->username }}"><i class="iconfont icon-icon_reply"></i>回复</a></span>
                        @endif
                    </div>
                </div>
            @endforeach

            <div class="comment-form">
                <form id="comment-content" method="post" action="{{ url('comment/store') }}">
                    <input type="hidden" id="editor_token" name="_token" value="{{ csrf_token() }}" />
                    <input type="hidden" id="comment_id" name="comment_id" value="{{ $comment->id }}">
                    <textarea id="comment_part_con" name="comment_part_con" class="form-control comment-part-con" rows="2"></textarea>
                    <div class="comment-part-bottom">
                        <button type="button" class="btn btn-cancel">取消</button>
                        <button type="button" class="btn btn-reply">回复</button>
                    </div>
                </form>
            </div>
        </li>
    @endforeach
</ul>
