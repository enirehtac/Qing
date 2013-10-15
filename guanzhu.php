<?php
/*
Template Name: 关注
*/
?>
<?php get_header(); ?>
<div class="container">
	<?php 
		$guanzhu = get_the_author_meta('guanzhu',wt_get_user_id());
		if($guanzhu) {
	?>
	<div class="panel panel-default" id="list-grid">
		<div class="panel-heading row">
			<div class="col-sm-12 col">
				<i class="glyphicon glyphicon-plus-sign"></i> 我的关注
			</div>
		</div>
		<?php
			$guanzhu_array = explode(',',$guanzhu);
			array_pop($guanzhu_array);
			foreach($guanzhu_array as $val){
		?>
		<div class="list-group">
			<a href="<?php echo get_author_posts_url($val); ?>" class="list-group-item row">
				<div class="col-sm-1 col">
					<?php echo get_avatar( $val, 100 ); ?>
				</div>
				<div class="col-sm-11 col">
						<h4 class="list-group-item-heading">
							<?php echo the_author_meta('display_name',$val); ?>
						</h4>
						<div class="list-group-item-text">
							<?php echo the_author_meta('description',$val); ?>
						</div>
				</div>
			</a>
		</div>
		<?php } ?>
	</div>
	<?php } else { ?>
	<div id="tgne" class="col-sm-12">
		<p><i class="glyphicon glyphicon-tint"></i></p>
		<p>您目前没有关注任何人</p>
	</div>
	<?php } ?>
</div>
<?php get_footer(); ?>