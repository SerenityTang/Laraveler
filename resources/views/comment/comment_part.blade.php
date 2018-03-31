{{--此回答的评论列表--}}
<div class="comment-part-bor">
    @foreach($comments as $comment)
        @include('comment.comment_part_item')
    @endforeach
</div>
{{--根据登录与否和是否为本回答的回答者给出提示添加评论--}}
@if(Auth::check())
    @if(\App\Helpers\Helpers::get_answer($entity_id)->user_id != Auth::user()->id)
        <span><a href="javascript:void(0);" id="new-comment" class="new-comment"><i class="iconfont icon-iconfontpinglun"></i>您有话要说？请添加您的评论吧......</a></span>
    @elseif(\App\Helpers\Helpers::get_answer($entity_id)->comment_count < 1)
        <span class="comment-tip">您的回答暂时还没有评论噢^_^</span>
    @endif
@else
    <span><a href="javascript:void(0);" id="new-comment" class="new-comment"><i class="iconfont icon-iconfontpinglun"></i>您有话要说？请添加您的评论吧......</a></span>
@endif
{{--评论输入框--}}
<div class="form-group comment-input">
    <form id="comment-content" method="post" action="{{ url('comment/answer_store') }}">
        <input type="hidden" id="editor_token" name="_token" value="{{ csrf_token() }}" />
        <input type="hidden" id="answer_id" name="answer_id" value="{{ $entity_id }}">
        <input type="hidden" id="entity_type" name="entity_type" value="{{ $entity_type }}">
        <input type="hidden" id="to_user" name="to_user" value="">
        <textarea id="comment-part-con" name="comment-part-con" class="form-control comment-part-con" rows="2"></textarea>
        <div class="comment-part-bottom">
            <button type="button" class="btn btn-cancel">取消</button>
            <button type="button" class="btn btn-reply" data-entity_id="{{ $entity_id }}" data-entity_type="{{ $entity_type }}">回复</button>
        </div>
    </form>
</div>

<script src="{{ asset('libs/layer/layer/layer.js') }}"></script>
<script>
    //回复评论框聚焦与否设置边框颜色样式
    $(function () {
        $('.media-comment textarea').focus(function () {
            $('.media-comment .comment-part-bottom').css('border-color', '#22d7bb');
        });
        $('.media-comment textarea').blur(function () {
            $('.media-comment .comment-part-bottom').css('border-color', '#ddd');
        });

        //点击回复按钮，切换显示回复评论框
        $('.media-comment .comment-part-bor span a.reply-icon').click(function () {
            var user_id = $(this).data('user-id');
            var user_name = $(this).data('user-name');
            $('#to_user').val(user_id);
            @if(!\Auth::check())
                window.location.href = '{{ url('/login') }}';
            @else
                if ($('.media-comment .comment-input').is(":hidden")){
                    $('.media-comment textarea').val('');
                    $('.media-comment textarea').attr('placeholder', '@ '+user_name);
                    $('.media-comment .comment-input').toggle('slow');
                } else {
                    $('.media-comment textarea').val('');
                    $('.media-comment textarea').attr('placeholder', '@ '+user_name);
                }
            @endif
        });

        //添加新评论
        $('.new-comment').click(function () {
            $('#to_user').val(null);    //添加新评论非回复评论用户，所以把此字段改为null
            var icon = $(this);
            @if(!\Auth::check())
                window.location.href = '{{ url('/login') }}';
            @else
                if (icon.parents('.media').find('.media-comment .comment-input').is(":hidden")){
                    icon.parents('.media').find('.media-comment textarea').val('');
                    icon.parents('.media').find('.media-comment textarea').attr('placeholder', '请输入您的评论......');
                    icon.parents('.media').find('.media-comment .comment-input').toggle('slow');
                } else {
                    icon.parents('.media').find('.media-comment textarea').val('');
                    icon.parents('.media').find('.media-comment textarea').attr('placeholder', '请输入您的评论......');
                }
            @endif
        });

        //点击取消按钮把回复评论框隐藏
        $('.media-comment .comment-part-bottom .btn-cancel').click(function () {
            $(this).parents('.media-comment .comment-input').hide('slow');
        });
    });
</script>
<script>
    //评论回复
    $(function () {
        $('.btn-reply').click(function () {
            var icon = $(this);
            var entity_id = $(this).data('entity_id');
            var entity_type = $(this).data('entity_type');
            var postData = $("#comment-content").serializeArray();
            $.post('/comment/answer_store', postData, function(html){
                $('.media-comment .comment-input').hide();
                $("#media-comment .comment-part-bor").append(html);

                icon.parents('.media').find('.media-footer span.comment-count').html(parseInt(icon.parents('.media').find('.media-footer .comment-count').html())+1);       //回答的评论数+1
            });
        });
    });
</script>