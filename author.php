<?php get_header(); ?>
<?php
	if(isset($_GET['author_name'])) :
	$curauth = get_userdatabylogin($author_name);
	else :
	$curauth = get_userdata(intval($author));
	endif;
	$val = $curauth->id;
?>
<?php
$guanzhu_old = get_the_author_meta('guanzhu',wt_get_user_id());
if($_GET['uid']) {
	$guanzhu = $_GET['uid'].','.$guanzhu_old;
	array_unique($guanzhu);
	update_user_meta(wt_get_user_id(), 'guanzhu', $guanzhu);
	wp_update_user( array (
		'ID' => wt_get_user_id(), 
		'guanzhu' => $guanzhu,
	) ) ;
};
if($_GET['uidx']) {
	$guanzhu_array_x = explode(',',$guanzhu_old);
	$str = $_GET['uidx'];
	$len = count( $guanzhu_array_x );
	for( $i=0;$i<$len; $i++) {
		if( $guanzhu_array_x[$i] == $str ) {
			unset( $guanzhu_array_x[$i] );
		}
	};
	$guanzhu_new = implode(',',$guanzhu_array_x);
	array_unique($guanzhu_new);
	update_user_meta(wt_get_user_id(), 'guanzhu', $guanzhu_new);
	wp_update_user( array (
		'ID' => wt_get_user_id(), 
		'guanzhu' => $guanzhu_new,
	) ) ;
} ?>
<div class="container" id="author">
			<div class="media" id="author-info">
					<a class="pull-left" href="<?php echo get_author_posts_url($val); ?>"><?php echo get_avatar( $val, 100 ); ?></a>
					<div class="media-body">
						<h4 class="media-heading">
							<a href="<?php echo get_author_posts_url($val); ?>"><?php echo the_author_meta( 'display_name',$val ); ?></a>
			<small># UESTC 第 <?php echo the_author_meta( 'ID',$val ); ?> 号会员，加入于 <?php echo the_author_meta( 'user_registered',$val ); ?></small>		</h4>
						<p>
							<?php echo the_author_meta('description',$val); ?>
						</p>
						<p>
							<?php if (is_user_logged_in()) { ?>
							<?php 
								$guanzhu_array = explode(',',$guanzhu_old);
								if ( in_array($val,$guanzhu_array) ) {
							?>
							<a href="javascript:guanzhux(<?php echo $val; ?>);" rel="nofollow" class="btn btn-default" id="guanzhu" role="button">
								<i class="glyphicon glyphicon-remove-sign"></i> 取消关注
							</a>
							<?php } else { ?>
							<a href="javascript:guanzhu(<?php echo $val; ?>);" rel="nofollow" class="btn btn-success" id="guanzhu" role="button">
								<i class="glyphicon glyphicon-plus-sign"></i> 关注
							</a>
							<?php } ?>
							<?php } else { ?>
							<a href="<?php bloginfo('url'); ?>/sign-in?froma=<?php echo $val; ?>" rel="nofollow" class="btn btn-success" id="guanzhu" role="button">
								<i class="glyphicon glyphicon-plus-sign"></i> 关注
							</a>
							<?php } ?>
						</p>
						<script>
							function guanzhu(uid) {
								$.ajax({
									url: '<?php echo home_url(add_query_arg(array(),$wp->request)); ?>?uid=' + uid,
									type: 'GET',
									dataType: 'html',
									timeout: 9000,
									error: function() {
										alert('提交失败！');
									},
									success: function(html) {
										$("#guanzhu").attr('href','javascript:guanzhux('+uid+');');
										$("#guanzhu").html('<i class="glyphicon glyphicon-remove-sign"></i> 取消关注');
										$("#guanzhu").removeClass('btn-success');
										$("#guanzhu").addClass('btn-default');
										//alert('提交成功！');
										//window.location.reload(); 
									}
								});
								//return false;
							};
							function guanzhux(uid) {
								$.ajax({
									url: '<?php echo home_url(add_query_arg(array(),$wp->request)); ?>?uidx=' + uid,
									type: 'GET',
									dataType: 'html',
									timeout: 9000,
									error: function() {
										alert('提交失败！');
									},
									success: function(html) {
										$("#guanzhu").attr('href','javascript:guanzhu('+uid+');');
										$("#guanzhu").html('<i class="glyphicon glyphicon-plus-sign"></i> 关注');
										$("#guanzhu").removeClass('btn-default');
										$("#guanzhu").addClass('btn-success');
										//alert('提交成功！');
										//window.location.reload(); 
									}
								});
								//return false;
							};
						</script>
					</div>
				</div>
	<div class="panel panel-default" id="list-grid">
			<div class="panel-heading row">
				<div class="col-sm-10 col">
					主题
				</div>
				<div class="col-sm-1 col col-info">
					浏览
				</div>
				<div class="col-sm-1 col col-info">
					评论
				</div>
			</div>
			<div class="list-group">
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<a href="<?php the_permalink(); ?>" class="list-group-item row">
					<div class="col-sm-10 col">
						<h4 class="list-group-item-heading">
							<?php the_title(); ?>
						</h4>
						<div class="list-group-item-text">
							<?php echo mb_strimwidth(strip_tags(apply_filters('the_content', $post->post_content)), 0, 180, "…"); ?>
						</div>
					</div>
					<div class="col-sm-1 col">
						<div class="list-group-item-info views" data-toggle="tooltip" data-placement="bottom" data-original-title="浏览">
							<?php echo getPostViews(get_the_ID()); ?>
						</div>
					</div>
					<div class="col-sm-1 col">
						<div class="list-group-item-info comments" data-toggle="tooltip" data-placement="bottom" data-original-title="回复">
							<?php comments_number('0', '1', '%' );?>
						</div>
					</div>
					<div class="clearfix"></div>
				</a>
			<?php endwhile; ?><?php endif; ?>
			<script>$('.list-group-item-info').tooltip()</script>
			</div>
	</div>
	<ul id="pager" class="pagination">
		<?php par_pagenavi(9); ?>
	</ul>
	<?php get_template_part('fabu'); ?>
</div>
<?php get_footer(); ?>