<?php
/*
Template Name: 资料设置
*/
if (is_user_logged_in()) {
				require_once( ABSPATH . WPINC . '/registration.php');
				if(!empty($_POST["action"])) {
					$user_id = wt_get_user_id(); 
					$display_name = $_POST["display_name"]; 
					$email = $_POST["email"]; 
					$youbian = $_POST["youbian"]; 
					$description = $_POST["description"];
					$touxiang = $_POST["touxiang"]; 
					$dianhua = $_POST["dianhua"];
					$mobile = $_POST["mobile"];
					$user_pass = $_POST["user_pass"];
					$user_pass2 = $_POST["user_pass2"];
					$error = '';
					if($display_name == '') {
						$error .= '<span class="tips tips2">你没有填写用户名！</span>';
					}
					if($email == '') {
						$error .= '<span class="tips tips2">你没有填写邮箱！</span>';
					}
					if(!empty($user_pass)) {
						if(strlen($_POST['user_pass']) < 6) {
							$error .= '<span class="tips tips2">密码长度必须大于等于6位！</span>';
						} elseif($_POST['user_pass'] != $_POST['user_pass2']) {
							$error .= '<span class="tips tips2">两次输入的密码必须一致!</span>';
						} else {
							update_user_meta($user_id, 'user_pass', $user_pass);
							wp_update_user( array ('ID' => $user_id, 'user_pass' => $user_pass) ) ;
						}
					}
					if($error == '') {
						update_user_meta($user_id, 'display_name', $display_name);
						update_user_meta($user_id, 'user_email', $email);
						update_user_meta($user_id, 'youbian', $youbian);
						update_user_meta($user_id, 'description', $description);
						update_user_meta($user_id, 'dianhua', $dianhua);
						update_user_meta($user_id, 'touxiang', $touxiang);
						update_user_meta($user_id, 'mobile', $mobile);
						wp_update_user( array ('ID' => $user_id, 'display_name' => $display_name, 'user_email' => $email, 'youbian' => $youbian, 'description' => $description, 'dianhua' => $dianhua, 'touxiang' => $touxiang, 'mobile' => $mobile) ) ;
						$error1 .= '<span class="tips tips1">更新资料成功！</span>';
					}
				}
}
?>
<?php get_header(); ?>
<div class="container">
	<div id="account">
		<?php if (is_user_logged_in()) { ?>
		<form action="<?php bloginfo('url'); ?>/shezhi/" method="post" role="form" class="form-horizontal">
				
				<?php if($error) { ?>
				<div class="form-group row">
					<p class="form-control-static error2 col-sm-12"><?php echo $error; ?></p>
			    </div>
			    <?php } ?>
			    
			    <?php if($error1) { ?>
				<div class="form-group row">
					<p class="form-control-static error2 col-sm-12"><?php echo $error1; ?></p>
			    </div>
			    <?php } ?>
			
				<div class="form-group row">
					<label class="col-sm-2 control-label"><h4><i class="glyphicon glyphicon-user"></i> 基本资料</h4></label>
				</div>
				<div class="acc-form-head"></div>
				
				<div class="form-group row">
					<label class="col-sm-2 control-label">用户名</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" value="<?php the_author_meta('user_login',wt_get_user_id()); ?>" disabled>
					</div>
				</div>
				
				<div class="form-group row">
					<label class="col-sm-2 control-label">昵称[必填]</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" value="<?php the_author_meta('display_name',wt_get_user_id()); ?>" name="display_name">
					</div>
				</div>
				
				<div class="form-group row">
					<label class="col-sm-2 control-label">你的邮箱[必填]</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" value="<?php the_author_meta('user_email',wt_get_user_id()); ?>" name="email">
					</div>
				</div>
				
				<div class="form-group row">
					<label class="col-sm-2 control-label">签名</label>
					<div class="col-sm-6">
						<textarea name="description" class="form-control" rows="5"><?php the_author_meta('description',wt_get_user_id()); ?></textarea>
					</div>
				</div>
				
				<div class="form-group row">
					<label class="col-sm-2 control-label"><h4><i class="glyphicon glyphicon-lock"></i> 修改密码</h4></label>
				</div>
				<div class="acc-form-head"></div>
				
				<div class="form-group row">
					<label class="col-sm-2 control-label">新密码</label>
					<div class="col-sm-4">
						<input type="password" class="form-control" value="" name="user_pass">
					</div>
				</div>
				
				<div class="form-group row">
					<label class="col-sm-2 control-label">确认新密码</label>
					<div class="col-sm-4">
						<input type="password" class="form-control" value="" name="user_pass2">
					</div>
				</div>
				
				<input type="hidden" name="action" value="update">
				
				<div class="form-group row">
					<label class="col-sm-2 control-label">&nbsp;</label>
					<div class="col-sm-4">
						<input type="submit" class="btn btn-primary" value="更新个人资料">
					</div>
				</div>
				
		</form>
		<?php } else { ?>
		<div class="row">
			<div class="col-sm-12">
				<div id="tgne">
					<p><i class="glyphicon glyphicon-tint"></i></p>
					<p>您需要<a href="<?php bloginfo('url'); ?>/sign-in/">登陆</a>或<a href="<?php bloginfo('url'); ?>/sign-in?reg=ok">注册</a>后才能访问此页面</p>
				</div>
			</div>
		</div>
		<?php } ?>
	</div>
</div>
<?php get_footer(); ?>