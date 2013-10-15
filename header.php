<?php
global $readtime;
$readtime = microtime();
if (is_user_logged_in()) {
if( isset($_POST['post_form']) && $_POST['post_form'] == 'send')
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
        'post_title' => $post_title,
        'post_content' => $post_content,
        'post_author' => ''.$user_id.'',
        'post_status' => 'publish',
        //'tags_input' => $post_tags
        //'post_category' => array($category)
    );


    // 将文章插入数据库
    if($error=='') {
	    $status = wp_insert_post( $tougao );
	    if ($status != 0) 
	    { 
	        add_post_meta($status, 'ip_value', yourIp(),TRUE);
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
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php if (is_single() || is_page() || is_archive() || is_search()) { ?><?php wp_title('',true); ?> - <?php } bloginfo('name'); ?><?php if ( is_home() ){ ?> - <?php bloginfo('description'); ?><?php } ?><?php if ( is_paged() ){ ?> - <?php printf( __('Page %1$s of %2$s', ''), intval( get_query_var('paged')), $wp_query->max_num_pages); ?><?php } ?></title>
<?php 
if (is_home()){ 
	$description     = get_option('mao10_description');
	$keywords = get_option('mao10_keywords');
} elseif (is_single() || is_page()){    
	$description1 =  $post->post_excerpt ;
	$description2 = mb_strimwidth(strip_tags(apply_filters('the_content', $post->post_content)), 0, 200, "…");
	$description = $description1 ? $description1 : $description2;
	$keywords = get_post_meta($post->ID, "keywords_value", true);        
} elseif(is_category()){
	$description     = category_description();
	$current_category = single_cat_title("", false);
	$keywords =  $current_category;
}
?>
<meta name="keywords" content="<?php echo $keywords ?>" />
<meta name="description" content="<?php echo $description ?>" />
 <link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/favicon.png" />
<link href="<?php bloginfo('template_directory'); ?>/css/bootstrap.css" rel="stylesheet">
<link href="<?php bloginfo('template_directory'); ?>/ueditor/themes/default/_css/umeditor.css" type="text/css" rel="stylesheet">
<link href="<?php bloginfo('template_directory'); ?>/build/css/messenger.css" rel="stylesheet">
<link href="<?php bloginfo('template_directory'); ?>/build/css/messenger-theme-air.css" rel="stylesheet">
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
	<script src="<?php bloginfo('template_directory'); ?>/js/html5shiv.js"></script>
	<script src="<?php bloginfo('template_directory'); ?>/js/respond.min.js"></script>
<![endif]-->
<?php wp_deregister_script('jquery');//wp_enqueue_script('jquery'); ?>
<script src="<?php bloginfo('template_directory'); ?>/js/jquery.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/bootstrap.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/build/js/messenger.min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/cat.js"></script>
<?php wp_head(); ?>
</head>
<body>
<nav id="topbar" class="navbar navbar-default" role="navigation">
	<div class="container">
	<!-- Brand and toggle get grouped for better mobile display -->
	<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
			<span class="sr-only">
				Toggle navigation
			</span>
			<span class="icon-bar">
			</span>
			<span class="icon-bar">
			</span>
			<span class="icon-bar">
			</span>
		</button>
		<a class="navbar-brand" href="<?php bloginfo('url'); ?>">
			<i class="glyphicon glyphicon-magnet"></i> 电子科技大学
		</a>
	</div>
	<!-- Collect the nav links, forms, and other content for toggling -->
	<div class="collapse navbar-collapse navbar-ex1-collapse">
		<ul class="nav navbar-nav">
			<li<?php if(is_home()) echo ' class="active"'; ?>>
				<a href="<?php bloginfo('url'); ?>">
					<i class="glyphicon glyphicon-list"></i> 最新
				</a>
			</li>
			<li<?php if(is_page(9)) echo ' class="active"'; ?>>
				<a href="<?php bloginfo('url'); ?>/hot/">
					<i class="glyphicon glyphicon-fire"></i> 最热
				</a>
			</li>
			<li<?php if(is_category('3')) echo ' class="active"'; ?>>
				<a href="<?php bloginfo('url'); ?>/cat/star/">
					<i class="glyphicon glyphicon-bookmark"></i> 精华
				</a>
			</li>


<li class="dropdown">
				<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
					<i class="glyphicon glyphicon-plus"></i> 节点导航
					
				</a>
				<ul class="dropdown-menu">
					<li>
						<a href="/cat/uestc-forum">
							 轻论坛
						</a>
					</li>
<li>
						<a href="/cat/incampus">
							 我在成电
						</a>
					</li>
<li>
						<a href="/cat/share">
							 分享有趣
						</a>
					</li>

</ul>


			<li>
				<a href="javascript:;" class="dropdown-toggle" data-toggle="modal" data-target="#myModal">
					<i class="glyphicon glyphicon-search"></i> 搜索
				</a>
			</li>
		</ul>
		<ul class="nav navbar-nav navbar-right">
			<?php if (is_user_logged_in()) { ?>
			<li<?php if(is_page(7)) echo ' class="active"'; ?>>
				<a href="<?php echo get_author_posts_url(wt_get_user_id()); ?>">
					<?php echo get_avatar( wt_get_user_id(), 20 ); ?><?php echo get_the_author_meta('display_name',wt_get_user_id()); ?>
				</a>
			</li>
			<li class="dropdown">
				<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
					<i class="glyphicon glyphicon-user"></i> 用户中心
					<b class="caret"></b>
				</a>
				<ul class="dropdown-menu">
					<li>
						<a href="<?php echo get_author_posts_url(wt_get_user_id()); ?>">
							<i class="glyphicon glyphicon-home"></i> 我的首页
						</a>
					</li>
					<!--li>
						<a href="<?php bloginfo('url'); ?>/shoucang/">
							 <i class="glyphicon glyphicon-map-marker"></i> 我的收藏
						</a>
					</li-->
					<li>
						<a href="<?php bloginfo('url'); ?>/guanzhu/">
							<i class="glyphicon glyphicon-plus-sign"></i> 我的关注
						</a>
					</li>
					<li class="divider">
					</li>
					<li>
						<a href="<?php bloginfo('url'); ?>/shezhi/">
							<i class="glyphicon glyphicon-cog"></i> 资料设置
						</a>
					</li>
					<li class="divider">
					</li>
					<li>
						<a href="<?php echo wp_logout_url( home_url() ); ?>">
							<i class="glyphicon glyphicon-log-out"></i> 退出登陆
						</a>
					</li>
				</ul>
			</li>
			<?php } else { ?>
			<li>
				<a href="<?php bloginfo('url'); ?>/sign-in/"><i class="glyphicon glyphicon-log-in"></i> 登入</a>
			</li>
			<li>
				<a href="<?php bloginfo('url'); ?>/sign-in?reg=ok"><i class="glyphicon glyphicon-leaf"></i> 注册</a>
			</li>
			<?php } ?>
		</ul>
	</div>
	<!-- /.navbar-collapse -->
	</div>
</nav>
<?php echo $error; ?>