@extends('pc.layouts.app')
@section('title')
    {{ $user->username }} 个人信息 | @parent
@stop
@section('css')
    <link rel="stylesheet" href="{{ asset('css/user/default.css') }}">
    <link rel="stylesheet" href="{{ asset('libs/cityPicker/css/city-picker.css') }}">
    <link rel="stylesheet" href="{{ asset('libs/webuploader/webuploader.css') }}">
    <link rel="stylesheet" href="{{ asset('libs/cropper/dist/cropper.min.css') }}">
    <link rel="stylesheet" href="{{ asset('libs/bootstrap-select/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('libs/bootstrap-fileinput/css/fileinput.min.css') }}">
@stop

@section('content')
    <div class="container main-container">
        <div class="row">
            {{--左侧菜单--}}
            <div class="col-md-3">
                @include('pc.user.partials.setting-side-menu')
            </div>

            <div class="col-md-9">
                <div class="panel panel-default right-container">
                    <h4 class="title"><i class="iconfont icon-msnui-person-info"></i>个人信息</h4>
                    <form class="form-horizontal" role="form" method="post"  enctype="multipart/form-data" action="{{ url('user/auth/profile/per_detail') }}">
                        <input type="hidden" id="editor_token" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" id="avatar_image" name="avatar_image" value="{{ \Illuminate\Support\Facades\Cookie::get('avatar_image') }} " />
                        <p class="small-title">基本信息</p>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">头像</label>
                            <div class="col-sm-8">
                                <img class="avatar-46 avatar-img" id="user_avatar_image" name="user_avatar_image" src="{{ \App\Helpers\Helpers::get_user_avatar(Auth()->user()->id,'medium') }}" alt="头像">
                                <div class="wu-example">
                                    <div class="change-avatar">
                                        <button type="button" class="btn avatar-btn" data-toggle="modal" data-target="#avatar_modal">修改头像</button>
                                        <p class="text-muted">图像大小不超过 2 MB<br>只支持 JPG / JPEG / PNG / GIF 等格式的图片</p>
                                        <div class="modal fade" id="avatar_modal" tabindex="-1" role="dialog" aria-labelledby="avatar_model_label">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title" id="avatar_model_label">修改头像</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-xs-6 text-center">
                                                                <div id="avatar_origin" class="avatar-container">
                                                                    <img class="avatar-origin" src="{{ \App\Helpers\Helpers::get_user_avatar(Auth()->user()->id,'origin') }}">
                                                                </div>
                                                                <!--用来存放文件信息-->
                                                                <div id="uploader">
                                                                    <div id="fileList" class="uploader-list"></div>
                                                                    <div id="filePicker" class="picker-container">选择图片</div>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-6 text-center mt-5">
                                                                <div class="preview-container img-preview">
                                                                </div>
                                                                <button id="avatar_btn" type="button" class="btn btn-primary avatar_btn">保存头像</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">用户名</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control text-extra" id="username" name="username" value="{{ $user->username }}" placeholder="请输入用户名">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">真实姓名</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control text-extra" id="realname" name="realname" value="{{ $user->realname }}" placeholder="请输入真实姓名">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">性别</label>
                            <div class="col-sm-8">
                                <select id="gender" name="gender"  class="form-control selectpicker">
                                    <option value="0" @if($user->gender == 0) selected @endif>男</option>
                                    <option value="1" @if($user->gender == 1) selected @endif>女</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">生日</label>
                            <div class="col-sm-8">
                                <input type="text" id="birthday" name="birthday" value="{{ $user->birthday }}" class="form-control text-extra required" style="width: 275px;" placeholder="请选择生日时间">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">现居地址</label>
                            <div class="col-sm-8">
                                <input type="text" id="city-picker" name="city-picker" class="form-control text-extra required city-bg" placeholder="省 / 市 " data-toggle="city-picker" data-level="city">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">个性域名</label>
                            <div class="col-sm-8">
                                <input type="text" id="personal_domain" name="personal_domain" value="{{ $user->personal_domain }}" class="form-control text-extra" placeholder="默认前缀 http://serenity.cn/user/">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">个人网站</label>
                            <div class="col-sm-8">
                                <input type="text" id="personal_website" name="personal_website" value="{{ $user->personal_website }}" class="form-control text-extra" placeholder="默认前缀 http://">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">个人简介</label>
                            <div class="col-sm-8">
                                <textarea id="description" name="description" class="form-control" placeholder="请简单介绍一下自己吧^_^" rows="3">{{ $user->description }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">微信二维码</label>
                            <div class="col-sm-6">
                                <input type="file" class="filestyle" accept="image/*" id="wechat_qrcode" name="wechat_qrcode" value="{{ $user->wechat_qrcode }}" autofocus>
                            </div>
                        </div>

                        <p class="small-title" style="margin-top: 30px;">职业信息</p>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">职业状态</label>
                            <div class="col-sm-8 career_status">
                                {{--<a href="javascript:void(0)" id="career_status">添加职业状态</a>--}}
                                <ul class="dowebok">
                                    {{--<p>请选择您当前的职业状态：</p>--}}
                                    <li><input type="radio" id="stu" name="career_status" value="0" data-labelauty="学生" @if($user->career_status == 0) checked @endif></li>
                                    <li><input type="radio" id="working" name="career_status" value="1" data-labelauty="在职"  @if($user->career_status == 1) checked @endif></li>
                                    <li><input type="radio" id="wait_employ" name="career_status" value="2" data-labelauty="待业" @if($user->career_status == 2) checked @endif></li>
                                </ul>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">职业方向</label>
                            <div class="col-sm-8">
                                <select id="career_direction" name="career_direction" class="form-control selectpicker">
                                    <option value="0">请选择职业方向</option>
                                    {!! $taxonomies !!}
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-8">
                                <button type="submit" class="btn btn-success btn-lg btn-save">保存</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('footer')
    <script src="{{ asset('libs/cityPicker/js/city-picker.data.js') }}"></script>
    <script src="{{ asset('libs/cityPicker/js/city-picker.js') }}"></script>
    <script src="{{ asset('libs/laydate/laydate.js') }}"></script>
    <script src="{{ asset('libs/webuploader/webuploader.js') }}"></script>
    <script src="{{ asset('libs/cropper/dist/cropper.min.js') }}"></script>
    <script src="{{ asset('libs/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('libs/bootstrap-select/js/i18n/defaults-zh_CN.js') }}"></script>
    {{--<script src="{{ asset('libs/bootstrap-fileinput/js/fileinput.min.js') }}"></script>
    <script src="{{ asset('libs/bootstrap-fileinput/js/fileinput_locale_zh.js') }}"></script>--}}
    <script src="{{ asset('libs/bootstrap-filestyle/bootstrap-filestyle.min.js') }}"></script>
    <script>
        /*页面加载滚动到顶端*/
        window.onload = function () {
            window.scrollTo(0,-1);      //把内容滚动到指定的坐标
            document.body.scrollTop = 0;
        }
    </script>
    <script>    //时间插件
        laydate.render({
            elem: '#birthday' //指定元素
        });
    </script>
    <script>    //下拉菜单
        $(function () {
            $('.selectpicker').selectpicker();
        });
    </script>
    <script>    //上传图片
        $('#wechat_qrcode').filestyle({
            'placeholder' : '请选择微信二维码图片',
            'buttonText' : '选择图片'
        });
        $('.bootstrap-filestyle input').addClass('filestyle-input');    //设置input样式
        $('.bootstrap-filestyle label').addClass('btn-select-qrcode');  //设置选择图片按钮样式
    </script>
    <script>
        $(function(){
            $('.dowebok input').labelauty();
        });
    </script>
    <script>    //地区插件
        $('#city-picker').citypicker({
            province: '{{ $user->province }}',
            city: '{{ $user->city }}',
        });

        $(function () {
            $('.city-picker-dropdown').css('left', '15px');  //调整地区下拉菜单位置
            $('.city-picker-span').addClass('city-bg');
        });

    </script>
    {{--<script>
        $('#career_status').on('click', function () {
            layer.open({
                type: 2,
                title: '请添加职业状态',
                resize: false,
                move: false,
                shadeClose: true,
                shade: 0.8,
                area: ['380px', '30%'],
                content: '{{ url('user/'.Auth::user()->username.'/career_status') }}/' //iframe的url
            });
        });
    </script>--}}
    <script>
        $(function () {
            var filePicker = $('#filePicker');              //选择图片按钮
            var avatar_origin = $('#avatar_origin img');    //显示选择的图片
            var avatar_modal = $('#avatar_modal');          //弹出的上传文件的模态框

            //模态框对用户可见时触发
            avatar_modal.on('shown.bs.modal', function(e) {
                //console.log(avatar_origin.attr("src"));
                //console.log(avatar_origin.attr("src").split("?")[0]);
                var user_avatar = avatar_origin.attr("src").split("?")[0] + "?" + Math.random(); //获取图片路径在？中分割，获取？前面部分，并在？后面加上0.0到1.0随机数
                //console.log(user_avatar);
                avatar_origin.attr("src",user_avatar);
                avatar_origin.cropper({                     //初始化该图片剪裁
                    aspectRatio: 1/1,
                    modal: false,
                    movable: false,
                    zoomable: false,
                    preview: ".img-preview",
                    done: function(data) {
                        console.log(data);
                    }
                });

                var uploader = WebUploader.create({        //初始化Web Uploader

                    // 选完文件后，是否自动上传。
                    auto: true,

                    // swf文件路径
                    swf: "{{ asset('libs/webuploader/Uploader.swf') }}",

                    // 文件接收服务端。
                    server: "{{ route('user.auth.profile.avatar') }}",

                    formData: {
                        _token:'{{ csrf_token() }}'
                    },
                    method:'POST',
                    // 设置文件上传域的name
                    fileVal:'user_avatar',
                    /*指定选择文件的按钮容器，不指定则不创建按钮
                    内部根据当前运行是创建，可能是input元素，也可能是flash.*/
                    pick: '#filePicker',
                    //验证单个文件大小是否超出限制, 超出则不允许加入队列
                    fileSingleSizeLimit: 2 * 1024 * 1024,    // 2M
                    //去重， 根据文件名字、文件大小和最后修改时间来生成hash Key
                    duplicate: true,
                    // 只允许选择图片文件。
                    accept: {
                        title: 'Images',
                        extensions: 'gif,jpg,jpeg,png',
                        mimeTypes: 'image/*'
                    },
                    // 不压缩image, 默认如果是jpeg，文件上传前会压缩一把再上传！
                    resize: false
                });

                var webuploader_pick = $('.webuploader-pick');  //选择图片按钮类样式

                //当validate不通过时，会以派送错误事件的形式通知调用者
                uploader.on('error', function(msg) {
                    if (msg == 'F_EXCEED_SIZE') {
                        layer.msg("图片大小不能越过2MB");
                    } else if (msg == 'Q_TYPE_DENIED') {
                        layer.msg("只支持GIF,JPG,JPEG,PNG图片格式");
                    } else {
                        layer.msg(msg);
                    }
                });

                // 文件上传过程中创建进度条实时显示。
                uploader.on('uploadProgress', function(file, percentage) {
                    webuploader_pick.text(parseInt(percentage * 100) + '%');
                });

                //当文件上传成功时触发
                uploader.on('uploadSuccess', function(file, response) {
                    var user_avatar = avatar_origin.attr("src").split("?")[0] + "?" + Math.random();
                    avatar_origin.attr("src",user_avatar);
                    avatar_origin.cropper('destroy');           //试了下不销毁cropper选择图片上传后显示不出来
                    avatar_origin.cropper({
                        aspectRatio: 1/1,
                        modal: false,
                        movable: false,
                        zoomable: false,
                        preview: ".img-preview",
                        done: function(data) {
                            console.log(data);
                        }
                    });
                    $('#uploader').addClass('webuploader-element-invisible');

                    if (response.code == 601) {
                        console.log(response);
                        layer.msg(response.message);    //弹出头像上传成功信息
                    }
                });

                //当文件上传出错时触发
                uploader.on('uploadError', function(file, reason) {
                    layer.msg('上传出错,错误原因：' + reason);
                    webuploader_pick.text('选择图片');          //上传图片出错把按钮文字还原
                });
            });

            //当模态框完全对用户隐藏时触发
            avatar_modal.on('hidden.bs.modal', function(e) {
                $('#uploader').removeClass('webuploader-element-invisible');
                filePicker.removeClass('webuploader-container');
                filePicker.text('选择图片');
                avatar_origin.cropper('destroy');
            });

            //点击保存按钮才开始把头像也就是上传的图片进行裁剪
            $('#avatar_btn').click(function(){
                var cropper = avatar_origin.cropper('getData');         //从原始图片中返回的剪裁区域数据(x：剪裁区域左侧的偏移;y：剪裁区域距上部的偏移;width：剪裁区域的宽度;height：剪裁区域的高度;rotate：图片的旋转角度;)
                $.post("{{ route('user.auth.profile.avatar') }}",
                    {_token: '{{ csrf_token() }}', x: cropper.x, y: cropper.y, width: cropper.width, height: cropper.height}, function(res){
                    console.log(res);
                    if (res.code == 602) {
                        layer.msg(res.data);    //弹出修改头像成功信息

                        var user_avatar_image = $('#user_avatar_image');
                        //console.log(user_avatar_image.attr("src"));
                        user_avatar_image.attr("src", user_avatar_image.attr("src").split("?")[0] + "?" + Math.random());  //图片路径不变，因为重新上传头像图片是把原有的头像图片删除再添加新更改的头像图片
                        var avatar_middle = $('.avatar-46');
                        avatar_middle.attr("src", avatar_middle.attr("src").split("?")[0] + "?" + Math.random());   //更改导航右侧的头像图片

                        avatar_modal.modal('hide');     //隐藏模态框
                    }
                });
            });
        });
    </script>
@stop