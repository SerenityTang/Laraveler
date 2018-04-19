<div class="modal fade" id="verifyEmailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog email-modify">
        <div class="modal-content">
            <div class="modal-header email-modify-header">
                <button type="button" class="close email-modify-close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title email-modify-title" id="myModalLabel">
                    邮箱更换验证
                </h4>
            </div>
            <div class="modal-body email-modify-body">
                <div class="row">
                    <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data" action="{{ url('/feedback') }}">
                        <p class="email-icon">
                            <i class="iconfont icon-yanzhengma"></i>
                        </p>
                        <div class="form-group new-email">
                            <label for="" class="col-sm-3 control-label">原邮箱地址</label>
                            <div class="col-sm-7 extra">
                                <input type="text" id="old-email" name="old-email" class="form-control text-extra" placeholder="请输入原邮箱地址">
                            </div>
                        </div>
                        <em class="old-email-em"></em>
                    </form>
                </div>
            </div>
            <div class="modal-footer email-modify-footer">
                <button type="button" class="btn email-verify-submit-btn">
                    验证
                </button>
                <button type="button" class="btn email-cancel-btn">
                    取消
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<div class="modal fade" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog email-modify">
        <div class="modal-content">
            <div class="modal-header email-modify-header">
                <button type="button" class="close email-modify-close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title email-modify-title" id="myModalLabel">
                    绑定新邮箱
                </h4>
            </div>
            <div class="modal-body email-modify-body">
                <div class="row">
                    <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data" action="{{ url('/feedback') }}">
                        <p class="email-icon">
                            <i class="iconfont icon-youjianyanzheng"></i>
                        </p>
                        <div class="form-group new-email">
                            <label for="" class="col-sm-3 control-label">新邮箱地址</label>
                            <div class="col-sm-7 extra">
                                <input type="text" id="new-email" name="new-email" class="form-control text-extra" placeholder="请输入更换邮箱地址">
                            </div>
                        </div>
                        <em class="new-email-em"></em>
                    </form>
                </div>
            </div>
            <div class="modal-footer email-modify-footer">
                <button type="button" class="btn email-submit-btn">
                    绑定
                </button>
                <button type="button" class="btn email-cancel-btn">
                    取消
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>