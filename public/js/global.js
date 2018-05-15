/**
 * Created by dengzhihao on 2018/2/24.
 * 全局公用JS
 */

/**
 * 编辑器图片图片文件方式上传
 * @param file
 * @param editor
 * @param image_type
 */
function upload_editor_image(file, editorId, image_type, id){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    data = new FormData();
    data.append("file", file);
    data.append("type_id", id);
    $.ajax({
        data: data,             //发送到服务器的数据
        type: "POST",           //请求方式
        url: "/image/upload?image_type="+image_type,   //请求url
        dataType: 'text',       //返回的数据类型
        cache: false,           //默认true，设置为false不缓存页面
        contentType: false,
        processData: false,
        success: function(url) {
            console.log(url)
            if(url == 'error'){
                layer.msg('图片上传失败！！！', {
                    icon: 5,
                });
                return false;
            }
            $('#'+editorId).summernote('insertImage', url, function ($image) {
                $image.css('width', $image.width() / 4);
                $image.addClass('img-responsive');
            });
        },
        error:function() {
            layer.msg('图片上传失败，请压缩图片大小再进行上传！！！', {
                icon: 5,
            });
        }
    });
}