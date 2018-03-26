<li class="list-group-item warm-user">
    @foreach($warm_users as $warm_user)
        <a href="{{ url('user/'.$warm_user->personal_domain) }}">
            <img src="{{ App\Helpers\Helpers::get_user_avatar($warm_user->id, 'medium') }}" data-toggle="tooltip" title="{{$warm_user->username}} 回答数：{{$warm_user->answer_count}}" alt="{{ $warm_user->username }}">
        </a>
    @endforeach
</li>

<script>
    $(function () { $("[data-toggle='tooltip']").tooltip(); });
</script>