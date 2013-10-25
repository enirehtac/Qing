<?php
// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
		<p class="nocomments">This post is password protected. Enter the password to view comments.</p>
	<?php
		return;
	}
?>

<!-- You can start editing here. -->

<?php if ( have_comments() ) : ?>
	
	<ul class="nav nav-tabs">
		<li class="active"><a href="javascript:;"><i class="glyphicon glyphicon-tasks"></i> 共有<?php comments_number('0', '1', '%' );?>条回复</a></li>
		<li><a href="#respond"><i class="glyphicon glyphicon-comment"></i> 回复主题</a></li>
	</ul>

	<div class="list-group">

	<?php wp_list_comments('type=comment&callback=cleanr_theme_comment'); ?>
	
	</div>

	<!--div id="pager" class="pagination">
		<?php
			// 如果用户在后台选择要显示评论分页
			if (get_option('page_comments')) {
				// 获取评论分页的 HTML
				$comment_pages = paginate_comments_links('echo=0');
				// 如果评论分页的 HTML 不为空, 显示导航式分页
				if ($comment_pages) { ?>
					<?php echo $comment_pages; ?>
				<?php }
			} ?>
	</div-->
 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ('open' == $post->comment_status) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<div class="panel panel-default">
			<div class="panel-body">
				评论已经关闭.
			</div>
		</div>

	<?php endif; ?>
<?php endif; ?>

<?php if ('open' == $post->comment_status) : ?>

<script type="text/javascript" charset="utf-8" src="<?php bloginfo('template_directory'); ?>/ueditor/umeditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php bloginfo('template_directory'); ?>/ueditor/editor_api.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/ueditor/lang/zh-cn/zh-cn.js"></script>

<ul class="nav nav-tabs">
	<li class="active" id="respond"><a href="javascript:;"><i class="glyphicon glyphicon-comment"></i> 回复主题</a></li>
	<li><a href="#comment"><i class="glyphicon glyphicon-tasks"></i> 共有<?php comments_number('0', '1', '%' );?>条回复</a></li>
</ul>

<div class="col-sm-12">
<?php if (!is_user_logged_in() ) : ?>

<div id="tgne">
	<p><i class="glyphicon glyphicon-tint"></i></p>
	<p>你必须<a href="<?php bloginfo('url'); ?>/sign-in?from=<?php the_ID(); ?>">登入</a>或<a href="<?php bloginfo('url'); ?>/sign-in?reg=ok&from=<?php the_ID(); ?>">注册</a>后。才能回复主题.</p>
</div>
<?php else : ?>

<form role="form" action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="comment-form" class="row">

<div class="form-group">
	<script type="text/plain" id="myEditor" style="width:100%;height:240px;"></script>
</div>

<input id="editor" class="post_input" value="" name="comment" type="hidden" />

<div class="form-inline">

<button type="submit" class="btn btn-primary">回复</button>
<?php cancel_comment_reply_link('<span class="btn btn-default">取消回复</span>'); ?>
</div>
<?php comment_id_fields(); ?>

<?php do_action('comment_form', $post->ID); ?>

</form>

<script type="text/javascript">
    var ue = UM.getEditor('myEditor');
    ue.addListener('blur',function(){
        $('#editor').val(UM.getEditor('myEditor').getContent());
    });
    ue.addListener('focus',function(){
        
    });
</script>

<?php endif; // If registration required and not logged in ?>

</div>

<?php endif; // if you delete this the sky will fall on your head ?>