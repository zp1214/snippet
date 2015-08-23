//WordPress 技巧：显示同个父页面的其他子页面的链接

<!--子页面分类显示begin-->
<?php
  global $post;
  if ($post->post_parent)
		$children = wp_list_pages("title_li=&child_of=".$post->post_parent."&echo=0");
  else
    	$children = wp_list_pages("title_li=&child_of=".$post->ID."&echo=0");
 
  if ($children) { ?>
<div style="border:1px dashed #ddd; padding:10px; margin:10px 0;line-height:26px;border-radius: 3px;"><div>
  <?php
  if ($post->post_parent)
   echo '<h2>父窗口：' . "<a href=" . get_permalink( $post->post_parent ) . ">" .
   get_the_title($post->post_parent) . '</a></h2>';
  else
   echo '<h2>'.get_the_title($post->ID).'目录：</h2>';
  ?>
  <ul>
  <?php echo $children; ?>
  </ul>
</div>
<?php } ?>
<!--子页面分类显示end-->



//简化后的代码
<?php
	global $post;
  	if ($post->post_parent) {
		echo '<ul>';
		wp_list_pages("title_li=&child_of=".$post->post_parent);
		echo '</ul>';
	}
	else
	{
 		echo '<ul>';
   		wp_list_pages("title_li=&child_of=".$post->ID);
		echo '</ul>';
	}
?>