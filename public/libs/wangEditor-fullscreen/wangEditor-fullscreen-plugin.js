/**
 * 
 */
window.wangEditor.fullscreen = {
	// editor create之后调用
	init: function(editorSelector){
		$(editorSelector + " .w-e-toolbar").append('<div class="w-e-menu"><a class="_wangEditor_btn_fullscreen" href="###" onclick="window.wangEditor.fullscreen.toggleFullscreen(\'' + editorSelector + '\')"><i class="iconfont icon-full-screen"></i></a></div>');
	},
	toggleFullscreen: function(editorSelector){
		$(editorSelector).toggleClass('fullscreen-editor');
		if($(editorSelector + ' ._wangEditor_btn_fullscreen').html() == '<i class="iconfont icon-full-screen"></i>'){
			$(editorSelector + ' ._wangEditor_btn_fullscreen').html('<i class="iconfont icon-cancel-full-screen"></i>');
		}else{
			$(editorSelector + ' ._wangEditor_btn_fullscreen').html('<i class="iconfont icon-full-screen"></i>');
		}
	}
};