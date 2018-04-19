@foreach($day_hot_questions as $question)
    <li class="list-group-item hot-list-item">
        <h2 class="title">
            <a href="{{ url('question/show/' . $question->id) }}" title="{{ $question->title }}">{{ $question->title }}</a>
        </h2>
        <a class="author" href="{{ url('user/'.$question->user->personal_domain) }}">
            <img src="{{ App\Helpers\Helpers::get_user_avatar($question->user_id, 'small') }}" class="avatar-24" alt="{{ $question->user->username }}">
            <span class="username">{{ $question->user->username }} / </span>
        </a>
        <span class="time" title="{{ $question->created_at }}">
            {!! $question->created_at !!}
        </span>

        <div class="ques-count">
            <span title="浏览数"><i class="iconfont icon-liulan"></i>{{$question->view_count}}</span>
            <span>|</span>
            <span title="投票数"><i class="iconfont icon-toupiao"></i>{{$question->vote_count}}</span>
            <span>|</span>
            <span title="回答数"><i class="iconfont icon-tubiaopinglunshu"></i>{{$question->answer_count}}</span>
            <span>|</span>
            <span title="关注数"><i class="iconfont icon-guanzhu"></i>{{$question->attention_count}}</span>
        </div>
    </li>
@endforeach

<script src="{{ asset('libs/jquery-timeago/jquery.timeago.js') }}"></script>
<script src="{{ asset('libs/jquery-timeago/locales/jquery.timeago.zh-CN.js') }}"></script>
<script>
    $(".time").timeago();
</script>