<?php 
// [accordion]
function ux_banner($atts, $content=null, $code) {
	$params = shortcode_atts(array(
		'img1' => '',
		'img2' => ''
	), $atts);
	return renderView( dirname(__FILE__) . '/views/banner.php' ,$params);
}
add_shortcode('banner', 'ux_banner');