
    <ol class="list-group list-actives">
        @foreach($active_users as $active_user)
        <li class="list-active">
            <img src="{{ App\Helpers\Helpers::get_user_avatar($active_user->id, 'small') }}" class="avatar-27" alt="{{ $active_user->username }}">
            <a href="{{ url('') }}">{{ $active_user->username }}</a>
            <span class="credit"><span title="回答数">{{ $active_user->answer_count }}</span> / <span title="博客数">{{ $active_user->article_count }}</span></span>
        </li>
        @endforeach
    </ol>