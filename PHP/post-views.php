<!-- //统计当前日志的浏览数 -->
<!-- 
下面的代码是目前我爱水煮鱼所使用的日志浏览数统计代码核心部分，
和 WP-Postviews 有点不同，因为我爱水煮鱼的博客使用内存缓存，
所以我把统计数写入到 WordPress 对象缓存中，
统计每增加 10 次之后才写入数据库中，
这样大大减少数据库的请求，加快 WordPress 的效率。
下面用到了一个新的自定义字段相关函数
 update_post_meta($post_id, $meta_key, $meta_value, $prev_value); ，
 就是可以通过程序来更新自定义字段。 
-->
<?php
  $post_id = $post->ID;
  $post_views = wp_cache_get($post_id,'views');

  if($post_views === false){
    $post_views = get_post_meta($post_id, "views",true);
    if(!$post_views) $post_views = 0;
  }

  $post_views = $post_views + 1;

  wp_cache_set($post_id,$post_views,'views');
        
  if($post_views%10 == 0){
    update_post_meta($post_id, 'views', $post_views);
  }

  echo $post_views;
?>