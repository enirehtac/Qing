<form action="<?php echo home_url(add_query_arg(array(),$wp->request)); ?>" method="post" role="form" id="fabu">
	<ul class="nav nav-tabs">
		<li class="active"><a href="javascript:;"><i class="glyphicon glyphicon-edit"></i> 新建话题</a></li>
	</ul>
<script type="text/javascript" charset="utf-8" src="<?php bloginfo('template_directory'); ?>/ueditor/umeditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php bloginfo('template_directory'); ?>/ueditor/editor_api.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/ueditor/lang/zh-cn/zh-cn.js"></script>
	<div class="form-group">
		<input type="text" class="form-control post_input" name="post_title" id="post-title" placeholder="请输入标题" <?php if (!is_user_logged_in()) echo 'disabled'; ?> >
	</div>
	<div class="form-group">
		<script type="text/plain" id="myEditor" style="width:100%;height:240px;"></script>
	</div>
	<!--div class="form-group">
		<input type="text" class="form-control" name="post_tags" id="post-tags" placeholder="请输入标签，使用英文半角逗号隔开" <?php if (!is_user_logged_in()) echo 'disabled'; ?> >
	</div-->
	<input id="editor" class="post_input" value="" name="post_content" type="hidden" />
	<input value="send" name="post_form" type="hidden" />
	<div class="form-group">
		<p class="form-control-static error2"><?php echo $error; ?></p>
    </div>
    <div class="form-group" id="post-submit-hover">
		<input type="submit" id="post-submit" class="btn btn-default post-submit" value="提交" <?php if (!is_user_logged_in()) echo 'disabled'; ?> />
	</div>
	<?php if (!is_user_logged_in()) { ?>
	<div id="sign-in-fabu">
		请在<a href="<?php bloginfo('url'); ?>/sign-in/">登入</a>或<a href="<?php bloginfo('url'); ?>/sign-in?reg=ok">注册</a>后发布话题！
	</div>
	<?php } ?>
</form>
<?php if (is_user_logged_in()) { ?>
<script type="text/javascript">
    var ue = UM.getEditor('myEditor');
    ue.addListener('blur',function(){
        $('#editor').val(UM.getEditor('myEditor').getContent());
    });
    ue.addListener('focus',function(){
        
    });
    jQuery("#post-submit-hover").hover(function(){   	
		$('#editor').val(UM.getEditor('myEditor').getContent());
		var post_title = $("#post-title").val();
		var post_content = UM.getEditor('myEditor').hasContents();
		if(post_title && post_content) {
			$('#post-submit').removeClass('btn-default');
			$('#post-submit').addClass('btn-primary');
			$('#post-submit').attr('disabled',false);
			$('#post-submit').val('提交');
		} else {
			$('#post-submit').removeClass('btn-primary');
			$('#post-submit').addClass('btn-default');
			$('#post-submit').attr('disabled',true);
			$('#post-submit').val('必须填写标题及内容');
		}
	});
	jQuery(".post_input").focus(function(){ 
		$('#post-submit').removeClass('btn-default');
		$('#post-submit').addClass('btn-primary');
		$('#post-submit').attr('disabled',false);
		$('#post-submit').val('提交');
	});
</script>
<?php } else { ?>
	<script type="text/javascript">
		$(document).ready(function(){
	        UM.getEditor('myEditor').setDisabled('fullscreen');
	    });
    </script>
<?php } ?>