@if(isset($best_answer))
    <ul class="list-group best-answer">
        <li class="list-group-item best-answer-bg">
            <div class="best-answer-title">
                <svg class="icon" aria-hidden="true">
                    <use xlink:href="#icon-guanjun"></use>
                </svg>
                <span>最佳答案</span>
            </div>
            <div class="media">
                <a class="media-left ans-avatar avatar-40"
                   href="{{ url('user/'.$best_answer->user->personal_domain) }}">
                    <img src="{{ App\Helpers\Helpers::get_user_avatar($best_answer->user_id, 'middle') }}"
                         class="avatar-40" alt="{{ $best_answer->user->username }}">
                </a>

                <div class="media-body">
                    <h4 class="media-heading best-answer-author">
                        <a class="author-name"
                           href="{{ url('user/'.$best_answer->user->personal_domain) }}">{{ $best_answer->user->username }}</a>
                    </h4>
                    {!! $best_answer->content !!}
                    <h4 class="media-heading author">
                        <a class="author-name"
                           href="{{ url('user/'.$question->user->personal_domain) }}">{{ $question->user->username }}</a>
                        <span class="separate">采纳于</span>
                        <span class="time" title="{{ $best_answer->created_at }}">
                            {!! $best_answer->created_at !!}
                        </span>
                    </h4>
                </div>
            </div>
        </li>
    </ul>
@endif