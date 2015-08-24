<?php
function oxtheme_get_https_avatar($avatar) {
	//~ 替换为 https 的域名
	$avatar = str_replace(array("www.gravatar.com", "0.gravatar.com", "1.gravatar.com", "2.gravatar.com"), "secure.gravatar.com", $avatar);
	//~ 替换为 https 协议
	$avatar = str_replace("http://", "https://", $avatar);
	return $avatar;
}
add_filter('get_avatar', 'oxtheme_get_https_avatar');
?>