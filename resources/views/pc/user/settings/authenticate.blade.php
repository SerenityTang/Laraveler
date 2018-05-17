@extends('pc.layouts.app')
@section('title')
    {{ $user->username }} 实名认证 | @parent
@stop
@section('css')
    <link href="{{ asset('css/user/default.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('libs/bootstrap-fileinput/css/fileinput.min.css') }}">
@stop
@section('style')
    <style type="text/css">
        .icon {
            width: 1em; height: 1em;
            vertical-align: -0.15em;
            fill: currentColor;
            overflow: hidden;
        }
    </style>
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
                    <h4 class="title"><i class="iconfont icon-shimingrenzheng" style="top: 1px;"></i>实名认证</h4>
                    @if(Auth::user()->approval_status == 0)
                        <form class="form-horizontal" role="form" enctype="multipart/form-data" method="post" action="{{ url('user/profile/post_authenticate') }}">
                            <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}" />
                            <input type="hidden" id="redirect_uri" name="redirect_uri" value="{{ request()->url() }}">

                            <ul class="explain">
                                <li>实名认证申请资料提交后，我们将在<span style="font-weight: bolder;color: #52acd9;"> 3 个工作日内</span>处理并反馈；</li>
                                <li>手持身份证(正面)照片要求持证人<span style="font-weight: bolder;color: #52acd9;">五官可见、证件全部信息清晰无遮挡</span>；</li>
                                <li>图像大小不超过<span style="font-weight: bolder;color: #52acd9;"> 2 MB</span>，只支持<span style="font-weight: bolder;color: #52acd9;"> JPG / JPEG / PNG / GIF </span>等格式的图片。</li>
                            </ul>
                            <div class="form-group">
                                <label for="" class="col-md-2 control-label">真实姓名</label>
                                <div class="col-md-7">
                                    <input type="text" class="form-control text-extra" id="real_name" name="real_name" placeholder="请输入真实姓名">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-md-2 control-label">身份证号码</label>
                                <div class="col-md-7">
                                    <input type="text" class="form-control text-extra" id="id_card" name="id_card" placeholder="请输入身份证号码">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-md-2 control-label">身份证(正面)</label>
                                <div class="col-md-6 idcard-bor">
                                    <img id="thumb_front" src="{{ asset('imgs/idcard-front.jpg') }}" alt="身份证(正面)" width="306" height="193">
                                    <div class="add" onclick="$('#front').click();">
                                        <p class="add-btn">+</p>
                                        <p class="add-text">添加照片</p>
                                    </div>
                                    <input type="file" id="front" name="front" style="display: none;">
                                </div>
                            </div>

                            <div id="preview" class="form-group">
                                <label for="" class="col-md-2 control-label">身份证(反面)</label>
                                <div class="col-md-7 idcard-bor">
                                    <img id="thumb_verso" src="{{ asset('imgs/idcard-verso.jpg') }}" alt="身份证(反面)" width="306" height="193">
                                    <div class="add" onclick="$('#verso').click();">
                                        <p class="add-btn">+</p>
                                        <p class="add-text">添加照片</p>
                                    </div>
                                    <input type="file" id="verso" name="verso" style="display: none;">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-md-2 control-label">手持身份证(正面)</label>
                                <div class="col-md-7 idcard-bor">
                                    <img id="thumb_hand" src="{{ asset('imgs/idcard-hand.jpg') }}" alt="手持身份证(正面)" width="306" height="193">
                                    <div class="add" onclick="$('#hand').click();">
                                        <p class="add-btn">+</p>
                                        <p class="add-text">添加照片</p>
                                    </div>
                                    <input type="file" id="hand" name="hand" style="display: none;">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-md-8">
                                    <button type="submit" class="btn btn-success btn-lg btn-save">提交</button>
                                </div>
                            </div>
                        </form>
                    @elseif(Auth::user()->approval_status == 1)
                        <div class="col-md-12 wait-check">
                            <p class="check-icon"><i class="iconfont icon-daishenhe"></i></p>
                            <p>{{ explode('，', $user_auth->feeback)[0] }}</p>
                            <p>{{ explode('，', $user_auth->feeback)[1] . '，' . explode('，', $user_auth->feeback)[2]}}</p>
                        </div>
                    @else
                        <div class="col-md-12 wait-check">
                            <svg class="icon auth-success" aria-hidden="true">
                                <use xlink:href="#icon-renzhengchenggong"></use>
                            </svg>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('footer')
    <script src="{{ asset('libs/bootstrap-filestyle/bootstrap-filestyle.min.js') }}"></script>
    <script src="{{ asset('css/iconfont/iconfont.js') }}"></script>
    <script>
        //正面生成预览图
        $("#verso").change(function() {
            var $file = $(this);
            var fileObj = $file[0];
            var windowURL = window.URL || window.webkitURL;
            var dataURL;
            var $img = $("#thumb_verso");

            if(fileObj && fileObj.files && fileObj.files[0]){
                dataURL = windowURL.createObjectURL(fileObj.files[0]);
                $img.attr('src',dataURL);
            }else{
                dataURL = $file.val();
                var imgObj = document.getElementById("thumb_verso");
                // 两个坑:
                // 1、在设置filter属性时，元素必须已经存在在DOM树中，动态创建的Node，也需要在设置属性前加入到DOM中，先设置属性在加入，无效；
                // 2、src属性需要像下面的方式添加，上面的两种方式添加，无效；
                imgObj.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale)";
                imgObj.filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = dataURL;
            }
        });

        //反面生成预览图
        $("#front").change(function() {
            var $file = $(this);
            var fileObj = $file[0];
            var windowURL = window.URL || window.webkitURL;
            var dataURL;
            var $img = $("#thumb_front");

            if(fileObj && fileObj.files && fileObj.files[0]){
                dataURL = windowURL.createObjectURL(fileObj.files[0]);
                $img.attr('src',dataURL);
            }else{
                dataURL = $file.val();
                var imgObj = document.getElementById("thumb_front");
                // 两个坑:
                // 1、在设置filter属性时，元素必须已经存在在DOM树中，动态创建的Node，也需要在设置属性前加入到DOM中，先设置属性在加入，无效；
                // 2、src属性需要像下面的方式添加，上面的两种方式添加，无效；
                imgObj.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale)";
                imgObj.filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = dataURL;
            }
        });

        //手持生成预览图
        $("#hand").change(function() {
            var $file = $(this);
            var fileObj = $file[0];
            var windowURL = window.URL || window.webkitURL;
            var dataURL;
            var $img = $("#thumb_hand");

            if(fileObj && fileObj.files && fileObj.files[0]){
                dataURL = windowURL.createObjectURL(fileObj.files[0]);
                $img.attr('src',dataURL);
            }else{
                dataURL = $file.val();
                var imgObj = document.getElementById("thumb_hand");
                // 两个坑:
                // 1、在设置filter属性时，元素必须已经存在在DOM树中，动态创建的Node，也需要在设置属性前加入到DOM中，先设置属性在加入，无效；
                // 2、src属性需要像下面的方式添加，上面的两种方式添加，无效；
                imgObj.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale)";
                imgObj.filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = dataURL;
            }
        });
    </script>
@stop