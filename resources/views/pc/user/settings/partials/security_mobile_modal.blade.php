<div class="modal fade" id="verifyMobileModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog mobile-modify">
        <div class="modal-content">
            <div class="modal-header mobile-modify-header">
                <button type="button" class="close mobile-modify-close" data-dismiss="modal" aria-hidden="true">×
                </button>
                <h4 class="modal-title mobile-modify-title" id="myModalLabel">
                    手机更换验证
                </h4>
            </div>
            <div class="modal-body mobile-modify-body">
                <div class="row">
                    <form class="form-horizontal" role="form" method="post"
                          enctype="multipart/form-data" {{--action="{{ url('/user/verify_mobile') }}"--}}>
                        <p class="mobile-icon">
                            <i class="iconfont icon-yanzhengmaicon"></i>
                        </p>
                        <div class="form-group old-mobile">
                            <label for="" class="col-sm-3 control-label">原手机号码</label>
                            <div class="col-sm-7 extra">
                                <input type="text" id="old-mobile" name="old-mobile" class="form-control text-extra"
                                       placeholder="请输入原手机号码">
                            </div>
                        </div>
                        <em class="old-mobile-em"></em>
                    </form>
                </div>
            </div>
            <div class="modal-footer mobile-modify-footer">
                <button type="button" class="btn mobile-verify-submit-btn">
                    验证
                </button>
                <button type="button" class="btn mobile-cancel-btn">
                    取消
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<div class="modal fade" id="mobileModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog mobile-modify">
        <div class="modal-content">
            <div class="modal-header mobile-modify-header">
                <button type="button" class="close mobile-modify-close" data-dismiss="modal" aria-hidden="true">×
                </button>
                <h4 class="modal-title mobile-modify-title" id="myModalLabel">
                    绑定新手机号码
                </h4>
            </div>
            <div class="modal-body mobile-modify-body">
                <div class="row">
                    <form class="form-horizontal" role="form" method="post"
                          enctype="multipart/form-data" {{--action="{{ url('/user/mobile_bind') }}"--}}>
                        <p class="mobile-icon">
                            <i class="iconfont icon-shoujibangding"></i>
                        </p>
                        <div class="form-group new-mobile">
                            <label for="" class="col-sm-3 control-label">新手机号码</label>
                            <div class="col-sm-7 extra">
                                <input type="text" id="new-mobile" name="new-mobile" class="form-control text-extra"
                                       placeholder="请输入新手机号码">
                            </div>
                        </div>
                        <p class="new-mobile-em"></p>
                        <div class="form-group verify-modal">
                            <label for="" class="col-sm-3 control-label">验证码</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control text-extra" id="verify_code" name="verify_code"
                                       maxlength="6" placeholder="请输入验证码">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-lg get-mvcode">获取验证码</button>
                            </div>
                        </div>
                        <em class="new-verify-em"></em>
                    </form>
                </div>
            </div>
            <div class="modal-footer mobile-modify-footer">
                <button type="button" class="btn mobile-submit-btn">
                    绑定
                </button>
                <button type="button" class="btn mobile-cancel-btn">
                    取消
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>