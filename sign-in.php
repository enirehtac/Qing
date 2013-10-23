<?php
/*
Template Name: 注册/登入
*/
?>
<?php
$from = get_permalink($_POST['from']);
$froma = get_author_posts_url($_POST['froma']);
if( !empty($_POST['mm_reg1']) ) {
  $error1 = '';
  $sanitized_user_login = sanitize_user( $_POST['user_login'] );

  $user_login = $_POST['user_login'];
	$user_pass = $_POST['user_pass'];
	if ( !user_pass_ok( $user_login, $user_pass )) {
		  $error1 .= '用户名与密码不符，请重新填写。';
	}
  
  if($error1 == '') {
    if (!is_user_logged_in()) {
      $user = get_userdatabylogin($sanitized_user_login);
      $user_id = $user->ID;
  
      // 自动登录
      wp_set_current_user($user_id, $user_login);
      wp_set_auth_cookie($user_id);
      do_action('wp_login', $user_login);
    }
  }
};
if( !empty($_POST['mm_reg']) ) {
  $error = '';
  $sanitized_user_login = sanitize_user( $_POST['user_login'] );
  $user_email = apply_filters( 'user_registration_email', $_POST['user_email'] );

  // Check the username
  if ( $sanitized_user_login == '' ) {
    $error .= '<strong>错误</strong>：请输入用户名。<br />';
  } elseif ( ! validate_username( $user_login ) ) {
    $error .= '<strong>错误</strong>：此用户名包含无效字符，请输入有效的用户名<br />。';
    $sanitized_user_login = '';
  } elseif ( username_exists( $sanitized_user_login ) ) {
    $error .= '<strong>错误</strong>：该用户名已被注册，请再选择一个。<br />';
  }

  // Check the e-mail address
  if ( $user_email == '' ) {
    $error .= '<strong>错误</strong>：请填写电子邮件地址。<br />';
  } elseif ( ! is_email( $user_email ) ) {
    $error .= '<strong>错误</strong>：电子邮件地址不正确。！<br />';
    $user_email = '';
  } elseif ( email_exists( $user_email ) ) {
    $error .= '<strong>错误</strong>：该电子邮件地址已经被注册，请换一个。<br />';
  }
    
  // Check the password
  if(strlen($_POST['user_pass']) < 6)
    $error .= '<strong>错误</strong>：密码长度至少6位!<br />';
  elseif($_POST['user_pass'] != $_POST['user_pass2'])
    $error .= '<strong>错误</strong>：两次输入的密码必须一致!<br />';
      
    if($error == '') {
    $user_id = wp_create_user( $sanitized_user_login, $_POST['user_pass'], $user_email );
    // My subject替换为邮件标题，content替换为邮件内容
    wp_mail($user_email,"My subject","content");
    
    if ( ! $user_id ) {
      $error .= sprintf( '<strong>错误</strong>：无法完成您的注册请求... 请联系<a href=\"mailto:%s\">管理员</a>！<br />', get_option( 'admin_email' ) );
    }
    else if (!is_user_logged_in()) {
      $user = get_userdatabylogin($sanitized_user_login);
      $user_id = $user->ID;
  
      // 自动登录
      wp_set_current_user($user_id, $user_login);
      wp_set_auth_cookie($user_id);
      do_action('wp_login', $user_login);
    }
  }
}
?>
<?php get_header(); ?>
<div id="sign-in" class="container">
	<div class="row">
	<?php if (!is_user_logged_in()) { ?>
	<div class="col-sm-4"></div>
	<div class="col-sm-4">
		<ul class="nav nav-tabs" id="myTab">
			<li class="active"><a href="#login" data-toggle="tab">登入</a></li>
			<li><a href="#register" data-toggle="tab">注册</a></li>
		</ul>
		<div class="tab-content">
			<div id="login" class="tab-pane fade in active">
				<form name="loginform" id="loginform" action="<?php echo home_url(add_query_arg(array(),$wp->request)); ?>" method="post" role="form">
				<div class="form-group">
					<p class="form-control-static">如果您已经有本站账号，请在此登入。</p>
				</div>
				<div class="form-group">
					<input class="form-control" autocomplete="off" type="text" name="user_login" value="" placeholder="用户名或邮箱" />
				</div>
				<div class="form-group">
					<input class="form-control" autocomplete="off" type="text" name="user_pass" value="" placeholder="密码" onfocus="this.type='password'" />
				</div>
				<div class="form-group">
					<label for="rememberme"><input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="90">记住我</label> &nbsp; 
					<a href="<?php bloginfo('url'); ?>/wp-login.php?action=lostpassword">忘记密码</a>
				</div>
				<?php if(!empty($error1)) { 
				echo '<p style="background:#e74c3c; color:#fff; padding:3px 10px; margin:10px 0;">'.$error1.'</p>';
				} ?>
				<p>
					<input type="hidden" name="mm_reg1" value="ok" />
					<?php if($_GET['from']) { ?>
					<input type="hidden" name="from" value="<?php echo $_GET['from']; ?>" />
					<?php } ?>
					<?php if($_GET['froma']) { ?>
					<input type="hidden" name="froma" value="<?php echo $_GET['froma']; ?>" />
					<?php } ?>
					<input type="submit" class="btn btn-danger" value="登入" />
				</p>
				</form>
			</div>
			<div id="register" class="tab-pane fade">
				<form name="registerform" id="registerform" action="<?php echo home_url(add_query_arg(array(),$wp->request)); ?>" method="post" role="form">
				<div class="form-group">
					<p class="form-control-static">注册本站账号，体验更多会员特权！</p>
				</div>
				<div class="form-group">
					<input class="form-control" autocomplete="off" type="text"  name="user_login"  value="" placeholder="用户名" />
					<span class="help-block">登陆可设置与用户名不同的昵称</span>
				</div>
				<div class="form-group">
					<input class="form-control" autocomplete="off" type="text"  name="user_email"  value="" placeholder="邮箱" />
					<span class="help-block">用于找回密码和显示Gravatar头像</span>
				</div>
				<div class="form-group">
					<input class="form-control" autocomplete="off" type="text" name="user_pass"  value="" placeholder="密码" onfocus="this.type='password'" />
				</div>
				<div class="form-group">
					<input class="form-control" autocomplete="off" type="text" name="user_pass2"  value="" placeholder="确认密码" onfocus="this.type='password'" />
				</div>
				<?php if(!empty($error)) { 
				echo '<p style="background:#e74c3c; color:#fff; padding:3px 10px; margin:10px 0;">'.$error.'</p>';
				} ?>
				<p>
					<input type="hidden" name="mm_reg" value="ok" />
					<input type="hidden" name="from" value="<?php echo $_GET['from']; ?>" />
					<input type="submit" class="btn btn-danger" value="注册" />
				</p>
				</form>
			</div>
		</div>
	</div>
	<?php if($_GET['reg']) { ?>
	<script>
		$(function () {
			$('#myTab a:last').tab('show')
		});
	</script>
	<?php } ?>
	<div class="col-sm-4"></div>
	<?php } else { ?>
	<script language=javascript>window.location.href="<?php if($_POST['from']) echo $from; elseif($_POST['froma']) echo $froma; else bloginfo('url'); ?>"</script> 
	<div id="tgne" class="col-sm-12">
		<p><i class="glyphicon glyphicon-tint"></i></p>
		<p>正在登入，如果您的浏览器不支持跳转，<a href="<?php if($_POST['from']) echo $from; elseif($_POST['froma']) echo $froma; else bloginfo('url'); ?>">请点这里</a>。</p>
	</div>
	<?php } ?>
	</div>
</div><div style="position:absolute;bottom:2px;width:100%">
<?php get_footer(); ?></div>
