<?php get_header(); ?>
<div class="container">
	<div class="panel panel-default" id="list-grid">
			<div class="panel-heading row">
				<div class="col-sm-1 col">
					作者
				</div>
				<div class="col-sm-9 col">
					主题
				</div>
				<div class="col-sm-1 col col-info">
					浏览
				</div>
				<div class="col-sm-1 col col-info">
					回复
				</div>
			</div>
			<div class="list-group">
			<?php if(!$wp_query) global $wp_query;
			$args = array(
			    'meta_key' => 'commentTime',
			    'orderby'   => 'meta_value_num',  // WordPress 2.8以上版本
			    'order' => DESC
			);
			$args = array_merge( $args, $wp_query->query );
			query_posts($args); ?>
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<a href="<?php the_permalink(); ?>" class="list-group-item row">
					<div class="col-sm-1 col">
						<?php echo get_avatar( get_the_author_email(), 100 ); ?>
					</div>
					<div class="col-sm-9 col">
						<h4 class="list-group-item-heading">
							<?php the_title(); ?>
						</h4>
						<div class="list-group-item-text">
							<?php echo mb_strimwidth(strip_tags(apply_filters('the_content', $post->post_content)), 0, 110, "…"); ?></br></br><?php echo time_since($post->post_date);?>&nbsp;&nbsp;
<small>By&nbsp;<?php echo the_author_meta( 'display_name' ); ?></small>
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