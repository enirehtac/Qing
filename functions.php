<?php

function yourIp() {
	$user_IP = ($_SERVER["HTTP_VIA"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER["REMOTE_ADDR"];
	$user_IP = ($user_IP) ? $user_IP : $_SERVER["REMOTE_ADDR"];
	return $user_IP;
}

//禁止登陆后台
if(is_admin()) {
  $current_user = wp_get_current_user();
  if($current_user->roles[0] == get_option('default_role')) {
    wp_safe_redirect( home_url() );
    exit();
  }
}

add_filter('user_contactmethods','hide_profile_fields',10,1);
function hide_profile_fields( $contactmethods ) {
unset($contactmethods['aim']);
unset($contactmethods['jabber']);
unset($contactmethods['yim']);
return $contactmethods;
}

function my_new_contactmethods( $contactmethods ) {
$contactmethods['guanzhu'] = '关注';
$contactmethods['bianji'] = '编辑';
return $contactmethods;
}
add_filter('user_contactmethods','my_new_contactmethods',10,1);

function comment_meta_add($post_ID)  {
    // 发布新文章或修改文章，更新/添加commentTime字段值
    global $wpdb;
    if(!wp_is_post_revision($post_ID)) {
        if( !update_post_meta($post_ID, 'commentTime', time()) ) {
            add_post_meta($post_ID, 'commentTime', time());
        }
    }
}


/*
function comment_meta_update($comment_ID)  {
    // 发布新评论更新commentTime字段值
    $comment = get_comment($comment_ID);
    $my_post_id = $comment->comment_post_ID;
    update_post_meta($my_post_id, 'commentTime', time());
}
*/

function comment_meta_delete($post_ID)  {
    // 删除文章同时删除commentTime字段
    global $wpdb;
    if(!wp_is_post_revision($post_ID)) {
        delete_post_meta($post_ID, 'commentTime');
    }
}
add_action('save_post', 'comment_meta_add');
add_action('delete_post', 'comment_meta_delete');
//add_action('comment_post', 'comment_meta_update');

add_filter( 'show_admin_bar', '__return_false' );

//分页工具
function par_pagenavi($range = 9){
  // $paged - number of the current page
  global $paged, $wp_query;
  // How much pages do we have?
  if ( !$max_page ) {
  $max_page = $wp_query->max_num_pages;
  }
  // We need the pagination only if there are more than 1 page
  if($max_page > 1){
  if(!$paged){
  $paged = 1;
  }
  echo '';
  // On the first page, don't put the First page link
  echo "<li><a href='" . get_pagenum_link(1) . "' class='extend' title='最前一页'>&laquo;</a></li>";
  // To the previous page
  echo "<li>";
  previous_posts_link('&lsaquo;');
  echo "</li>";
  // We need the sliding effect only if there are more pages than is the sliding range
  if($max_page > $range){
  // When closer to the beginning
  if($paged < $range){
  for($i = 1; $i <= ($range + 1); $i++){
  if($i==$paged) echo "<li class='active'><a>$i</a></li>";
else echo "<li><a href='" . get_pagenum_link($i) ."'>$i</a></li>";
  }
  }
  // When closer to the end
  elseif($paged >= ($max_page - ceil(($range/2)))){
  for($i = $max_page - $range; $i <= $max_page; $i++){
  if($i==$paged) echo "<li class='active'><a>$i</a></li>";
else echo "<li><a href='" . get_pagenum_link($i) ."'>$i</a></li>";
  }
  }
  // Somewhere in the middle
  elseif($paged >= $range && $paged < ($max_page - ceil(($range/2)))){
  for($i = ($paged - ceil($range/2)); $i <= ($paged + ceil(($range/2))); $i++){
  if($i==$paged) echo "<li class='active'><a>$i</a></li>";
else echo "<li><a href='" . get_pagenum_link($i) ."'>$i</a></li>";
  }
  }
  }
  // Less pages than the range, no sliding effect needed
  else{
  for($i = 1; $i <= $max_page; $i++){
  if($i==$paged) echo "<li class='active'><a>$i</a></li>";
else echo "<li><a href='" . get_pagenum_link($i) ."'>$i</a></li>";
  }
  }
  // Next page
  echo "<li>";
  next_posts_link('&rsaquo;');
  echo "</li>";
  // On the last page, don't put the Last page link
  echo "<li><a href='" . get_pagenum_link($max_page) . "' class='extend' title='最后一页'>&raquo;</a></li>";
  }
  }

//注册工具栏
if (function_exists('register_sidebar')){
   register_sidebar(array(
		'name' => '小工具',
		'id'   => 'side',
		'before_widget' => '<div class="panel panel-default">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="panel-heading">',
		'after_title'   => '</div>'
	));
}

//浏览统计
function getPostViews($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0";
    }
    return $count.'';
}
 
function setPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    update_post_meta($postID, 'commentTime', time());
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

//自定义域
$new_meta_boxes =
array(
	"ip" => array(
        "name" => "ip",
        "std" => "",
        "title" => "作者IP:"),
);

function new_meta_boxes() {
    global $post, $new_meta_boxes;

    foreach($new_meta_boxes as $meta_box) {
        $meta_box_value = get_post_meta($post->ID, $meta_box['name'].'_value', true);

        if($meta_box_value == "")
            $meta_box_value = $meta_box['std'];

        echo'<input type="hidden" name="'.$meta_box['name'].'_noncename" id="'.$meta_box['name'].'_noncename" value="'.wp_create_nonce( plugin_basename(__FILE__) ).'" />';

        // 自定义字段标题
        echo'<h4>'.$meta_box['title'].'</h4>';

        // 自定义字段输入框
        echo '<textarea cols="45" rows="1" name="'.$meta_box['name'].'_value">'.$meta_box_value.'</textarea><br />';
    }
}

function create_meta_box() {
    global $theme_name;

    if ( function_exists('add_meta_box') ) {
        add_meta_box( 'new-meta-boxes', '自定义字段', 'new_meta_boxes', 'post', 'normal', 'high' );
    }
}

function save_postdata( $post_id ) {
    global $post, $new_meta_boxes;

    foreach($new_meta_boxes as $meta_box) {
        if ( !wp_verify_nonce( $_POST[$meta_box['name'].'_noncename'], plugin_basename(__FILE__) ))  {
            return $post_id;
        }

        if ( 'page' == $_POST['post_type'] ) {
            if ( !current_user_can( 'edit_page', $post_id ))
                return $post_id;
        } 
        else {
            if ( !current_user_can( 'edit_post', $post_id ))
                return $post_id;
        }

        $data = $_POST[$meta_box['name'].'_value'];

        if(get_post_meta($post_id, $meta_box['name'].'_value') == "")
            add_post_meta($post_id, $meta_box['name'].'_value', $data, true);
        elseif($data != get_post_meta($post_id, $meta_box['name'].'_value', true))
            update_post_meta($post_id, $meta_box['name'].'_value', $data);
        elseif($data == "")
            delete_post_meta($post_id, $meta_box['name'].'_value', get_post_meta($post_id, $meta_box['name'].'_value', true));
    }
}

add_action('admin_menu', 'create_meta_box');
add_action('save_post', 'save_postdata');

function meta($meta) {
	return get_post_meta(get_the_ID(), $meta."_value", true);
}

function wt_get_user_id(){
    global $userdata;
    get_currentuserinfo();
    return $userdata->ID;
}


//评论回复邮件   
function comment_mail_notify($comment_id) {   
$comment = get_comment($comment_id);   
$parent_id = $comment->comment_parent ? $comment->comment_parent : '';   
$spam_confirmed = $comment->comment_approved;   
if (($parent_id != '') && ($spam_confirmed != 'spam')) {   
$wp_email = 'no-reply@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME']));//发件人e-mail地址   
$to = trim(get_comment($parent_id)->comment_author_email);   
$subject = '您在 [' . get_option("blogname") . '] 的回复有了回應';   
$message = '<table style="width: 99.8%;height:99.8% "><tbody><tr><td style="background:#FAFAFA url(http://labpic.qiniudn.com/a873524e5ac9465dc4d6fd6d133bc58d.png)"><div style="background-color:white;border-top:2px solid #12ADDB;box-shadow:0 1px 3px #AAAAAA;line-height:180%;padding:0 15px 12px;width:500px;margin:50px auto;color:#555555;font-family:Century Gothic,Trebuchet MS,Hiragino Sans GB,微软雅黑,Microsoft Yahei,Tahoma,Helvetica,Arial,SimSun,sans-serif;font-size:12px;"><h2 style="border-bottom:1px solid #DDD;font-size:14px;font-weight:normal;padding:13px 0 10px 8px;"><span style="color: #12ADDB;font-weight: bold;">&gt; </span>您在<a style="text-decoration:none;color: #12ADDB;" href="' . get_option('home') . '"> ' . get_option('blogname') . ' </a>轻论坛上得到回复啦！</h2><div style="padding:0 12px 0 12px;margin-top:18px"><p>' . trim(get_comment($parent_id)->comment_author) . ' 同学，您曾在帖子《' . get_the_title($comment->comment_post_ID) . '》上发表回复:</p><p style="background-color: #f5f5f5;border: 0px solid #DDD;padding: 10px 15px;margin:18px 0">' . nl2br(get_comment($parent_id)->comment_content) . '</p><p>' . trim($comment->comment_author) . '  给您的回复如下:</p><p style="background-color: #f5f5f5;border: 0px solid #DDD;padding: 10px 15px;margin:18px 0">' . nl2br($comment->comment_content) . '</p><p>您可以点击 <a style="text-decoration:none; color:#12addb" href="' . htmlspecialchars(get_comment_link($parent_id)) . '">查看回复的完整內容 </a>，欢迎再次光临 <a style="text-decoration:none; color:#12addb" href="' . get_option('home') . '">' . get_option('blogname') . ' </a>。</p></div></div></td></tr></tbody></table>';   
$from = "From: \"" . get_option('blogname') . "\" <$wp_email>";   
$headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";   
wp_mail( $to, $subject, $message, $headers );   
//echo 'mail to ', $to, '<br/> ' , $subject, $message; // for testing   
}   
}   
add_action('comment_post', 'comment_mail_notify');  


