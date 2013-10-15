<?php
/*
Template Name: 编辑
*/
?>
<?php
$postID = $_GET['edit'];
if($postID) {
$authorID = get_post_field('post_author', $postID);
if($authorID==wt_get_user_id()) {
update_user_meta(wt_get_user_id(), 'bianji', $postID);
wp_update_user( array (
	'ID' => wt_get_user_id(),
	'bianji' => $postID
) ) ;
}
}
if(is_user_logged_in()) {
if( isset($_POST['post_form2']) && $_POST['post_form2'] == 'send')
{
    global $wpdb;
    $last_post = $wpdb->get_var("SELECT post_date FROM $wpdb->posts WHERE post_type = 'post' ORDER BY post_date DESC LIMIT 1");

    // 博客当前最新文章发布时间与要投稿的文章至少间隔120秒。
    // 可自行修改时间间隔，修改下面代码中的120即可
    // 相比Cookie来验证两次投稿的时间差，读数据库的方式更加安全
    $error = '';
    if ( current_time('timestamp') - strtotime($last_post) < 120 )
    {
        $error = '<script type="text/javascript">$(document).ready(function(){function postform0() {$.globalMessenger().post({message: "操作过于频繁，请稍后尝试！",type: "error",showCloseButton: true});};setTimout(postform0(),100);}); </script>';
    }
        
    // 表单变量初始化
    $post_title =  $_POST['post_title'];
    $post_content =  $_POST['post_content'];
    //$post_tags =  $_POST['post_tags'];
    
    // 表单项数据验证
    if ( empty($post_title) || mb_strlen($post_title) > 100 )
    {
        $error = '<script type="text/javascript">$(document).ready(function(){function postform0() {$.globalMessenger().post({message: "标题必须填写，且长度不得超过100字！",type: "error",showCloseButton: true});};setTimout(postform0(),100);}); </script>';
    }
    
    if ( empty($post_content) || mb_strlen($post_content) > 3000 || mb_strlen($post_content) < 10)
    {
        $error = '<script type="text/javascript">$(document).ready(function(){function postform0() {$.globalMessenger().post({message: "内容必须填写，且长度不得超过3000字，不得少于10字！",type: "error",showCloseButton: true});};setTimout(postform0(),100);}); </script>';
    }
    
    $user_id = wt_get_user_id();
  
    $tougao = array(
        'ID' => get_the_author_meta('bianji',wt_get_user_id()),
        'post_title' => $post_title,
        'post_content' => $post_content,
        'post_author' => ''.$user_id.'',
        'post_status' => 'publish',
        //'tags_input' => $post_tags
        //'post_category' => array($category)
    );


    // 将文章插入数据库
    if($error=='') {
	    $status = wp_update_post( $tougao );
	    if ($status != 0) 
	    { 
	        //add_post_meta($status, 'wzc_value', $meta_value,TRUE);
	        $postlink = get_permalink($status);
	        Header("Location:$postlink");
	    }
	    else
	    {
	        $error = '<script type="text/javascript">$(document).ready(function(){function postform0() {$.globalMessenger().post({message: "发布失败，请稍后尝试！",type: "error",showCloseButton: true});};setTimout(postform0(),100);}); </script>';
	    }
    }
}
}
?>
<?php get_header(); ?>
<div class="container" id="edit">
<?php if($postID) { ?>
<?php query_posts('p='.$postID.''); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<form action="<?php echo home_url(add_query_arg(array(),$wp->request)); ?>" method="post" role="form" id="fabu">
	<ul class="nav nav-tabs">
		<li class="active"><a href="javascript:;"><i class="glyphicon glyphicon-edit"></i> 编辑主题</a></li>
	</ul>
<script type="text/javascript" charset="utf-8" src="<?php bloginfo('template_directory'); ?>/ueditor/umeditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php bloginfo('template_directory'); ?>/ueditor/editor_api.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/ueditor/lang/zh-cn/zh-cn.js"></script>
	<div class="form-group">
		<input type="text" class="form-control post_input" name="post_title" id="post-title" placeholder="请输入标题" value="<?php the_title(); ?>" <?php if (!is_user_logged_in()) echo 'disabled'; ?> >
	</div>
	<div class="form-group">
		<script type="text/plain" id="myEditor" style="width:100%;height:240px;"><?php the_content(); ?></script>
	</div>
	<!--div class="form-group">
		<input type="text" class="form-control" name="post_tags" id="post-tags" placeholder="请输入标签，使用英文半角逗号隔开" <?php if (!is_user_logged_in()) echo 'disabled'; ?> >
	</div-->
	<input id="editor" class="post_input" value="" name="post_content" type="hidden" />
	<input value="send" name="post_form2" type="hidden" />
	<div class="form-group">
		<p class="form-control-static error2"><?php echo $error; ?></p>
    </div>
    <div class="form-group" id="post-submit-hover">
		<input type="submit" id="post-submit" class="btn btn-primary post-submit" value="提交" <?php if (!is_user_logged_in()) echo 'disabled'; ?> />
	</div>
	<?php if (!is_user_logged_in()) { ?>
	<div id="sign-in-fabu">
		请在<a href="<?php bloginfo('url'); ?>/sign-in/">登陆</a>或<a href="<?php bloginfo('url'); ?>/sign-in?reg=ok">注册</a>后编辑主题！
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
<?php endwhile; endif; ?>
</div>
<?php } else { ?>
	<div id="tgne">
		<p><i class="glyphicon glyphicon-tint"></i></p>
		<p>请指定主题进行编辑</p>
	</div>
<?php } ?>
<?php get_footer(); ?>