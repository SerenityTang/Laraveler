<ul class="list-group answer-con">
    @foreach($answers as $answer)
        <li class="list-group-item">
            <div class="media">
                <a class="media-left ans-avatar avatar-40" href="{{ url('') }}">
                    <img src="{{ App\Helpers\Helpers::get_user_avatar($answer->user_id, 'middle') }}" class="avatar-40" alt="{{ $answer->user->username }}">
                </a>
                <div class="media-body">
                    <h4 class="media-heading">
                        <a class="author-name" href="{{ url('') }}"><strong>{{ $answer->user->username }}</strong></a>
                        <span class="separate">回复于</span>
                        <span class="time" title="{{ $answer->created_at }}">
                            {!! $answer->created_at !!}
                        </span>
                    </h4>

                    {!! $answer->content !!}
                </div>
                <div class="media-footer">
                    <a href="javascript:void(0);" id="comment-icon" title="评论" class="comment-icon" data-entity_id="{{ $answer->id }}" data-entity_type="Answer">
                        <i class="iconfont icon-pinglun1"></i>评论
                    </a>
                    <span class="comment-count">{{ $answer->comment_count }}</span>
                    @if(Auth::check())
                        @if($question->user_id == Auth::user()->id && !isset($best_answer))
                            <a href="javascript:void(0);" title="采纳" class="adopt-icon" data-answer-id="{{ $answer->id }}">
                                <i class="iconfont icon-lejiejieshou"></i>采纳
                            </a>
                        @endif
                    @endif
                    <a href="{{ url('') }}" title="奖励" class="award-icon">
                        <i class="iconfont icon-web__jiangli"></i>奖励
                    </a>
                    <a href="javascript:void(0);" title="支持" id="support-icon" class="support-icon @if(\App\Helpers\Helpers::support($answer->id, 'Answer', 'support') != null) active @endif" data-answer-id="{{ $answer->id }}" data-answer-uid="{{ $answer->user_id }}" data-answer-curruid="{{ Auth::check() ? Auth::user()->id : 0 }}">
                        <i class="iconfont icon-dianzan"></i>
                        <span class="support-count">{{ $answer->support_count }}</span>
                    </a>
                    <a href="javascript:void(0);" title="反对"id="oppose-icon" class="oppose-icon @if(\App\Helpers\Helpers::support($answer->id, 'Answer', 'opposition') != null) active @endif" data-answer-id="{{ $answer->id }}" data-answer-uid="{{ $answer->user_id }}" data-answer-curruid="{{ Auth::check() ? Auth::user()->id : 0 }}">
                        <i class="iconfont icon-ai46" style="font-size: 18px;position: relative;top: 2px;"></i>
                        <span class="oppose-count">{{ $answer->opposition_count }}</span>
                    </a>
                </div>
                <div id="media-comment" class="media-comment" style="display: none;"></div>
            </div>
        </li>
    @endforeach
</ul>