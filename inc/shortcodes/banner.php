<?php 
// [banner img1="597,598,599,600" img2="602,603"/]
function ux_banner($atts, $content=null, $code) {
	$params = shortcode_atts(array(
		'img1' => '',
		'img2' => ''
	), $atts);
	return renderView( dirname(__FILE__) . '/views/banner.php' ,$params);
}
add_shortcode('banner', 'ux_banner');