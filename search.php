<?php get_header(); ?>
<div class="container">
	<ol class="breadcrumb">
		<li><a href="<?php bloginfo('url'); ?>">首页</a></li>
		<li class="active">搜索：<?php echo $s; ?></li>
	</ol>
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
					评论
				</div>
			</div>
			<div class="list-group">
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<?php
				$title = get_the_title();
				$content = mb_strimwidth(strip_tags(apply_filters('the_content', $post->post_content)), 0, 180, "…");
				$keys = explode(" ",$s);
				$title = preg_replace('/('.implode('|', $keys) .')/iu','<span style="color:#f36119;">\0</span>',$title);
				$content = preg_replace('/('.implode('|', $keys) .')/iu','<span style="color:#f36119;">\0</span>',$content);
				$pos = strpos($content,$keys[0],1);
				if($pos&&$pos>80){//如果搜索关键字位置有了返回值，且大于70
					$content = substr($content,pos-70,600);//substr会出现乱码，
				}else{
					$content = substr($content,0,1000);
				}
			?>
				<a href="<?php the_permalink(); ?>" class="list-group-item row">
					<div class="col-sm-1 col">
						<?php echo get_avatar( get_the_author_email(), 100 ); ?>
					</div>
					<div class="col-sm-9 col">
						<h4 class="list-group-item-heading">
							<?php echo $title; ?>
						</h4>
						<div class="list-group-item-text">
							<?php echo $content; ?>
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