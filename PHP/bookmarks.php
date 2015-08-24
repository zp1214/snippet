开启友情链接选项

add_filter( 'pre_option_link_manager_enabled', '__return_true' );

调用

<?php 
$bookmarks = get_bookmarks('orderby=rand&limit=50&category=');if ( !emptyempty($bookmarks) ) {
	foreach ($bookmarks as $bookmark) {
		echo '<li><a href="' . $bookmark->link_url . '" title="' . $bookmark->link_description . '" target="_blank" >' . $bookmark->link_name . '</a></li>';
	}
}
?>
