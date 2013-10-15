<?php get_header(); ?>
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
<div class="container" id="single">
	<ol class="breadcrumb">
		<li><a href="<?php bloginfo('url'); ?>">首页</a></li>
		<li><?php the_category(', '); ?></li>
		<li class="active"><?php the_title(); ?></li>
		<?php edit_post_link('管理员编辑','<li class="active">','</li> '); ?>
		<span class="pull-right"><i class="glyphicon glyphicon-eye-open"></i> <?php echo getPostViews(get_the_ID()); ?>次浏览</span>
	</ol>
	<div class="row">
		<div class="col-sm-12">
			<div id="content">
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<?php setPostViews(get_the_ID()); ?>
				<h1 id="the_title"><?php the_title(); ?></h1>
				<div id="entry">
					<?php the_content(); ?>
					<div class="clearfix"></div>
					<?php the_tags('<div id="tags"><span><i class="glyphicon glyphicon-tag"></i> 话题标签</span>','','</div>'); ?>
					<div class="clearfix"></div>
					<?php   
						if (get_the_author_meta('ID')==wt_get_user_id()){   
							echo '<br><a href="'.$url.'/bianji?edit='.get_the_ID().'">编辑话题</a> ';
						}   
					?>
				</div>
		<!--作者信息开始 -->	<div class="panel-group" id="accordion">
  <div class="panel panel-default" style="margin-bottom:30px">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
         <i class="glyphicon glyphicon-zoom-in"></i> 作者 <?php echo the_author_meta( 'display_name' ); ?> 的信息 >>
        </a>
      </h4>
    </div>
    <div id="collapseOne" class="panel-collapse collapse">
      <div class="panel-body">
  <div class="media" id="author-info">
          <a class="pull-left" href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php echo get_avatar( get_the_author_email(), 100 ); ?></a>
          <div class="media-body">
            <h4 class="media-heading">
              <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php echo the_author_meta( 'display_name' ); ?></a>
            </h4>
            <p>
              <?php echo the_author_meta('description'); ?>
            </p>
            <p>
              <?php if (is_user_logged_in()) { ?>
              <?php 
                $guanzhu_array = explode(',',$guanzhu_old);
                if ( in_array(get_the_author_meta('ID'),$guanzhu_array) ) {
              ?>
              <a href="javascript:guanzhux(<?php echo get_the_author_meta('ID'); ?>);" rel="nofollow" class="btn btn-default" id="guanzhu" role="button">
                <i class="glyphicon glyphicon-remove-sign"></i> 取消关注
              </a>
              <?php } else { ?>
              <a href="javascript:guanzhu(<?php echo get_the_author_meta('ID'); ?>);" rel="nofollow" class="btn btn-success" id="guanzhu" role="button">
                <i class="glyphicon glyphicon-plus-sign"></i> 关注
              </a>
              <?php } ?>
              <?php } else { ?>
              <a href="<?php bloginfo('url'); ?>/sign-in?from=<?php the_ID(); ?>" rel="nofollow" class="btn btn-success" id="guanzhu" role="button">
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
        <?php endwhile; endif; ?>
        <div class="row" id="related">
          <div class="col-sm-6 col-md-5 col-lg-5">
            <div class="panel panel-default">
              <!-- Default panel contents -->
              <div class="panel-heading">
                <i class="glyphicon glyphicon-time"></i> 作者近期话题
              </div>
              <!-- List group -->
              <div class="list-group">
                <?php
                  global $post;
                  $post_author = get_the_author_meta( 'user_login' );
                  $args = array(
                        'author_name' => $post_author,
                        'post__not_in' => array($post->ID),
                        'showposts' => 5,               // 显示相关文章数量
                        'orderby' => date,          // 按时间排序
                        'caller_get_posts' => 1
                    );
                  query_posts($args);
                
                  if (have_posts()) {
                    while (have_posts()) {
                the_post(); update_post_caches($posts); ?>
                <a class="list-group-item" href="<?php the_permalink(); ?>">
                  <?php the_title(); ?>
                </a>
                <?php } } else { 
                  echo '<a class="list-group-item" href="javascript:;">* TA还没有发布其他话题！</a>';
                } wp_reset_query(); ?>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-md-5 col-lg-5">
            <div class="panel panel-default">
              <!-- Default panel contents -->
              <div class="panel-heading">
                <i class="glyphicon glyphicon-fire"></i> 本站热门话题
              </div>
              <!-- List group -->
              <div class="list-group">
                <?php query_posts('orderby=comment_count&showposts=5'); ?>
                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <a class="list-group-item" href="<?php the_permalink(); ?>">
                  <?php the_title(); ?>
                </a>
                <?php endwhile; endif; wp_reset_query(); ?>
              </div>
            </div>
          </div>
          <div class="col-md-2 col-lg-2 visible-md visible-lg">
            <a href="javascript:;" id="sad">
              <p><i class="glyphicon glyphicon-magnet"></i></p>
              <h4>电子科技大学</h4>
              <p>来了</p>
              <p>就是来找妹纸的</p>
            </a>
          </div>
        </div>
      </div>
      </div>
    </div>
  </div>
</div> <!--作者信息结束 -->
			<div id="comment">
				<div id="comment-body">
					<?php comments_template(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>