//Remove wordpress version from the header
function i_want_no_generators()
{
 		return '';
	}
add_filter('the_generator','i_want_no_generators');


function time_since($older_date, $newer_date = false)
{
    $chunks = array(
    array(60 * 60 * 24 * 365 , '年'),
    array(60 * 60 * 24 * 30 , '月'),
    array(60 * 60 * 24 * 7, '周'),
    array(60 * 60 * 24 , '天'),
    array(60 * 60 , '小时'),
    array(60 , '分钟'),
    );
      
    $newer_date = ($newer_date == false) ? (time()+(60*60*get_settings("gmt_offset"))) : $newer_date;
    $since = $newer_date - abs(strtotime($older_date));
      
    //根据自己的需要调整时间段，下面的24则表示小时，根据需要调整吧
    if($since < 60 * 60 * 72){
    for ($i = 0, $j = count($chunks); $i < $j; $i++)
    {
    $seconds = $chunks[$i][0];
    $name = $chunks[$i][1];
      
    if (($count = floor($since / $seconds)) != 0)
    {
    break;
    }
    }
      
    $out = ($count == 1) ? '1 '.$name : "$count {$name}";
    return $out."前";
    }else{
    the_time(get_option('date_format'));
    }
}

//评论
function cleanr_theme_comment($comment, $args, $depth) {
$GLOBALS['comment'] = $comment; ?>
<div <?php comment_class('list-group-item'); ?> id="comment-<?php comment_ID() ?>">
	<div class="media">
		<a class="pull-left hidden-xs" href="<?php echo get_author_posts_url($comment->user_id); ?>">
			<?php echo get_avatar($comment,$size='50',$default='' ); ?>
		</a>
		<div class="media-body">
			<h4 class="media-heading">
				<?php printf(__('%s'), get_comment_author_link()) ?>
				<small class="pull-right"><?php echo time_since($comment->comment_date);?></small>
			</h4>
			<?php comment_text() ?>
			<?php comment_reply_link(array_merge( $args, array('reply_text' => '回复', 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
			<div class="comment-description"><?php echo the_author_meta('description',$comment->user_id); ?></div>
			<?php if ( is_admin() ) {   
			$url = get_bloginfo('url');   
			echo '<br><a id="delete-'. $comment->comment_ID .'" href="' . wp_nonce_url("$url/wp-admin/comment.php?action=deletecomment&p=" . $comment->comment_post_ID . '&c=' . $comment->comment_ID, 'delete-comment_' . $comment->comment_ID) . '"" >删除</a>';   
			} ?>
		</div>
	</div>
</div>

<?php } ?>