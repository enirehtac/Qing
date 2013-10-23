<footer>
	<div class="container">
		<i class="glyphicon glyphicon-copyright-mark"></i> 2013 电子科技大学 · 轻 · 开发模式 · <?php 
global $readtime;
$pagetime = round(microtime() - $readtime,3)*1000;
echo $pagetime . " ms";
?> 
 · 
<?php $users=wp_list_authors('echo=0&exclude_admin=0&hide_empty=0&optioncount=1&style=0');
$users=split(',',$users);
echo count($users), ' 用户'; ?>
 · 
<?php $count_posts = wp_count_posts(); echo $published_posts = $count_posts->publish;?>
&nbsp;主题
 · 
<?php echo $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments");?>
&nbsp;回复
<div style="float:right;text-align:right;">Lovingly Present by <a href="http://uestc.tk">Qing</a>.</div>	
  </div>

</footer>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
aria-hidden="true">
	<form role="form">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title" id="myModalLabel">
					<i class="glyphicon glyphicon-search"></i> 搜索电子科大...搜不到妹纸的:(
				</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<input type="text" class="form-control" id="exampleInputEmail1" placeholder="请输入您要搜索的内容..." name="s">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">
					取消
				</button>
				<input type="submit" class="btn btn-primary" value="提交">
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	</form>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->
</body>
<?php wp_footer(); ?>
</html>