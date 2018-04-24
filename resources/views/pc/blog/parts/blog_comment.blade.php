@foreach($comments as $comment)
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
                <a href="javascript:void(0)" title="支持" class="like-icon @if(\App\Helpers\Helpers::support($comment->id, 'Comment', 'support') != null)active @endif" data-comment-id="{{ $comment->id }}">
                    <i class="iconfont icon-dianzan"></i>
                    <span class="like-count @if(\App\Helpers\Helpers::support($comment->id, 'Comment', 'support') != null)active @endif">{{ $comment->support_count }}</span>
                </a>

                <a href="javascript:void(0)" title="回复" class="reply-icon" data-user-name="{{ $comment->user->username }}" data-user-id="{{ $comment->user_id }}">
                    <i class="iconfont icon-icon_reply"></i>
                </a>
            </span>

            <span class="oper-right">
                <a href="javascript:void(0)" title="编辑"><i class="iconfont icon-bianji1 edit-icon"></i></a>
                <a href="javascript:void(0)" title="删除"><i class="iconfont icon-weibiaoti544 delete-icon" data-comment-id="{{ $comment->id }}"></i></a>
            </span>
        </div>
    </div>
@endforeach

<script src="{{ asset('libs/jquery-timeago/jquery.timeago.js') }}"></script>
<script src="{{ asset('libs/jquery-timeago/locales/jquery.timeago.zh-CN.js') }}"></script>
<script>
    $(".time").timeago();
</script>
<script>
    $(function () {
        //评论回复输入框切换显示隐藏
        $('.reply-icon').click(function () {
            var icon = $(this);
            var user_name = $(this).data('user-name');
            var user_id = $(this).data('user-id');
            $('#to_user').val(user_id);
            @if (Auth::check())
            if (icon.parents('.comment-item').find('.comment-form').is(":hidden")) {
                icon.parents('.comment-item').find('.comment-form textarea').val('');
                icon.parents('.comment-item').find('.comment-form textarea').attr('placeholder', '@ '+user_name);
                icon.parents('.comment-item').find('.comment-form').slideToggle('slow');
            } else {
                icon.parents('.comment-item').find('.comment-form textarea').val('');
                icon.parents('.comment-item').find('.comment-form textarea').attr('placeholder', '@ '+user_name);
            }
            @else
                window.location.href = '{{ url('/login') }}';
            @endif
        });
        //子评论回复输入框切换显示隐藏
        $('.sub-reply-icon').click(function () {
            var icon = $(this);
            var user_name = $(this).data('user-name');
            var user_id = $(this).data('user-id');
            $('#to_user').val(user_id);
            @if (Auth::check())
            if (icon.parents('.comment-item').find('.comment-form').is(":hidden")) {
                icon.parents('.comment-item').find('.comment-form textarea').val('');
                icon.parents('.comment-item').find('.comment-form textarea').attr('placeholder', '@ '+user_name);
                icon.parents('.comment-item').find('.comment-form').slideToggle('slow');
            } else {
                icon.parents('.comment-item').find('.comment-form textarea').val('');
                icon.parents('.comment-item').find('.comment-form textarea').attr('placeholder', '@ '+user_name);
            }
            @else
                window.location.href = '{{ url('/login') }}';
            @endif
        });
        //取消隐藏输入框
        $('.btn-cancel').click(function () {
            $(this).parents('.comment-item').find('.comment-form').hide('slow');
        });

        //评论右下角按钮显示隐藏
        $('.comment-content ul.comment-con .media').hover(function () {
            $(this).find('.media-body .operation span.oper-right').fadeIn();
        }, function () {
            $(this).find('.media-body .operation span.oper-right').fadeOut();
        });

        //评论回复
        $('.btn-child-reply').click(function () {
            var icon = $(this);
            var postData = icon.parents('.comment-form').find("#comment-content").serializeArray();
            $.post('/comment/mutual_blog_store', postData, function(html){
                $('.comment-content .comment-form').hide();
                icon.parents('.comment-item').find(".comment-input").append(html);

                $('.comment-count').html(parseInt($('.comment-count').html())+1+' 条评论');       //回答的评论数+1
            });
        });

        //评论支持
        $('.like-icon').click(function () {
            var icon = $(this);
            var comment_id = $(this).data('comment-id');
            var like_count = icon.find('.like-count').html();
            $.get('/comment/support/'+comment_id, function (message) {
                if (message == 'support') {
                    like_count++;
                    icon.find('.like-count').html(like_count);
                    icon.find('i').css('color', '#555');
                    icon.find('.like-count').css('color', '#555');
                } else if (message == 'unsupport') {
                    like_count--;
                    icon.find('.like-count').html(like_count);
                    icon.find('i').css('color', '#999');
                    icon.find('.like-count').css('color', '#999');
                }
            });
        });

        //编辑评论
        $('.edit-icon').click(function () {
            var icon = $(this);
            icon.parents('.media').css('display', 'none');
            icon.parents('.comment-item').find('.edit-comment').show('slow');
        });
        //编辑评论输入框取消隐藏
        $('.edit-cancel').click(function () {
            $(this).parents('.comment-item').find('.edit-comment').hide();
            $(this).parents('.comment-item').find('.media').show('slow');
        });
        //提交评论更改
        $('.edit-comment-btn').click(function () {
            var icon = $(this);
            var comment_id = $(this).data('comment-id');
            var postData = icon.parents('.edit-comment').find("#edit-comment").serializeArray();
            $.post('/comment/edit/'+comment_id, postData, function(message){
                icon.parents('.comment-item').find('.edit-comment').hide();
                icon.parents('.comment-item').find('.media .media-body p').html(message);
                icon.parents('.comment-item').find('.media').show('slow');
            });
        });
        //删除评论
        $('.delete-icon').click(function () {
            var icon = $(this);
            var comment_id = $(this).data('comment-id');
            zeroModal.confirm("确定删除评论吗？", function() {
                $.ajax({
                    url : "{{url('/comment/destroy/[id]')}}".replace('[id]', comment_id),
                    data : {
                        _token: '{{csrf_token()}}',
                    },
                    dataType : "json",
                    type : "POST",
                    success : function (res) {
                        if(res.code == 710){
                            layer.msg(res.message, {
                                icon: 6,//提示的样式
                                time: 2000, //2秒关闭（如果不配置，默认是3秒）//设置后不需要自己写定时关闭了，单位是毫秒
                            });
                            icon.parents('.media').css('display', 'none');
                        } else if (res.code == 711) {
                            zeroModal.error(res.message);
                        }

                    },
                    error : function () {
                        zeroModal.error('系统错误！');
                    }
                });
            });
        });
    });
</script>