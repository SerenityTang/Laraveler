 <ol class="list-group list-actives">
    @foreach($credit_users as $credit_user)
    <li class="list-active">
        <img src="{{ App\Helpers\Helpers::get_user_avatar($credit_user->id, 'small') }}" class="avatar-27" alt="{{ $credit_user->username }}">
        <a href="{{ url('user/'.$credit_user->personal_domain) }}">{{ $credit_user->username }}</a>
        <span class="credit">{{ $credit_user->credits }} 积分</span>
    </li>
    @endforeach
</ol>