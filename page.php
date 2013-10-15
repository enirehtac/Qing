<?php get_header(); ?>
<div class="container">
	<div class="panel panel-default" id="list-grid">
<div style="padding-left:30px;">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<?php the_content(); ?>	<?php endwhile; ?><?php endif; ?>
</div>
	</div>
	
</div>
<?php get_footer(); ?>